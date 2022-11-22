/*!
 * Copyright 2012, Chris Wanstrath
 * Released under the MIT License
 * https://github.com/defunkt/jquery-pjax
 */

(function($){

// When called on a container with a selector, fetches the href with
// ajax into the container or with the data-pjax attribute on the link
// itself.
//
// Tries to make sure the back button and ctrl+click work the way
// you'd expect.
//
// Exported as $.fn.pjax
//
// Accepts a jQuery ajax options object that may include these
// pjax specific options:
//
//
// container - String selector for the element where to place the response body.
//      push - Whether to pushState the URL. Defaults to true (of course).
//   replace - Want to use replaceState instead? That's cool.
//
// For convenience the second parameter can be either the container or
// the options object.
//
// Returns the jQuery object
function fnPjax(selector, container, options) {
  options = optionsFor(container, options)
  return this.on('click.pjax', selector, function(event) {
    var opts = options
    if (!opts.container) {
      opts = $.extend({}, options)
      opts.container = $(this).attr('data-pjax')
    }
    handleClick(event, opts)
  })
}

// Public: pjax on click handler
//
// Exported as $.pjax.click.
//
// event   - "click" jQuery.Event
// options - pjax options
//
// Examples
//
//   $(document).on('click', 'a', $.pjax.click)
//   // is the same as
//   $(document).pjax('a')
//
// Returns nothing.
function handleClick(event, container, options) {
  options = optionsFor(container, options)

  var link = event.currentTarget
  var $link = $(link)

  if (link.tagName.toUpperCase() !== 'A')
    throw "$.fn.pjax or $.pjax.click requires an anchor element"

  // Middle click, cmd click, and ctrl click should open
  // links in a new tab as normal.
  if ( event.which > 1 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey )
    return

  // Ignore cross origin links
  if ( location.protocol !== link.protocol || location.hostname !== link.hostname )
    return

  // Ignore case when a hash is being tacked on the current URL
  if ( link.href.indexOf('#') > -1 && stripHash(link) == stripHash(location) )
    return

  // Ignore event with default prevented
  if (event.isDefaultPrevented())
    return

  var defaults = {
    url: link.href,
    container: $link.attr('data-pjax'),
    target: link
  }

  var opts = $.extend({}, defaults, options)
  var clickEvent = $.Event('pjax:click')
  $link.trigger(clickEvent, [opts])

  if (!clickEvent.isDefaultPrevented()) {
    pjax(opts)
    event.preventDefault()
    $link.trigger('pjax:clicked', [opts])
  }
}

// Public: pjax on form submit handler
//
// Exported as $.pjax.submit
//
// event   - "click" jQuery.Event
// options - pjax options
//
// Examples
//
//  $(document).on('submit', 'form', function(event) {
//    $.pjax.submit(event, '[data-pjax-container]')
//  })
//
// Returns nothing.
function handleSubmit(event, container, options) {
  options = optionsFor(container, options)

  var form = event.currentTarget
  var $form = $(form)

  if (form.tagName.toUpperCase() !== 'FORM')
    throw "$.pjax.submit requires a form element"

  var defaults = {
    type: ($form.attr('method') || 'GET').toUpperCase(),
    url: $form.attr('action'),
    container: $form.attr('data-pjax'),
    target: form
  }

  if (defaults.type !== 'GET' && window.FormData !== undefined) {
    defaults.data = new FormData(form)
    defaults.processData = false
    defaults.contentType = false
  } else {
    // Can't handle file uploads, exit
    if ($form.find(':file').length) {
      return
    }

    // Fallback to manually serializing the fields
    defaults.data = $form.serializeArray()
  }

  pjax($.extend({}, defaults, options))

  event.preventDefault()
}

// Loads a URL with ajax, puts the response body inside a container,
// then pushState()'s the loaded URL.
//
// Works just like $.ajax in that it accepts a jQuery ajax
// settings object (with keys like url, type, data, etc).
//
// Accepts these extra keys:
//
// container - String selector for where to stick the response body.
//      push - Whether to pushState the URL. Defaults to true (of course).
//   replace - Want to use replaceState instead? That's cool.
//
// Use it just like $.ajax:
//
//   var xhr = $.pjax({ url: this.href, container: '#main' })
//   console.log( xhr.readyState )
//
// Returns whatever $.ajax returns.
function pjax(options) {
  options = $.extend(true, {}, $.ajaxSettings, pjax.defaults, options)

  if ($.isFunction(options.url)) {
    options.url = options.url()
  }

  var hash = parseURL(options.url).hash

  var containerType = $.type(options.container)
  if (containerType !== 'string') {
    throw "expected string value for 'container' option; got " + containerType
  }
  var context = options.context = $(options.container)
  if (!context.length) {
    throw "the container selector '" + options.container + "' did not match anything"
  }

  // We want the browser to maintain two separate internal caches: one
  // for pjax'd partial page loads and one for normal page loads.
  // Without adding this secret parameter, some browsers will often
  // confuse the two.
  if (!options.data) options.data = {}
  if ($.isArray(options.data)) {
    options.data.push({name: '_pjax', value: options.container})
  } else {
    options.data._pjax = options.container
  }

  function fire(type, args, props) {
    if (!props) props = {}
    props.relatedTarget = options.target
    var event = $.Event(type, props)
    context.trigger(event, args)
    return !event.isDefaultPrevented()
  }

  var timeoutTimer

  options.beforeSend = function(xhr, settings) {
    // No timeout for non-GET requests
    // Its not safe to request the resource again with a fallback method.
    if (settings.type !== 'GET') {
      settings.timeout = 0
    }

    xhr.setRequestHeader('X-PJAX', 'true')
    xhr.setRequestHeader('X-PJAX-Container', options.container)

    if (!fire('pjax:beforeSend', [xhr, settings]))
      return false

    if (settings.timeout > 0) {
      timeoutTimer = setTimeout(function() {
        if (fire('pjax:timeout', [xhr, options]))
          xhr.abort('timeout')
      }, settings.timeout)

      // Clear timeout setting so jquerys internal timeout isn't invoked
      settings.timeout = 0
    }

    var url = parseURL(settings.url)
    if (hash) url.hash = hash
    options.requestUrl = stripInternalParams(url)
  }

  options.complete = function(xhr, textStatus) {
    if (timeoutTimer)
      clearTimeout(timeoutTimer)

    fire('pjax:complete', [xhr, textStatus, options])

    fire('pjax:end', [xhr, options])
  }

  options.error = function(xhr, textStatus, errorThrown) {
    var container = extractContainer("", xhr, options)

    var allowed = fire('pjax:error', [xhr, textStatus, errorThrown, options])
    if (options.type == 'GET' && textStatus !== 'abort' && allowed) {
      locationReplace(container.url)
    }
  }

  options.success = function(data, status, xhr) {
    var previousState = pjax.state

    // If $.pjax.defaults.version is a function, invoke it first.
    // Otherwise it can be a static string.
    var currentVersion = typeof $.pjax.defaults.version === 'function' ?
      $.pjax.defaults.version() :
      $.pjax.defaults.version

    var latestVersion = xhr.getResponseHeader('X-PJAX-Version')

    var container = extractContainer(data, xhr, options)

    var url = parseURL(container.url)
    if (hash) {
      url.hash = hash
      container.url = url.href
    }

    // If there is a layout version mismatch, hard load the new url
    if (currentVersion && latestVersion && currentVersion !== latestVersion) {
      locationReplace(container.url)
      return
    }

    // If the new response is missing a body, hard load the page
    if (!container.contents) {
      locationReplace(container.url)
      return
    }

    pjax.state = {
      id: options.id || uniqueId(),
      url: container.url,
      title: container.title,
      container: options.container,
      fragment: options.fragment,
      timeout: options.timeout
    }

    if (options.push || options.replace) {
      window.history.replaceState(pjax.state, container.title, container.url)
    }

    // Only blur the focus if the focused element is within the container.
    var blurFocus = $.contains(context, document.activeElement)

    // Clear out any focused controls before inserting new page contents.
    if (blurFocus) {
      try {
        document.activeElement.blur()
      } catch (e) { /* ignore */ }
    }

    if (container.title) document.title = container.title

    fire('pjax:beforeReplace', [container.contents, options], {
      state: pjax.state,
      previousState: previousState
    })
    context.html(container.contents)

    // FF bug: Won't autofocus fields that are inserted via JS.
    // This behavior is incorrect. So if theres no current focus, autofocus
    // the last field.
    //
    // http://www.w3.org/html/wg/drafts/html/master/forms.html
    var autofocusEl = context.find('input[autofocus], textarea[autofocus]').last()[0]
    if (autofocusEl && document.activeElement !== autofocusEl) {
      autofocusEl.focus()
    }

    executeScriptTags(container.scripts)

    var scrollTo = options.scrollTo

    // Ensure browser scrolls to the element referenced by the URL anchor
    if (hash) {
      var name = decodeURIComponent(hash.slice(1))
      var target = document.getElementById(name) || document.getElementsByName(name)[0]
      if (target) scrollTo = $(target).offset().top
    }

    if (typeof scrollTo == 'number') $(window).scrollTop(scrollTo)

    fire('pjax:success', [data, status, xhr, options])
  }


  // Initialize pjax.state for the initial page load. Assume we're
  // using the container and options of the link we're loading for the
  // back button to the initial page. This ensures good back button
  // behavior.
  if (!pjax.state) {
    pjax.state = {
      id: uniqueId(),
      url: window.location.href,
      title: document.title,
      container: options.container,
      fragment: options.fragment,
      timeout: options.timeout
    }
    window.history.replaceState(pjax.state, document.title)
  }

  // Cancel the current request if we're already pjaxing
  abortXHR(pjax.xhr)

  pjax.options = options
  var xhr = pjax.xhr = $.ajax(options)

  if (xhr.readyState > 0) {
    if (options.push && !options.replace) {
      // Cache current container element before replacing it
      cachePush(pjax.state.id, [options.container, cloneContents(context)])

      window.history.pushState(null, "", options.requestUrl)
    }

    fire('pjax:start', [xhr, options])
    fire('pjax:send', [xhr, options])
  }

  return pjax.xhr
}

// Public: Reload current page with pjax.
//
// Returns whatever $.pjax returns.
function pjaxReload(container, options) {
  var defaults = {
    url: window.location.href,
    push: false,
    replace: true,
    scrollTo: false
  }

  return pjax($.extend(defaults, optionsFor(container, options)))
}

// Internal: Hard replace current state with url.
//
// Work for around WebKit
//   https://bugs.webkit.org/show_bug.cgi?id=93506
//
// Returns nothing.
function locationReplace(url) {
  window.history.replaceState(null, "", pjax.state.url)
  window.location.replace(url)
}


var initialPop = true
var initialURL = window.location.href
var initialState = window.history.state

// Initialize $.pjax.state if possible
// Happens when reloading a page and coming forward from a different
// session history.
if (initialState && initialState.container) {
  pjax.state = initialState
}

// Non-webkit browsers don't fire an initial popstate event
if ('state' in window.history) {
  initialPop = false
}

// popstate handler takes care of the back and forward buttons
//
// You probably shouldn't use pjax on pages with other pushState
// stuff yet.
function onPjaxPopstate(event) {

  // Hitting back or forward should override any pending PJAX request.
  if (!initialPop) {
    abortXHR(pjax.xhr)
  }

  var previousState = pjax.state
  var state = event.state
  var direction

  if (state && state.container) {
    // When coming forward from a separate history session, will get an
    // initial pop with a state we are already at. Skip reloading the current
    // page.
    if (initialPop && initialURL == state.url) return

    if (previousState) {
      // If popping back to the same state, just skip.
      // Could be clicking back from hashchange rather than a pushState.
      if (previousState.id === state.id) return

      // Since state IDs always increase, we can deduce the navigation direction
      direction = previousState.id < state.id ? 'forward' : 'back'
    }

    var cache = cacheMapping[state.id] || []
    var containerSelector = cache[0] || state.container
    var container = $(containerSelector), contents = cache[1]

    if (container.length) {
      if (previousState) {
        // Cache current container before replacement and inform the
        // cache which direction the history shifted.
        cachePop(direction, previousState.id, [containerSelector, cloneContents(container)])
      }

      var popstateEvent = $.Event('pjax:popstate', {
        state: state,
        direction: direction
      })
      container.trigger(popstateEvent)

      var options = {
        id: state.id,
        url: state.url,
        container: containerSelector,
        push: false,
        fragment: state.fragment,
        timeout: state.timeout,
        scrollTo: false
      }

      if (contents) {
        container.trigger('pjax:start', [null, options])

        pjax.state = state
        if (state.title) document.title = state.title
        var beforeReplaceEvent = $.Event('pjax:beforeReplace', {
          state: state,
          previousState: previousState
        })
        container.trigger(beforeReplaceEvent, [contents, options])
        container.html(contents)

        container.trigger('pjax:end', [null, options])
      } else {
        pjax(options)
      }

      // Force reflow/relayout before the browser tries to restore the
      // scroll position.
      container[0].offsetHeight // eslint-disable-line no-unused-expressions
    } else {
      locationReplace(location.href)
    }
  }
  initialPop = false
}

// Fallback version of main pjax function for browsers that don't
// support pushState.
//
// Returns nothing since it retriggers a hard form submission.
function fallbackPjax(options) {
  var url = $.isFunction(options.url) ? options.url() : options.url,
      method = options.type ? options.type.toUpperCase() : 'GET'

  var form = $('<form>', {
    method: method === 'GET' ? 'GET' : 'POST',
    action: url,
    style: 'display:none'
  })

  if (method !== 'GET' && method !== 'POST') {
    form.append($('<input>', {
      type: 'hidden',
      name: '_method',
      value: method.toLowerCase()
    }))
  }

  var data = options.data
  if (typeof data === 'string') {
    $.each(data.split('&'), function(index, value) {
      var pair = value.split('=')
      form.append($('<input>', {type: 'hidden', name: pair[0], value: pair[1]}))
    })
  } else if ($.isArray(data)) {
    $.each(data, function(index, value) {
      form.append($('<input>', {type: 'hidden', name: value.name, value: value.value}))
    })
  } else if (typeof data === 'object') {
    var key
    for (key in data)
      form.append($('<input>', {type: 'hidden', name: key, value: data[key]}))
  }

  $(document.body).append(form)
  form.submit()
}

// Internal: Abort an XmlHttpRequest if it hasn't been completed,
// also removing its event handlers.
function abortXHR(xhr) {
  if ( xhr && xhr.readyState < 4) {
    xhr.onreadystatechange = $.noop
    xhr.abort()
  }
}

// Internal: Generate unique id for state object.
//
// Use a timestamp instead of a counter since ids should still be
// unique across page loads.
//
// Returns Number.
function uniqueId() {
  return (new Date).getTime()
}

function cloneContents(container) {
  var cloned = container.clone()
  // Unmark script tags as already being eval'd so they can get executed again
  // when restored from cache. HAXX: Uses jQuery internal method.
  cloned.find('script').each(function(){
    if (!this.src) $._data(this, 'globalEval', false)
  })
  return cloned.contents()
}

// Internal: Strip internal query params from parsed URL.
//
// Returns sanitized url.href String.
function stripInternalParams(url) {
  url.search = url.search.replace(/([?&])(_pjax|_)=[^&]*/g, '').replace(/^&/, '')
  return url.href.replace(/\?($|#)/, '$1')
}

// Internal: Parse URL components and returns a Locationish object.
//
// url - String URL
//
// Returns HTMLAnchorElement that acts like Location.
function parseURL(url) {
  var a = document.createElement('a')
  a.href = url
  return a
}

// Internal: Return the `href` component of given URL object with the hash
// portion removed.
//
// location - Location or HTMLAnchorElement
//
// Returns String
function stripHash(location) {
  return location.href.replace(/#.*/, '')
}

// Internal: Build options Object for arguments.
//
// For convenience the first parameter can be either the container or
// the options object.
//
// Examples
//
//   optionsFor('#container')
//   // => {container: '#container'}
//
//   optionsFor('#container', {push: true})
//   // => {container: '#container', push: true}
//
//   optionsFor({container: '#container', push: true})
//   // => {container: '#container', push: true}
//
// Returns options Object.
function optionsFor(container, options) {
  if (container && options) {
    options = $.extend({}, options)
    options.container = container
    return options
  } else if ($.isPlainObject(container)) {
    return container
  } else {
    return {container: container}
  }
}

// Internal: Filter and find all elements matching the selector.
//
// Where $.fn.find only matches descendants, findAll will test all the
// top level elements in the jQuery object as well.
//
// elems    - jQuery object of Elements
// selector - String selector to match
//
// Returns a jQuery object.
function findAll(elems, selector) {
  return elems.filter(selector).add(elems.find(selector))
}

function parseHTML(html) {
  return $.parseHTML(html, document, true)
}

// Internal: Extracts container and metadata from response.
//
// 1. Extracts X-PJAX-URL header if set
// 2. Extracts inline <title> tags
// 3. Builds response Element and extracts fragment if set
//
// data    - String response data
// xhr     - XHR response
// options - pjax options Object
//
// Returns an Object with url, title, and contents keys.
function extractContainer(data, xhr, options) {
  var obj = {}, fullDocument = /<html/i.test(data)

  // Prefer X-PJAX-URL header if it was set, otherwise fallback to
  // using the original requested url.
  var serverUrl = xhr.getResponseHeader('X-PJAX-URL')
  obj.url = serverUrl ? stripInternalParams(parseURL(serverUrl)) : options.requestUrl

  var $head, $body
  // Attempt to parse response html into elements
  if (fullDocument) {
    $body = $(parseHTML(data.match(/<body[^>]*>([\s\S.]*)<\/body>/i)[0]))
    var head = data.match(/<head[^>]*>([\s\S.]*)<\/head>/i)
    $head = head != null ? $(parseHTML(head[0])) : $body
  } else {
    $head = $body = $(parseHTML(data))
  }

  // If response data is empty, return fast
  if ($body.length === 0)
    return obj

  // If there's a <title> tag in the header, use it as
  // the page's title.
  obj.title = findAll($head, 'title').last().text()

  if (options.fragment) {
    var $fragment = $body
    // If they specified a fragment, look for it in the response
    // and pull it out.
    if (options.fragment !== 'body') {
      $fragment = findAll($fragment, options.fragment).first()
    }

    if ($fragment.length) {
      obj.contents = options.fragment === 'body' ? $fragment : $fragment.contents()

      // If there's no title, look for data-title and title attributes
      // on the fragment
      if (!obj.title)
        obj.title = $fragment.attr('title') || $fragment.data('title')
    }

  } else if (!fullDocument) {
    obj.contents = $body
  }

  // Clean up any <title> tags
  if (obj.contents) {
    // Remove any parent title elements
    obj.contents = obj.contents.not(function() { return $(this).is('title') })

    // Then scrub any titles from their descendants
    obj.contents.find('title').remove()

    // Gather all script[src] elements
    obj.scripts = findAll(obj.contents, 'script[src]').remove()
    obj.contents = obj.contents.not(obj.scripts)
  }

  // Trim any whitespace off the title
  if (obj.title) obj.title = $.trim(obj.title)

  return obj
}

// Load an execute scripts using standard script request.
//
// Avoids jQuery's traditional $.getScript which does a XHR request and
// globalEval.
//
// scripts - jQuery object of script Elements
//
// Returns nothing.
function executeScriptTags(scripts) {
  if (!scripts) return

  var existingScripts = $('script[src]')

  scripts.each(function() {
    var src = this.src
    var matchedScripts = existingScripts.filter(function() {
      return this.src === src
    })
    if (matchedScripts.length) return

    var script = document.createElement('script')
    var type = $(this).attr('type')
    if (type) script.type = type
    script.src = $(this).attr('src')
    document.head.appendChild(script)
  })
}

// Internal: History DOM caching class.
var cacheMapping      = {}
var cacheForwardStack = []
var cacheBackStack    = []

// Push previous state id and container contents into the history
// cache. Should be called in conjunction with `pushState` to save the
// previous container contents.
//
// id    - State ID Number
// value - DOM Element to cache
//
// Returns nothing.
function cachePush(id, value) {
  cacheMapping[id] = value
  cacheBackStack.push(id)

  // Remove all entries in forward history stack after pushing a new page.
  trimCacheStack(cacheForwardStack, 0)

  // Trim back history stack to max cache length.
  trimCacheStack(cacheBackStack, pjax.defaults.maxCacheLength)
}

// Shifts cache from directional history cache. Should be
// called on `popstate` with the previous state id and container
// contents.
//
// direction - "forward" or "back" String
// id        - State ID Number
// value     - DOM Element to cache
//
// Returns nothing.
function cachePop(direction, id, value) {
  var pushStack, popStack
  cacheMapping[id] = value

  if (direction === 'forward') {
    pushStack = cacheBackStack
    popStack  = cacheForwardStack
  } else {
    pushStack = cacheForwardStack
    popStack  = cacheBackStack
  }

  pushStack.push(id)
  id = popStack.pop()
  if (id) delete cacheMapping[id]

  // Trim whichever stack we just pushed to to max cache length.
  trimCacheStack(pushStack, pjax.defaults.maxCacheLength)
}

// Trim a cache stack (either cacheBackStack or cacheForwardStack) to be no
// longer than the specified length, deleting cached DOM elements as necessary.
//
// stack  - Array of state IDs
// length - Maximum length to trim to
//
// Returns nothing.
function trimCacheStack(stack, length) {
  while (stack.length > length)
    delete cacheMapping[stack.shift()]
}

// Public: Find version identifier for the initial page load.
//
// Returns String version or undefined.
function findVersion() {
  return $('meta').filter(function() {
    var name = $(this).attr('http-equiv')
    return name && name.toUpperCase() === 'X-PJAX-VERSION'
  }).attr('content')
}

// Install pjax functions on $.pjax to enable pushState behavior.
//
// Does nothing if already enabled.
//
// Examples
//
//     $.pjax.enable()
//
// Returns nothing.
function enable() {
  $.fn.pjax = fnPjax
  $.pjax = pjax
  $.pjax.enable = $.noop
  $.pjax.disable = disable
  $.pjax.click = handleClick
  $.pjax.submit = handleSubmit
  $.pjax.reload = pjaxReload
  $.pjax.defaults = {
    timeout: 650,
    push: true,
    replace: false,
    type: 'GET',
    dataType: 'html',
    scrollTo: 0,
    maxCacheLength: 20,
    version: findVersion
  }
  $(window).on('popstate.pjax', onPjaxPopstate)
}

// Disable pushState behavior.
//
// This is the case when a browser doesn't support pushState. It is
// sometimes useful to disable pushState for debugging on a modern
// browser.
//
// Examples
//
//     $.pjax.disable()
//
// Returns nothing.
function disable() {
  $.fn.pjax = function() { return this }
  $.pjax = fallbackPjax
  $.pjax.enable = enable
  $.pjax.disable = $.noop
  $.pjax.click = $.noop
  $.pjax.submit = $.noop
  $.pjax.reload = function() { window.location.reload() }

  $(window).off('popstate.pjax', onPjaxPopstate)
}


// Add the state property to jQuery's event object so we can use it in
// $(window).bind('popstate')
if ($.event.props && $.inArray('state', $.event.props) < 0) {
  $.event.props.push('state')
} else if (!('state' in $.Event.prototype)) {
  $.event.addProp('state')
}

// Is pjax supported by this browser?
$.support.pjax =
  window.history && window.history.pushState && window.history.replaceState &&
  // pushState isn't reliable on iOS until 5.
  !navigator.userAgent.match(/((iPod|iPhone|iPad).+\bOS\s+[1-4]\D|WebApps\/.+CFNetwork)/)

if ($.support.pjax) {
  enable()
} else {
  disable()
}

})(jQuery)

/**
 * Copyright (c) 2007 Ariel Flesler - aflesler ○ gmail • com | https://github.com/flesler
 * Licensed under MIT
 * @author Ariel Flesler
 * @version 2.1.3
 */
;(function(factory){'use strict';if(typeof define==='function'&&define.amd){define(['jquery'],factory)}else if(typeof module!=='undefined'&&module.exports){module.exports=factory(require('jquery'))}else{factory(jQuery)}})(function($){'use strict';var $scrollTo=$.scrollTo=function(target,duration,settings){return $(window).scrollTo(target,duration,settings)};$scrollTo.defaults={axis:'xy',duration:0,limit:true};function isWin(elem){return!elem.nodeName||$.inArray(elem.nodeName.toLowerCase(),['iframe','#document','html','body'])!==-1}function isFunction(obj){return typeof obj==='function'}$.fn.scrollTo=function(target,duration,settings){if(typeof duration==='object'){settings=duration;duration=0}if(typeof settings==='function'){settings={onAfter:settings}}if(target==='max'){target=9e9}settings=$.extend({},$scrollTo.defaults,settings);duration=duration||settings.duration;var queue=settings.queue&&settings.axis.length>1;if(queue){duration/=2}settings.offset=both(settings.offset);settings.over=both(settings.over);return this.each(function(){if(target===null){return}var win=isWin(this),elem=win?this.contentWindow||window:this,$elem=$(elem),targ=target,attr={},toff;switch(typeof targ){case 'number':case 'string':if(/^([+-]=?)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=win?$(targ):$(targ,elem);case 'object':if(targ.length===0){return}if(targ.is||targ.style){toff=(targ=$(targ)).offset()}}var offset=isFunction(settings.offset)&&settings.offset(elem,targ)||settings.offset;$.each(settings.axis.split(''),function(i,axis){var Pos=axis==='x'?'Left':'Top',pos=Pos.toLowerCase(),key='scroll'+Pos,prev=$elem[key](),max=$scrollTo.max(elem,axis);if(toff){attr[key]=toff[pos]+(win?0:prev-$elem.offset()[pos]);if(settings.margin){attr[key]-=parseInt(targ.css('margin'+Pos),10)||0;attr[key]-=parseInt(targ.css('border'+Pos+'Width'),10)||0}attr[key]+=offset[pos]||0;if(settings.over[pos]){attr[key]+=targ[axis==='x'?'width':'height']()*settings.over[pos]}}else{var val=targ[pos];attr[key]=val.slice&&val.slice(-1)==='%'?parseFloat(val)/100*max:val}if(settings.limit&&/^\d+$/.test(attr[key])){attr[key]=attr[key]<=0?0:Math.min(attr[key],max)}if(!i&&settings.axis.length>1){if(prev===attr[key]){attr={}}else if(queue){animate(settings.onAfterFirst);attr={}}}});animate(settings.onAfter);function animate(callback){var opts=$.extend({},settings,{queue:true,duration:duration,complete:callback&&function(){callback.call(elem,targ,settings)}});$elem.animate(attr,opts)}})};$scrollTo.max=function(elem,axis){var Dim=axis==='x'?'Width':'Height',scroll='scroll'+Dim;if(!isWin(elem)){return elem[scroll]-$(elem)[Dim.toLowerCase()]()}var size='client'+Dim,doc=elem.ownerDocument||elem.document,html=doc.documentElement,body=doc.body;return Math.max(html[scroll],body[scroll])-Math.min(html[size],body[size])};function both(val){return isFunction(val)||$.isPlainObject(val)?val:{top:val,left:val}}$.Tween.propHooks.scrollLeft=$.Tween.propHooks.scrollTop={get:function(t){return $(t.elem)[t.prop]()},set:function(t){var curr=this.get(t);if(t.options.interrupt&&t._last&&t._last!==curr){return $(t.elem).stop()}var next=Math.round(t.now);if(curr!==next){$(t.elem)[t.prop](next);t._last=this.get(t)}}};return $scrollTo});

jQuery(document).ready(function(){


	function initTemplateBus() {
		if ($('.filterElements').length) {
			var rowQuantity = 4; // количество рядов
			var cellQuantity = 7; // количество столбцов
			var minRowQuantity = 3;	// минимальное количество рядов
			var maxRowQuantity = 5;	// максимальное количество столбцов
			var minCellQuantity = 3; // максимальное количество рядов
			var maxCellQuantity = 20;// максимальное количество столбцов
			var rowQuantityInscriptionEl = document.querySelectorAll('.addRowColumnWrapp .rowQuantity')[0];
			var cellQuantityInscriptionEl = document.querySelectorAll('.addRowColumnWrapp .cellQuantity')[0];
			var seatsBlock = document.querySelectorAll('.busLayoutBlock .mainBusBlock .seats')[0];
			var addRowButt = document.querySelectorAll('.addRowColumnWrapp.rowOp .addRow')[0];
			var removeRowButt = document.querySelectorAll('.addRowColumnWrapp.rowOp .removeRow')[0];
			var addColumnButt = document.querySelectorAll('.addRowColumnWrapp.columnOp .addColumn')[0];
			var removeColumnButt = document.querySelectorAll('.addRowColumnWrapp.columnOp .removeColumn')[0];
			var busBodeyClasses = ['threeRowsOfSeats','fiveRowsOfSeats'];
			//-- add methods
			//функция очистки класса, отвечающего за габариты шаблона
			function cleanBodeyBusCountRowClasses(busBodeyClasses){
				var busBodey  = document.querySelectorAll('.busLayoutBlock .mainBusBlock .busBodey')[0];
				for (var i = 0; i < busBodeyClasses.length; i++){
					busBodey.classList.remove(busBodeyClasses[i]);
				}
			}
			addRowButt.onclick = function(){
				if (rowQuantity < maxRowQuantity){
					addRow(cellQuantity,rowQuantity);
					rowQuantity++;
					refreshNumInscriprion(rowQuantityInscriptionEl,rowQuantity);
					refreshInputOfNumIndicatorValue(rowQuantityInscriptionEl,rowQuantity);
					changeBusBodeyClassname(rowQuantity);
					if (autoNumbering)
						autoAdditionOfNumElementsTotal();
				}
			}
			removeRowButt.onclick = function(){
				if (rowQuantity > minRowQuantity){
					removeLastRow();
					rowQuantity--;
					refreshNumInscriprion(rowQuantityInscriptionEl,rowQuantity);
					refreshInputOfNumIndicatorValue(rowQuantityInscriptionEl,rowQuantity);
					changeBusBodeyClassname(rowQuantity);
					if (autoNumbering)
						autoAdditionOfNumElementsTotal();
				}
			}
			addColumnButt.onclick = function(){
				if (cellQuantity < maxCellQuantity){
					addColumn();
					cellQuantity++;
					refreshNumInscriprion(cellQuantityInscriptionEl,cellQuantity);
					refreshInputOfNumIndicatorValue(cellQuantityInscriptionEl,cellQuantity);
					changeBusBodeyClassname(rowQuantity);
					if (autoNumbering)
						autoAdditionOfNumElementsTotal();
				}
			}
			removeColumnButt.onclick = function(){
				if (cellQuantity > minCellQuantity){
					removeColumn();
					cellQuantity--;
					refreshNumInscriprion(cellQuantityInscriptionEl,cellQuantity);
					refreshInputOfNumIndicatorValue(cellQuantityInscriptionEl,cellQuantity);
					if (autoNumbering)
						autoAdditionOfNumElementsTotal();
				}
			}
			//обновляет индикаторы столбцов и строк;
			function refreshNumInscriprion(numElement,num){
				numElement.firstChild.nodeValue = num;
			}
			//обновляет значение инпута индикаторов столбцов и строк;
			function refreshInputOfNumIndicatorValue(numElement,num){
				function getComplementaryInput(){
					var complementaryInput = numElement.parentNode.querySelectorAll('input.complementaryInput')[0];
					var inputForReturn;
					if (!complementaryInput){
						var compInput = document.createElement('INPUT');
						compInput.classList.add('complementaryInput');
						compInput.name = 'ranks';
						compInput.type = 'hidden';
						var buttonsList = numElement.parentNode.querySelectorAll('ul.buttons')[0];
						numElement.parentNode.insertBefore(compInput,buttonsList);
						inputForReturn = compInput;
					}else{
						inputForReturn = complementaryInput;
					}
					return inputForReturn;
				}
				var compInput = getComplementaryInput();
				compInput.value = num;
				console.log('инпут: ' + compInput);
				console.log('значение: ' + compInput.value);
			}
			//получаем список всех клеток
			function getAllCells(seatsBlock){
				var cells = [];
				var cell = seatsBlock.firstChild;
				while (cell){
					if (cell.tagName == 'DIV')
						cells.push(cell);
					cell = cell.nextSibling;
				}
				return cells;
			}
			//поиск последних ячеек рядов
			function getLastCellsInRows(){
				var cells = getAllCells(seatsBlock);
				var lastCells = [];
				for (var i = 0; i < cells.length; i++){
					if ((i + 1)%cellQuantity == 0)
						lastCells.push(cells[i]);
				}
				return lastCells;
			}
			//добавляем клетки последовательно одна за одной
			function addCellLinear(cellClassName,numOfRow){
				var cell = document.createElement('DIV');
				seatsBlock.appendChild(cell);
				cell.className = cellClassName;
				cell.numOfRow = numOfRow;
				setMethodToCell(addRemoveCellPopup,'onclick',cell);
				setMethodToCell(removeCellPopup,'removeCellPopup',cell);
				setMethodToCell(addCellPopup,'addCellPopup',cell);
				setMethodToCell(addChangeButtonsToPopup,'addChangeButtonsToPopup',cell);
				setMethodToCell(addChangeToFreeCellButt,'addChangeToFreeCellButt',cell);
				setMethodToCell(addEnterSeatNumButt,'addEnterSeatNumButt',cell);
				setMethodToCell(addChangeToSeatCellButt,'addChangeToSeatCellButt',cell);
				setMethodToCell(addCancelButton,'addCancelButton',cell);
				setMethodToCell(addChangeToDriverButton,'addChangeToDriverButton',cell);
				//	setMethodToCell(removeButtonsFromPopup,'removeButtonsFromPopup',cell);
				return cell;
			}
			// добавляем ячейку после последней ячейки ряда
			function addCellForAddColumnFunc(lastRowCell,numOfRow){
				var cell = document.createElement('DIV');
				//	lastRowCell.parentNode.insertBefore(cell,lastRowCell);
				lastRowCell.parentNode.insertBefore(cell,lastRowCell.nextSibling);
				cell.className = 'cell seat';
				cell.numOfRow = numOfRow;
				setMethodToCell(addRemoveCellPopup,'onclick',cell);
				setMethodToCell(removeCellPopup,'removeCellPopup',cell);
				setMethodToCell(addCellPopup,'addCellPopup',cell);
				setMethodToCell(addChangeButtonsToPopup,'addChangeButtonsToPopup',cell);
				setMethodToCell(addChangeToFreeCellButt,'addChangeToFreeCellButt',cell);
				setMethodToCell(addEnterSeatNumButt,'addEnterSeatNumButt',cell);
				setMethodToCell(addChangeToSeatCellButt,'addChangeToSeatCellButt',cell);
				setMethodToCell(addCancelButton,'addCancelButton',cell);
				setMethodToCell(addChangeToDriverButton,'addChangeToDriverButton',cell);
				//	setMethodToCell(removeButtonsFromPopup,'removeButtonsFromPopup',cell);
			}
			//добавляем ряд
			function addRow(cellQuantity,numOfRow){
				for (var i = 0; i < cellQuantity; i++){
					var cell = addCellLinear('cell seat',numOfRow);
					if (i == 0)
						cell.style.clear = 'both';
				}
			}
			//получаем ячейки последнего ряда
			function getLastRow(seatsBlock){
				var cellsOfLastRow = [];
				var cell = seatsBlock.lastChild;
				while(cell && cell.style.clear != 'both'){
					if (cell.tagName == 'DIV')
						cellsOfLastRow.push(cell);
					cell = cell.previousSibling;
					if (cell.style.clear == 'both')
						cellsOfLastRow.push(cell);
				}
				return cellsOfLastRow;
			}
			//удаляем ячейки последнего ряда
			function removeLastRow(){
				var lastRow = getLastRow(seatsBlock);
				for (var i = 0; i < lastRow.length; i++){
					lastRow[i].parentNode.removeChild(lastRow[i]);
				}
			}
			//добавляем колонку ячеек
			function addColumn(){
				var lastCells = getLastCellsInRows();
				for (var i = 0; i <  lastCells.length; i++){
					addCellForAddColumnFunc(lastCells[i],(i + 1));
				}
			};
			// удаляем ряд ячеек
			function removeColumn(){
				var lastCells = getLastCellsInRows();
				for (var i = 0; i < lastCells.length; i++){
					lastCells[i].parentNode.removeChild(lastCells[i]);
				}
			}
			//изменяет класс тела автобуса. В зависимости от этого класса меняются параметры,
			//такие, как ширина авобуса, габариты частей с изображениями, размер и расположение
			//места водителя (если оно есть)
			function changeBusBodeyClassname(rowQuantity){
				var busBodey  = document.querySelectorAll('.busLayoutBlock .mainBusBlock .busBodey')[0];
				if (busBodey){
					cleanBodeyBusCountRowClasses(busBodeyClasses);
					switch (rowQuantity){
						case 3 :
							busBodey.classList.add('threeRowsOfSeats');
							break;
						case 5 :
							busBodey.classList.add('fiveRowsOfSeats');
							break;
					}
				}
			}
			//изначально на странице присутствует автобус без ячеек
			//функция добавляет ячейки при загрузке страницы в соответствии с
			//изночально заданными значениями их количества
			function firstAddOfSeats(rowQuantity,cellQuantity){
				changeBusBodeyClassname(rowQuantity);
				for (var i = 0; i < rowQuantity; i++){
					addRow(cellQuantity,(i + 1));
				}
			}
//	firstAddOfSeats(rowQuantity,cellQuantity);

			/*-------------- chenge of cell type -----------*/
			var activeCell; //-- клетка с добавленным popup
			//добавление popup клетке или удаление
			function addRemoveCellPopup(){
				if (activeCell != this){
					if (activeCell && activeCell.popup){
						activeCell.removeCellPopup();
					}
					this.addCellPopup();
					this.addChangeButtonsToPopup();
					activeCell = this;
				}else{
					this.removeCellPopup();
					activeCell = null;
				}
			}
			//добавляем popup
			function addCellPopup(){
				var cellPopupPositionBlock = document.createElement('DIV');
				this.appendChild(cellPopupPositionBlock);
				cellPopupPositionBlock.classList.add('cellPopupPositionBlock');
				var cellPopup = document.createElement('DIV');
				cellPopupPositionBlock.appendChild(cellPopup);
				cellPopup.classList.add('cellPopup');
				this.popup = cellPopupPositionBlock;
				this.popupInnerBlock = this.popup.querySelectorAll('.cellPopup')[0];
			}
			//удаляем popup
			function removeCellPopup(){
				this.removeChild(this.popup);
				this.popup = null;
				activeCell = null;
			}
			//задаём метод клетке
			function setMethodToCell(meth,methName,cell){
				cell[methName] = meth;
			}

			//-- executions

			/*-------------- chenge of cell type end -----------*/
			//добавляем кнопку изменения клетки на пустое место
			function addChangeToFreeCellButt(){
				var cell = this;
				//	var numElement = cell.querySelectorAll('.numElement')[0];
				//функция удаления номера с клетки
				function removeCellNum(){
					cell.numElement.parentNode.removeChild(cell.numElement);
					cell.numElement = null;
				}
				// метод изменения клетки на визуально пустую
				function changeToFreeCell(evt){
					evt.stopPropagation(); //отменяем click клетки
					cell.className = 'cell';
					cell.removeCellPopup();
					if (cell.numElement)
						removeCellNum();
					activeCell = null;
					if (autoNumbering)
						autoAdditionOfNumElementsTotal();
					addComplementaryInput(cell,'hidden','placeTypes[]','delete');
				}
				var button = document.createElement('DIV');
				this.popupInnerBlock.appendChild(button);
				button.className = 'changeButton changeToFreeCell';
				jQuery(button).click(changeToFreeCell);
			}
			// добавляем кнопку изменения клетки на клетку сидения
			function addChangeToSeatCellButt(){
				var cell = this;
				// метод изменения клетки на клетку сидения
				function changeToSeatCell(evt){
					evt.stopPropagation(); //отменяем click клетки
					cell.className = 'cell seat';
					cell.removeCellPopup();
					activeCell = null;
					if (autoNumbering){
						autoAdditionOfNumElementsTotal();
						addComplementaryInput(cell,'hidden','placeTypes[]',cell.numElement.firstChild.nodeValue);
					}else{
						removeOldCompInput(cell);
					}
				}
				var button = document.createElement('DIV');
				this.popupInnerBlock.appendChild(button);
				button.className = 'changeButton changeToSeat';
				jQuery(button).click(changeToSeatCell);
			}

			//добавляем инпут и кнопку добавления/изменения номера места
			function addEnterSeatNumButt(){
				var cell = this;
				var input = cell.querySelectorAll('input[type = \'text\']')[0];
				function createOrReplaceCellNum(){
					//	numElement = cell.querySelectorAll('.numElement')[0];
					if (cell.numElement){
						var textNodeValue = input.value;
						cell.numElement.firstChild.nodeValue = textNodeValue;
					}else{
						cell.numElement = document.createElement('DIV');
						cell.appendChild(cell.numElement);
						cell.numElement.className = 'numElement';
						var textNodeValue = input.value;
						cell.numElement.appendChild(document.createTextNode(textNodeValue));
					}
					addComplementaryInput(cell,'hidden','placeTypes[]',cell.numElement.firstChild.nodeValue);
				}
				// метод при нажатии на кнопку ok
				function enterSeatNumber(evt){
					evt.stopPropagation();
					createOrReplaceCellNum();
					cell.removeCellPopup();
					console.log('вводим номер');
				}
				// метод, который выполняется при нажатии в поле input
				function inputFunc(evt){
					evt.stopPropagation();
					enterButt.classList.add('visible');
				}
				var enterSeatNumWrapp = document.createElement('DIV');
				this.popupInnerBlock.appendChild(enterSeatNumWrapp);
				enterSeatNumWrapp.className = 'changeButton enterSeatNum';
				var input = document.createElement('INPUT');
				enterSeatNumWrapp.appendChild(input);
				input.type = 'text';
				input.placeholder = '№';
				var enterButt = document.createElement('DIV');
				enterSeatNumWrapp.appendChild(enterButt);
				enterButt.className = 'enterBut';
				enterButt.appendChild(document.createTextNode('Ок'));
				jQuery(enterButt).click(enterSeatNumber);
				jQuery(input).click(inputFunc);
			}

			//добавляем кнопку отмены действий в popap-е
			function addCancelButton(){
				var cell = this;
				//закрываем popup не производя никаких изменений
				function cencelPopup(evt){
					evt.stopPropagation();
					cell.removeCellPopup();
				}
				console.log('добавляем кнопку отмены');
				var cancelButton = document.createElement('DIV');
				this.popupInnerBlock.appendChild(cancelButton);
				cancelButton.className = 'changeButton cancelButton';
				jQuery(cancelButton).click(cencelPopup);
			}
			//добавляем кнопку преобразования клетки в клетку водителя
			function addChangeToDriverButton(){
				var cell = this;
				//функция удаления номера с клетки
				function removeCellNum(){
					cell.numElement.parentNode.removeChild(cell.numElement);
					cell.numElement = null;
				}
				function changeToDriver(evt){
					evt.stopPropagation(); //отменяем click клетки
					cell.className = 'cell driverCell';
					cell.removeCellPopup();
					if (cell.numElement)
						removeCellNum();
					activeCell = null;
					if (autoNumbering)
						autoAdditionOfNumElementsTotal();
					addComplementaryInput(cell,'hidden','placeTypes[]','driver');
				}
				var button = document.createElement('DIV');
				this.popupInnerBlock.appendChild(button);
				button.className = 'changeButton changeToDriver';
				button.appendChild(document.createTextNode('В'));
				jQuery(button).click(changeToDriver);
			}
			//метод добавления кнопок управления клетками в popup
			function addChangeButtonsToPopup(){
				var $cell = jQuery(this);
				if ($cell.hasClass('cell') && $cell.hasClass('seat')){
					this.addChangeToFreeCellButt();
					this.addEnterSeatNumButt();
					this.addChangeToDriverButton();
					this.addCancelButton();
				}else if (this.className == 'cell'){
					this.addChangeToSeatCellButt();
					this.addChangeToDriverButton();
					this.addCancelButton();
				}else if ($cell.hasClass('cell') && $cell.hasClass('driverCell')){
					this.addChangeToFreeCellButt();
					this.addChangeToSeatCellButt();
					this.addCancelButton();
				}
			}
			/*-- additional code --*/
			var applyDataFromDB = true;// отвечает за применение шаблона, данные по которому будут получаться из БД
			// если false, открывается стандартный шаблон для заполнения;
			if (applyDataFromDB){
				var templateData = [
					[
						['cell seat', 1],
						['cell'],
						['cell'],
						['cell seat', 6],
						['cell seat', 9],
						['cell seat', 12],
						['cell seat', 15]
					],
					[
						['cell'],
						['cell'],
						['cell'],
						['cell'],
						['cell'],
						['cell'],
						['cell seat', 16],
					],
					[
						['cell'],
						['cell seat', 2],
						['cell seat', 4],
						['cell seat', 7],
						['cell seat', 10],
						['cell seat', 13],
						['cell seat', 17],
					],
					[
						['cell driverCell'],
						['cell seat', 3],
						['cell seat', 5],
						['cell seat', 8],
						['cell seat', 11],
						['cell seat', 14],
						['cell seat', 18],
					],
				];

				rowQuantity = templateData.length;
				cellQuantity = templateData[0].length;
				//получаем номер клетки в общем массиве клеток по её координатам в двумерном массиве;
				function getCellIndexInCellsArray(i,j) {
					return (i * colNumInTempl) + j;
				}
				//изменение значения дополнительного инпута (требование стороннего разработчика);
				/*	function changeComplInputValue(cell,value){
				 function getComplInput(cell){
				 var complInput = cell.getElementsByClassName('complementaryCellInput')[0];
				 return complInput;
				 }
				 var complInput = getComplInput(cell);
				 if (complInput)
				 complInput.value = value;
				 }*/
				//удаляем дополнительный инпут;
				function removeOldCompInput(parent){
					var oldCompInput = parent.getElementsByClassName('complementaryCellInput')[0];
					if (oldCompInput)
						oldCompInput.parentNode.removeChild(oldCompInput);
				}
				//добавляем дополнительный инпут в ячейку (требование стороннего разработчика);
				function addComplementaryInput(parent,type,name,value){
					removeOldCompInput(parent);
					var input = document.createElement('INPUT');
					parent.insertBefore(input,parent.firstChild);

					input.classList.add('complementaryCellInput');

					//	input.style.display = 'none';

					input.type = type;
					input.name = name;
					input.value = value;
				}
				//изменение на свободную клетку;
				function transformToFreeCell(cellIndex,cellClassName){
					var cell = cells[cellIndex];
					cell.className = cellClassName;
					addComplementaryInput(cell,'hidden','placeTypes[]','delete');
				}
				//изменение на клетку сидения с номером;
				function transformToSeatCell(cellIndex,cellClassName,seatNum) {
					var cell = cells[cellIndex];

					cell.className = cellClassName;
					cell.numElement = document.createElement('DIV');
					cell.numElement.className = 'numElement';
					cell.appendChild(cell.numElement);
					const numTextNode = document.createTextNode(seatNum);
					cell.numElement.appendChild(numTextNode);
					addComplementaryInput(cell,'hidden','placeTypes[]', seatNum);
				}
//		function addComplementaryInput(parent,type,name,value,cellType){
				//изменение на клетку водителя;
				function transformToDriver(cellIndex,cellClassName){
					var cell = cells[cellIndex];
					cell.className = cellClassName;
					addComplementaryInput(cell,'hidden','placeTypes[]','driver');
				}
				//изменение класса автобуса;
				function changeBusClassName(rowsCount){
					switch (rowsCount){
						case 3:
							busBodey.className = 'busBodey threeRowsOfSeats';
							break;
						case 4:
							busBodey.className = 'busBodey';
							break;
						case 5:
							busBodey.className = 'busBodey fiveRowsOfSeats';
					}
				}
				// общая функция применения данных для готового шаблона;
				function applyTemplate(templateData){
					changeBusClassName(templateData.length);
					for (var i = 0; i < templateData.length; i++){
						for (var j = 0; j < templateData[i].length; j++){
							var cellIndex = getCellIndexInCellsArray(i,j);
							var cellClassName = templateData[i][j][0];
							switch (cellClassName){
								case 'cell':
									transformToFreeCell(cellIndex,cellClassName);
									break;
								case 'cell seat':
									var seatNum = templateData[i][j][1];
									transformToSeatCell(cellIndex, cellClassName, seatNum);
									break;
								case 'cell driverCell' :
									transformToDriver(cellIndex,cellClassName);
							}
						}
					}
				}
				firstAddOfSeats(rowQuantity,cellQuantity);
				var busBodey  = document.querySelectorAll('.busLayoutBlock .mainBusBlock .busBodey')[0];
				var cells = busBodey.getElementsByClassName('cell');
				var colNumInTempl = templateData[0].length;
				var rowNumInTempl = templateData.length;
				applyTemplate(templateData);
			}else{
				firstAddOfSeats(rowQuantity,cellQuantity);
			}
			/*-- secondary additional code --*/
			var autoNumbering, // включает/отключает автоматическую нумерацию сидений;
				regidNumbering, // включает/отключает жёсткую нумерацию сидений;
				reverseNumbering, // включает/отключает нумерацию рядов снизу;
				manualEnterPlaceNum; // включает/отключает возможность ручного добавления номера места сидения;
			//удаляет числовые блоки со всех клеток сидений;
			function removeNumElementsFromSeatCells(){
				var numElements = document.querySelectorAll('div.numElement');
				for (var i = 0; i < numElements.length; i++){
					numElements[i].parentNode.removeChild(numElements[i]);
				}
			}
			//проверяем целое ли число;
			function isNumberInteger(num){
				if (num%1 == 0)
					return true
				else
					return false;
			}
			//получаем номер ряда, к которому относится ячейка;
			function getNumOfRow(i,cellsCount){
				var serialNum = i + 1;
				var devisionResult = serialNum/cellQuantity;
				var rowNum;
				if (isNumberInteger(devisionResult))
					rowNum = devisionResult
				else
					rowNum = Math.floor(devisionResult + 1);
				return rowNum;
			}
			//получаем номер колонки, к которой относится ячейка;
			function getNumOfCell(i,cellsCount,numOfRow){
				var quantityOfExcessCellsForCalculation = (numOfRow - 1)*cellQuantity;
				var cellNum = i + 1 - quantityOfExcessCellsForCalculation;
				return cellNum;
			}
			//задание координат всем клеткам, информация заносится в свойство объекта клетки;
			function setCoordinatesToCells(){
				var cells = document.querySelectorAll('div.cell');
				var cellsCount = cells.length;
				for (var i = 0; i < cellsCount; i++){
					cells[i].numOfRow = getNumOfRow(i,cellsCount);
					cells[i].numOfCell = getNumOfCell(i,cellsCount,cells[i].numOfRow);
					//	console.log(cells[i].numOfRow + ', '+ cells[i].numOfCell);
				}
			}
			//атоматическое добавление номеров всем местам;
			function autoAdditionOfNumElements(){
				let seats = document.querySelectorAll('.cell');
				let seatCells = document.querySelectorAll('.cell.seat');

				if (regidNumbering) {
					setCoordinatesToCells();
				}

				const reversArr = getReversNumericArray();

				if (!regidNumbering) {
					const result = {};

					seats.forEach((value, index) => {
						const ceil = Math.ceil((index + 1) / cellQuantity);

						if (!result[ceil]) {
							result[ceil] = [];
						}

						result[ceil].push(value);

						return result;
					});

					seats = [];
					const start = reverseNumbering ? rowQuantity : 1;
					const end = reverseNumbering ? 1 : rowQuantity;
					for (let i = 0; i < cellQuantity; i++) {
						for (let j = start; start > end ? (j >= end) : (j <= end); start > end ? j-- : j++) {
							seats.push(result[j][i]);
						}
					}

					seatCells = seats.filter((item) => item.className.indexOf('seat') !== -1 );
				}

				for (let i = 0; i < seatCells.length; i++){
					var numElement = document.createElement('DIV');
					numElement.classList.add('numElement');
					seatCells[i].appendChild(numElement);
					seatCells[i].numElement = numElement;

					let numNode = document.createTextNode(i + 1);

					if (regidNumbering) {
						if (reverseNumbering) {
							numNode = document.createTextNode(reversArr[seatCells[i].numOfRow - 1] + String(seatCells[i].numOfCell - 1));
						} else {
							numNode = document.createTextNode(lettersArray[seatCells[i].numOfRow - 1] + String(seatCells[i].numOfCell - 1));
						}
					}

					numElement.appendChild(numNode);
					//	changeComplInputValue(seatCells[i],numNode.nodeValue);
					//	addComplementaryInput(parent,type,name,value);
					//	addComplementaryInput(cell,'hidden','placeTypes',seatNum);
					addComplementaryInput(seatCells[i],'hidden','placeTypes[]',numNode.nodeValue);
				}
			}
			//атоматическое добавление номеров всем местам - общая функция;
			function autoAdditionOfNumElementsTotal(){
				removeNumElementsFromSeatCells();
				autoAdditionOfNumElements();
				//	console.log(rowQuantity + ', ' + cellQuantity);
			}
			//определение изначального положения переключателей панели настроек. общая функция для всех переключателей;
//	getStartSwitchValue(manEntPlNumYesButt,manEntPlNumNoButt,'manualEnterPlaceNum');
			function getStartSwitchValue(yesButton,noButton,paramForChange){
				if (yesButton.classList.contains('active')){
					if (paramForChange == 'autoNumbering')
						autoNumbering = true
					else if (paramForChange == 'regidNumbering')
						regidNumbering = true
					else if (paramForChange == 'reverseNumbering')
						reverseNumbering = true
					else if (paramForChange == 'manualEnterPlaceNum')
						manualEnterPlaceNum = true;
				}
				else if(noButton.classList.contains('active')){
					if (paramForChange == 'autoNumbering')
						autoNumbering = false
					else if (paramForChange == 'regidNumbering')
						regidNumbering = false
					else if (paramForChange == 'reverseNumbering')
						reverseNumbering = true
					else if (paramForChange == 'manualEnterPlaceNum')
						manualEnterPlaceNum = false;
				}
			}
			//изменение положения переключателя автоматической нумерации при клике;
			function changeAutoNumberingValue(){
				if (!this.classList.contains('active')){
					if (this.classList.contains('yes')){
						autoNumbering = true;
						this.classList.add('active');
						autoNumNoButton.classList.remove('active');
						autoAdditionOfNumElementsTotal();
						// showOptionBlock(regidNumberingPanel);
						showOptionBlock(reverseLetterNubmeringPanel);
					}else if(this.classList.contains('no')){
						this.classList.add('active');
						autoNumbering = false;
						autoNumYesButton.classList.remove('active');
						// changeRegidNumberingValue.call(regidNumNoButton);
						// hideOptionBlock(regidNumberingPanel);
						hideOptionBlock(reverseLetterNubmeringPanel);
					}
				}
			}
			//изменение положения переключателя жёсткой нумерации при клике;
			function changeRegidNumberingValue(){
				if (!this.classList.contains('active')){
					if (this.classList.contains('yes')){
						regidNumbering = true;
						this.classList.add('active');
						regidNumNoButton.classList.remove('active');
						autoAdditionOfNumElementsTotal();
					}else if(this.classList.contains('no')){
						this.classList.add('active');
						regidNumbering = false;
						regidNumYesButton.classList.remove('active');
						autoAdditionOfNumElementsTotal();
					}
				}
			}
			//изменение буквенной нумерации по клику (сверху или снизу);
			function changeReverseNumbering(){
				if (!this.classList.contains('active')){
					if (this.classList.contains('yes')){
						reverseNumbering = true;
						this.classList.add('active');
						reverseLettNumNoButt.classList.remove('active');
						autoAdditionOfNumElementsTotal();
					}else if(this.classList.contains('no')){
						this.classList.add('active');
						reverseNumbering = false;
						reverseLettNumYesButt.classList.remove('active');
						autoAdditionOfNumElementsTotal();
					}
				}
			}
			//Добавление или удаление возможности редактировать номера мест;
			function changeManualEnterPlaceNum(callMethod){
				if (!this.classList.contains('active') || callMethod){
					if (this.classList.contains('yes')){
						manualEnterPlaceNum = true;
						this.classList.add('active');
						manEntPlNumNoButt.classList.remove('active');
						showEnterNumberBlocks();
					}else if(this.classList.contains('no')){
						this.classList.add('active');
						manualEnterPlaceNum = false;
						manEntPlNumYesButt.classList.remove('active');
						hideEnterNumberBlocks();
					}
				}
			}
			//Добавление или удаление возможности редактировать номера мест при загрузке страницы;
			function changeManualEnterPlaceNumOnLoad(){
				if (manEntPlNumYesButt.classList.contains('active'))
					changeManualEnterPlaceNum.call(manEntPlNumYesButt,'callMethod')
				else if (manEntPlNumNoButt.classList.contains('active'))
					changeManualEnterPlaceNum.call(manEntPlNumNoButt,'callMethod')
			}
			//прячем блоки, содержащие инпуты добавления номеров мест;
			function hideEnterNumberBlocks(){
				seatsBlock.classList.add('hiddenNumericBlocks');
			}
			//показываем блоки, содержащие инпуты добавления номеров мест;
			function showEnterNumberBlocks(){
				seatsBlock.classList.remove('hiddenNumericBlocks');
			}
			//прячем переданный в параметр;
			function hideOptionBlock(optionBlock){
				optionBlock.style.display = 'none';
			}
			// показываем переданный в параметр;
			function showOptionBlock(optionBlock){
				optionBlock.style.display = '';
			}
			//получаем реверсивный массив букв;
			function getReversNumericArray(){
				var intermadiateArr = [],
					reversNumericArr = [];
				for (var i = 0; i < rowQuantity; i++){
					intermadiateArr.push(lettersArray[i]);
				}
				for (var j = (intermadiateArr.length - 1); j >= 0; j--){
					reversNumericArr.push(intermadiateArr[j]);
				}
				return reversNumericArr;
			}
			var lettersArray = ['A','B','C','D','E','F','G','H','I','J','K']; // массив букв рядов;
			// var regidNumberingPanel = document.querySelectorAll('.templateOptions .regidNumbering')[0];
			var reverseLetterNubmeringPanel = document.querySelectorAll('.templateOptions .reverseLetterNubmering')[0];
			var autoNumYesButton = document.querySelectorAll('.autoNumbering .yes')[0];
			var autoNumNoButton = document.querySelectorAll('.autoNumbering .no')[0];
			// var regidNumYesButton = document.querySelectorAll('.regidNumbering .yes')[0];
			// var regidNumNoButton = document.querySelectorAll('.regidNumbering .no')[0];
			var reverseLettNumYesButt = document.querySelectorAll('.reverseLetterNubmering .yes')[0];
			var reverseLettNumNoButt = document.querySelectorAll('.reverseLetterNubmering .no')[0];
			var manEntPlNumYesButt = document.querySelectorAll('.manualEnterPlaceNum .yes')[0];
			var manEntPlNumNoButt = document.querySelectorAll('.manualEnterPlaceNum .no')[0];
			autoNumYesButton.onclick = changeAutoNumberingValue;
			autoNumNoButton.onclick = changeAutoNumberingValue;
			// regidNumYesButton.onclick = changeRegidNumberingValue;
			// regidNumNoButton.onclick = changeRegidNumberingValue;
			reverseLettNumYesButt.onclick = changeReverseNumbering;
			reverseLettNumNoButt.onclick = changeReverseNumbering;
			manEntPlNumYesButt.onclick = changeManualEnterPlaceNum;
			manEntPlNumNoButt.onclick = changeManualEnterPlaceNum;
			console.log(autoNumYesButton);
			console.log(autoNumNoButton);
			/*	getStartAutoNumberingValue(autoNumYesButton,autoNumNoButton);
			 getStartRegidNumberingValue(regidNumYesButton,regidNumNoButton);*/
			getStartSwitchValue(autoNumYesButton,autoNumNoButton,'autoNumbering');
			// getStartSwitchValue(regidNumYesButton,regidNumNoButton,'regidNumbering');
			// getStartSwitchValue(reverseLettNumYesButt,reverseLettNumNoButt,'reverseNumbering');
			getStartSwitchValue(manEntPlNumYesButt,manEntPlNumNoButt,'manualEnterPlaceNum');
			changeManualEnterPlaceNumOnLoad();
			/*
			 1 1 1 1
			 2 2 2 2
			 3 3 3 3
			 4 4 4 4
			 */
			var manEntPlNumYesButt = document.querySelectorAll('.manualEnterPlaceNum .yes')[0];
			var manEntPlNumNoButt = document.querySelectorAll('.manualEnterPlaceNum .no')[0];
		}
	}

	initTemplateBus();
	window.initTemplateBus = initTemplateBus;
});/*
 // changeReverseNumbering
 regidNumbering = true, // включает/отключает жёсткую нумерацию сидений;
 reverseNumbering = true;*/












