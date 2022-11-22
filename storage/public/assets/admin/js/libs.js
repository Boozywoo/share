!function(e){function t(t,a,o){var r=this;return this.on("click.pjax",t,function(t){var l=e.extend({},h(a,o));l.container||(l.container=e(this).attr("data-pjax")||r),n(t,l)})}function n(t,n,a){a=h(n,a);var r=t.currentTarget;if("A"!==r.tagName.toUpperCase())throw"$.fn.pjax or $.pjax.click requires an anchor element";if(!(t.which>1||t.metaKey||t.ctrlKey||t.shiftKey||t.altKey||location.protocol!==r.protocol||location.hostname!==r.hostname||r.href.indexOf("#")>-1&&f(r)==f(location)||t.isDefaultPrevented())){var l={url:r.href,container:e(r).attr("data-pjax"),target:r},i=e.extend({},l,a),c=e.Event("pjax:click");e(r).trigger(c,[i]),c.isDefaultPrevented()||(o(i),t.preventDefault(),e(r).trigger("pjax:clicked",[i]))}}function a(t,n,a){a=h(n,a);var r=t.currentTarget;if("FORM"!==r.tagName.toUpperCase())throw"$.pjax.submit requires a form element";var l={type:r.method.toUpperCase(),url:r.action,container:e(r).attr("data-pjax"),target:r};if("GET"!==l.type&&void 0!==window.FormData)l.data=new FormData(r),l.processData=!1,l.contentType=!1;else{if(e(r).find(":file").length)return;l.data=e(r).serializeArray()}o(e.extend({},l,a)),t.preventDefault()}function o(t){function n(t,n,o){o||(o={}),o.relatedTarget=a;var r=e.Event(t,o);return i.trigger(r,n),!r.isDefaultPrevented()}t=e.extend(!0,{},e.ajaxSettings,o.defaults,t),e.isFunction(t.url)&&(t.url=t.url());var a=t.target,r=m(t.url).hash,i=t.context=v(t.container);t.data||(t.data={}),e.isArray(t.data)?t.data.push({name:"_pjax",value:i.selector}):t.data._pjax=i.selector;var c;t.beforeSend=function(e,a){if("GET"!==a.type&&(a.timeout=0),e.setRequestHeader("X-PJAX","true"),e.setRequestHeader("X-PJAX-Container",i.selector),!n("pjax:beforeSend",[e,a]))return!1;a.timeout>0&&(c=setTimeout(function(){n("pjax:timeout",[e,t])&&e.abort("timeout")},a.timeout),a.timeout=0);var o=m(a.url);r&&(o.hash=r),t.requestUrl=p(o)},t.complete=function(e,a){c&&clearTimeout(c),n("pjax:complete",[e,a,t]),n("pjax:end",[e,t])},t.error=function(e,a,o){var r=C("",e,t),i=n("pjax:error",[e,a,o,t]);"GET"==t.type&&"abort"!==a&&i&&l(r.url)},t.success=function(a,c,s){var d=o.state,p="function"==typeof e.pjax.defaults.version?e.pjax.defaults.version():e.pjax.defaults.version,f=s.getResponseHeader("X-PJAX-Version"),h=C(a,s,t),v=m(h.url);if(r&&(v.hash=r,h.url=v.href),p&&f&&p!==f)return void l(h.url);if(!h.contents)return void l(h.url);o.state={id:t.id||u(),url:h.url,title:h.title,container:i.selector,fragment:t.fragment,timeout:t.timeout},(t.push||t.replace)&&window.history.replaceState(o.state,h.title,h.url);try{document.activeElement.blur()}catch(g){}h.title&&(document.title=h.title),n("pjax:beforeReplace",[h.contents,t],{state:o.state,previousState:d}),i.html(h.contents);var y=i.find("input[autofocus], textarea[autofocus]").last()[0];y&&document.activeElement!==y&&y.focus(),x(h.scripts);var w=t.scrollTo;if(r){var N=decodeURIComponent(r.slice(1)),b=document.getElementById(N)||document.getElementsByName(N)[0];b&&(w=e(b).offset().top)}"number"==typeof w&&e(window).scrollTop(w),n("pjax:success",[a,c,s,t])},o.state||(o.state={id:u(),url:window.location.href,title:document.title,container:i.selector,fragment:t.fragment,timeout:t.timeout},window.history.replaceState(o.state,document.title)),s(o.xhr),o.options=t;var f=o.xhr=e.ajax(t);return f.readyState>0&&(t.push&&!t.replace&&(w(o.state.id,d(i)),window.history.pushState(null,"",t.requestUrl)),n("pjax:start",[f,t]),n("pjax:send",[f,t])),o.xhr}function r(t,n){var a={url:window.location.href,push:!1,replace:!0,scrollTo:!1};return o(e.extend(a,h(t,n)))}function l(e){window.history.replaceState(null,"",o.state.url),window.location.replace(e)}function i(t){T||s(o.xhr);var n,a=o.state,r=t.state;if(r&&r.container){if(T&&B==r.url)return;if(a){if(a.id===r.id)return;n=a.id<r.id?"forward":"back"}var i=k[r.id]||[],c=e(i[0]||r.container),u=i[1];if(c.length){a&&N(n,a.id,d(c));var p=e.Event("pjax:popstate",{state:r,direction:n});c.trigger(p);var m={id:r.id,url:r.url,container:c,push:!1,fragment:r.fragment,timeout:r.timeout,scrollTo:!1};if(u){c.trigger("pjax:start",[null,m]),o.state=r,r.title&&(document.title=r.title);var f=e.Event("pjax:beforeReplace",{state:r,previousState:a});c.trigger(f,[u,m]),c.html(u),c.trigger("pjax:end",[null,m])}else o(m);c[0].offsetHeight}else l(location.href)}T=!1}function c(t){var n=e.isFunction(t.url)?t.url():t.url,a=t.type?t.type.toUpperCase():"GET",o=e("<form>",{method:"GET"===a?"GET":"POST",action:n,style:"display:none"});"GET"!==a&&"POST"!==a&&o.append(e("<input>",{type:"hidden",name:"_method",value:a.toLowerCase()}));var r=t.data;if("string"==typeof r)e.each(r.split("&"),function(t,n){var a=n.split("=");o.append(e("<input>",{type:"hidden",name:a[0],value:a[1]}))});else if(e.isArray(r))e.each(r,function(t,n){o.append(e("<input>",{type:"hidden",name:n.name,value:n.value}))});else if("object"==typeof r){var l;for(l in r)o.append(e("<input>",{type:"hidden",name:l,value:r[l]}))}e(document.body).append(o),o.submit()}function s(t){t&&t.readyState<4&&(t.onreadystatechange=e.noop,t.abort())}function u(){return(new Date).getTime()}function d(e){var t=e.clone();return t.find("script").each(function(){this.src||jQuery._data(this,"globalEval",!1)}),[e.selector,t.contents()]}function p(e){return e.search=e.search.replace(/([?&])(_pjax|_)=[^&]*/g,""),e.href.replace(/\?($|#)/,"$1")}function m(e){var t=document.createElement("a");return t.href=e,t}function f(e){return e.href.replace(/#.*/,"")}function h(t,n){return t&&n?n.container=t:n=e.isPlainObject(t)?t:{container:t},n.container&&(n.container=v(n.container)),n}function v(t){if(t=e(t),t.length){if(""!==t.selector&&t.context===document)return t;if(t.attr("id"))return e("#"+t.attr("id"));throw"cant get selector for pjax container!"}throw"no pjax container for "+t.selector}function g(e,t){return e.filter(t).add(e.find(t))}function y(t){return e.parseHTML(t,document,!0)}function C(t,n,a){var o={},r=/<html/i.test(t),l=n.getResponseHeader("X-PJAX-URL");if(o.url=l?p(m(l)):a.requestUrl,r)var i=e(y(t.match(/<head[^>]*>([\s\S.]*)<\/head>/i)[0])),c=e(y(t.match(/<body[^>]*>([\s\S.]*)<\/body>/i)[0]));else var i=c=e(y(t));if(0===c.length)return o;if(o.title=g(i,"title").last().text(),a.fragment){if("body"===a.fragment)var s=c;else var s=g(c,a.fragment).first();s.length&&(o.contents="body"===a.fragment?s:s.contents(),o.title||(o.title=s.attr("title")||s.data("title")))}else r||(o.contents=c);return o.contents&&(o.contents=o.contents.not(function(){return e(this).is("title")}),o.contents.find("title").remove(),o.scripts=g(o.contents,"script[src]").remove(),o.contents=o.contents.not(o.scripts)),o.title&&(o.title=e.trim(o.title)),o}function x(t){if(t){var n=e("script[src]");t.each(function(){var t=this.src,a=n.filter(function(){return this.src===t});if(!a.length){var o=document.createElement("script"),r=e(this).attr("type");r&&(o.type=r),o.src=e(this).attr("src"),document.head.appendChild(o)}})}}function w(e,t){k[e]=t,A.push(e),b(P,0),b(A,o.defaults.maxCacheLength)}function N(e,t,n){var a,r;k[t]=n,"forward"===e?(a=A,r=P):(a=P,r=A),a.push(t),(t=r.pop())&&delete k[t],b(a,o.defaults.maxCacheLength)}function b(e,t){for(;e.length>t;)delete k[e.shift()]}function E(){return e("meta").filter(function(){var t=e(this).attr("http-equiv");return t&&"X-PJAX-VERSION"===t.toUpperCase()}).attr("content")}function j(){e.fn.pjax=t,e.pjax=o,e.pjax.enable=e.noop,e.pjax.disable=S,e.pjax.click=n,e.pjax.submit=a,e.pjax.reload=r,e.pjax.defaults={timeout:650,push:!0,replace:!1,type:"GET",dataType:"html",scrollTo:0,maxCacheLength:20,version:E},e(window).on("popstate.pjax",i)}function S(){e.fn.pjax=function(){return this},e.pjax=c,e.pjax.enable=j,e.pjax.disable=e.noop,e.pjax.click=e.noop,e.pjax.submit=e.noop,e.pjax.reload=function(){window.location.reload()},e(window).off("popstate.pjax",i)}var T=!0,B=window.location.href,L=window.history.state;L&&L.container&&(o.state=L),"state"in window.history&&(T=!1);var k={},P=[],A=[];e.inArray("state",e.event.props)<0&&e.event.props.push("state"),e.support.pjax=window.history&&window.history.pushState&&window.history.replaceState&&!navigator.userAgent.match(/((iPod|iPhone|iPad).+\bOS\s+[1-4]\D|WebApps\/.+CFNetwork)/),e.support.pjax?j():S()}(jQuery),function(e){"use strict";"function"==typeof define&&define.amd?define(["jquery"],e):"undefined"!=typeof module&&module.exports?module.exports=e(require("jquery")):e(jQuery)}(function(e){"use strict";function t(t){return!t.nodeName||-1!==e.inArray(t.nodeName.toLowerCase(),["iframe","#document","html","body"])}function n(t){return e.isFunction(t)||e.isPlainObject(t)?t:{top:t,left:t}}var a=e.scrollTo=function(t,n,a){return e(window).scrollTo(t,n,a)};return a.defaults={axis:"xy",duration:0,limit:!0},e.fn.scrollTo=function(o,r,l){"object"==typeof r&&(l=r,r=0),"function"==typeof l&&(l={onAfter:l}),"max"===o&&(o=9e9),l=e.extend({},a.defaults,l),r=r||l.duration;var i=l.queue&&1<l.axis.length;return i&&(r/=2),l.offset=n(l.offset),l.over=n(l.over),this.each(function(){function c(t){var n=e.extend({},l,{queue:!0,duration:r,complete:t&&function(){t.call(d,m,l)}});p.animate(f,n)}if(null!==o){var s,u=t(this),d=u?this.contentWindow||window:this,p=e(d),m=o,f={};switch(typeof m){case"number":case"string":if(/^([+-]=?)?\d+(\.\d+)?(px|%)?$/.test(m)){m=n(m);break}m=u?e(m):e(m,d);case"object":if(0===m.length)return;(m.is||m.style)&&(s=(m=e(m)).offset())}var h=e.isFunction(l.offset)&&l.offset(d,m)||l.offset;e.each(l.axis.split(""),function(e,t){var n="x"===t?"Left":"Top",o=n.toLowerCase(),r="scroll"+n,v=p[r](),g=a.max(d,t);s?(f[r]=s[o]+(u?0:v-p.offset()[o]),l.margin&&(f[r]-=parseInt(m.css("margin"+n),10)||0,f[r]-=parseInt(m.css("border"+n+"Width"),10)||0),f[r]+=h[o]||0,l.over[o]&&(f[r]+=m["x"===t?"width":"height"]()*l.over[o])):(n=m[o],f[r]=n.slice&&"%"===n.slice(-1)?parseFloat(n)/100*g:n),l.limit&&/^\d+$/.test(f[r])&&(f[r]=0>=f[r]?0:Math.min(f[r],g)),!e&&1<l.axis.length&&(v===f[r]?f={}:i&&(c(l.onAfterFirst),f={}))}),c(l.onAfter)}})},a.max=function(n,a){var o="x"===a?"Width":"Height",r="scroll"+o;if(!t(n))return n[r]-e(n)[o.toLowerCase()]();var o="client"+o,l=n.ownerDocument||n.document,i=l.documentElement,l=l.body;return Math.max(i[r],l[r])-Math.min(i[o],l[o])},e.Tween.propHooks.scrollLeft=e.Tween.propHooks.scrollTop={get:function(t){return e(t.elem)[t.prop]()},set:function(t){var n=this.get(t);if(t.options.interrupt&&t._last&&t._last!==n)return e(t.elem).stop();var a=Math.round(t.now);n!==a&&(e(t.elem)[t.prop](a),t._last=this.get(t))}},a}),jQuery(document).ready(function(){function e(){function e(e){for(var t=document.querySelectorAll(".busLayoutBlock .mainBusBlock .busBodey")[0],n=0;n<e.length;n++)t.classList.remove(e[n])}function t(e,t){e.firstChild.nodeValue=t}function n(e,t){function n(){var t,n=e.parentNode.querySelectorAll("input.complementaryInput")[0];if(n)t=n;else{var a=document.createElement("INPUT");a.classList.add("complementaryInput"),a.name="ranks",a.type="hidden";var o=e.parentNode.querySelectorAll("ul.buttons")[0];e.parentNode.insertBefore(a,o),t=a}return t}var a=n();a.value=t,console.log("инпут: "+a),console.log("значение: "+a.value)}function a(e){for(var t=[],n=e.firstChild;n;)"DIV"==n.tagName&&t.push(n),n=n.nextSibling;return t}function o(){for(var e=a(re),t=[],n=0;n<e.length;n++)(n+1)%Y==0&&t.push(e[n]);return t}function r(e,t){var n=document.createElement("DIV");return re.appendChild(n),n.className=e,n.numOfRow=t,g(f,"onclick",n),g(v,"removeCellPopup",n),g(h,"addCellPopup",n),g(b,"addChangeButtonsToPopup",n),g(y,"addChangeToFreeCellButt",n),g(x,"addEnterSeatNumButt",n),g(C,"addChangeToSeatCellButt",n),g(w,"addCancelButton",n),g(N,"addChangeToDriverButton",n),n}function l(e,t){var n=document.createElement("DIV");e.parentNode.insertBefore(n,e.nextSibling),n.className="cell seat",n.numOfRow=t,g(f,"onclick",n),g(v,"removeCellPopup",n),g(h,"addCellPopup",n),g(b,"addChangeButtonsToPopup",n),g(y,"addChangeToFreeCellButt",n),g(x,"addEnterSeatNumButt",n),g(C,"addChangeToSeatCellButt",n),g(w,"addCancelButton",n),g(N,"addChangeToDriverButton",n)}function i(e,t){for(var n=0;n<e;n++){var a=r("cell seat",t);0==n&&(a.style.clear="both")}}function c(e){for(var t=[],n=e.lastChild;n&&"both"!=n.style.clear;)"DIV"==n.tagName&&t.push(n),n=n.previousSibling,"both"==n.style.clear&&t.push(n);return t}function s(){for(var e=c(re),t=0;t<e.length;t++)e[t].parentNode.removeChild(e[t])}function u(){for(var e=o(),t=0;t<e.length;t++)l(e[t],t+1)}function d(){for(var e=o(),t=0;t<e.length;t++)e[t].parentNode.removeChild(e[t])}function p(t){var n=document.querySelectorAll(".busLayoutBlock .mainBusBlock .busBodey")[0];if(n)switch(e(ue),t){case 3:n.classList.add("threeRowsOfSeats");break;case 5:n.classList.add("fiveRowsOfSeats")}}function m(e,t){p(e);for(var n=0;n<e;n++)i(t,n+1)}function f(){de!=this?(de&&de.popup&&de.removeCellPopup(),this.addCellPopup(),this.addChangeButtonsToPopup(),de=this):(this.removeCellPopup(),de=null)}function h(){var e=document.createElement("DIV");this.appendChild(e),e.classList.add("cellPopupPositionBlock");var t=document.createElement("DIV");e.appendChild(t),t.classList.add("cellPopup"),this.popup=e,this.popupInnerBlock=this.popup.querySelectorAll(".cellPopup")[0]}function v(){this.removeChild(this.popup),this.popup=null,de=null}function g(e,t,n){n[t]=e}function y(){function e(){n.numElement.parentNode.removeChild(n.numElement),n.numElement=null}function t(t){t.stopPropagation(),n.className="cell",n.removeCellPopup(),n.numElement&&e(),de=null,ge&&V(),S(n,"hidden","placeTypes[]","delete")}var n=this,a=document.createElement("DIV");this.popupInnerBlock.appendChild(a),a.className="changeButton changeToFreeCell",jQuery(a).click(t)}function C(){function e(e){e.stopPropagation(),t.className="cell seat",t.removeCellPopup(),de=null,ge?(V(),S(t,"hidden","placeTypes[]",t.numElement.firstChild.nodeValue)):j(t)}var t=this,n=document.createElement("DIV");this.popupInnerBlock.appendChild(n),n.className="changeButton changeToSeat",jQuery(n).click(e)}function x(){function e(){if(a.numElement){var e=o.value;a.numElement.firstChild.nodeValue=e}else{a.numElement=document.createElement("DIV"),a.appendChild(a.numElement),a.numElement.className="numElement";var e=o.value;a.numElement.appendChild(document.createTextNode(e))}S(a,"hidden","placeTypes[]",a.numElement.firstChild.nodeValue)}function t(t){t.stopPropagation(),e(),a.removeCellPopup(),console.log("вводим номер")}function n(e){e.stopPropagation(),this.placeholder="",l.classList.add("visible")}var a=this,o=a.querySelectorAll("input[type = 'text']")[0],r=document.createElement("DIV");this.popupInnerBlock.appendChild(r),r.className="changeButton enterSeatNum";var o=document.createElement("INPUT");r.appendChild(o),o.type="text",o.placeholder="Н";var l=document.createElement("DIV");r.appendChild(l),l.className="enterBut",l.appendChild(document.createTextNode("Ок")),jQuery(l).click(t),jQuery(o).click(n)}function w(){function e(e){e.stopPropagation(),t.removeCellPopup()}var t=this;console.log("добавляем кнопку отмены");var n=document.createElement("DIV");this.popupInnerBlock.appendChild(n),n.className="changeButton cancelButton",jQuery(n).click(e)}function N(){function e(){n.numElement.parentNode.removeChild(n.numElement),n.numElement=null}function t(t){t.stopPropagation(),n.className="cell driverCell",n.removeCellPopup(),n.numElement&&e(),de=null,ge&&V(),S(n,"hidden","placeTypes[]","driver")}var n=this,a=document.createElement("DIV");this.popupInnerBlock.appendChild(a),a.className="changeButton changeToDriver",a.appendChild(document.createTextNode("В")),jQuery(a).click(t)}function b(){var e=jQuery(this);e.hasClass("cell")&&e.hasClass("seat")?(this.addChangeToFreeCellButt(),this.addEnterSeatNumButt(),this.addChangeToDriverButton(),this.addCancelButton()):"cell"==this.className?(this.addChangeToSeatCellButt(),this.addChangeToDriverButton(),this.addCancelButton()):e.hasClass("cell")&&e.hasClass("driverCell")&&(this.addChangeToFreeCellButt(),this.addChangeToSeatCellButt(),this.addCancelButton())}function E(e,t){return e*ve+t}function j(e){var t=e.getElementsByClassName("complementaryCellInput")[0];t&&t.parentNode.removeChild(t)}function S(e,t,n,a){j(e);var o=document.createElement("INPUT");e.insertBefore(o,e.firstChild),o.classList.add("complementaryCellInput"),o.type=t,o.name=n,o.value=a}function T(e,t){var n=he[e];n.className=t,S(n,"hidden","placeTypes[]","delete")}function B(e,t,n){var a=he[e];a.className=t,a.numElement=document.createElement("DIV"),a.numElement.className="numElement",a.appendChild(a.numElement);var o=document.createTextNode(n);a.numElement.appendChild(o),S(a,"hidden","placeTypes[]",n)}function L(e,t){var n=he[e];n.className=t,S(n,"hidden","placeTypes[]","driver")}function k(e){switch(e){case 3:fe.className="busBodey threeRowsOfSeats";break;case 4:fe.className="busBodey";break;case 5:fe.className="busBodey fiveRowsOfSeats"}}function P(e){k(e.length);for(var t=0;t<e.length;t++)for(var n=0;n<e[t].length;n++){var a=E(t,n),o=e[t][n][0];switch(o){case"cell":T(a,o);break;case"cell seat":var r=e[t][n][1];B(a,o,r);break;case"cell driverCell":L(a,o)}}}function A(){for(var e=document.querySelectorAll("div.numElement"),t=0;t<e.length;t++)e[t].parentNode.removeChild(e[t])}function q(e){return e%1==0}function I(e,t){var n,a=e+1,o=a/Y;return n=q(o)?o:Math.floor(o+1)}function D(e,t,n){var a=(n-1)*Y,o=e+1-a;return o}function R(){for(var e=document.querySelectorAll("div.cell"),t=e.length,n=0;n<t;n++)e[n].numOfRow=I(n,t),e[n].numOfCell=D(n,t,e[n].numOfRow)}function O(){var e=document.querySelectorAll(".cell.seat");ye&&R();for(var t=K(),n=0;n<e.length;n++){var a=document.createElement("DIV");if(a.classList.add("numElement"),e[n].appendChild(a),e[n].numElement=a,ye)if(Ce)var o=document.createTextNode(t[e[n].numOfRow-1]+String(e[n].numOfCell-1));else var o=document.createTextNode(we[e[n].numOfRow-1]+String(e[n].numOfCell-1));else var o=document.createTextNode(n+1);a.appendChild(o),S(e[n],"hidden","placeTypes[]",o.nodeValue)}}function V(){A(),O()}function F(e,t,n){e.classList.contains("active")?"autoNumbering"==n?ge=!0:"regidNumbering"==n?ye=!0:"reverseNumbering"==n?Ce=!0:"manualEnterPlaceNum"==n&&(xe=!0):t.classList.contains("active")&&("autoNumbering"==n?ge=!1:"regidNumbering"==n?ye=!1:"reverseNumbering"==n?Ce=!0:"manualEnterPlaceNum"==n&&(xe=!1))}function Q(){this.classList.contains("active")||(this.classList.contains("yes")?(ge=!0,this.classList.add("active"),je.classList.remove("active"),V(),J(Ne)):this.classList.contains("no")&&(this.classList.add("active"),ge=!1,Ee.classList.remove("active"),U.call(Te),_(Ne)))}function U(){this.classList.contains("active")||(this.classList.contains("yes")?(ye=!0,this.classList.add("active"),Te.classList.remove("active"),V(),J(be)):this.classList.contains("no")&&(this.classList.add("active"),ye=!1,Se.classList.remove("active"),V(),H.call(Le),_(be)))}function H(){this.classList.contains("active")||(this.classList.contains("yes")?(Ce=!0,this.classList.add("active"),Le.classList.remove("active"),V()):this.classList.contains("no")&&(this.classList.add("active"),Ce=!1,Be.classList.remove("active"),V()))}function W(e){this.classList.contains("active")&&!e||(this.classList.contains("yes")?(xe=!0,this.classList.add("active"),Pe.classList.remove("active"),M()):this.classList.contains("no")&&(this.classList.add("active"),xe=!1,ke.classList.remove("active"),G()))}function X(){ke.classList.contains("active")?W.call(ke,"callMethod"):Pe.classList.contains("active")&&W.call(Pe,"callMethod")}function G(){re.classList.add("hiddenNumericBlocks")}function M(){re.classList.remove("hiddenNumericBlocks")}function _(e){e.style.display="none"}function J(e){e.style.display=""}function K(){for(var e=[],t=[],n=0;n<z;n++)e.push(we[n]);for(var a=e.length-1;a>=0;a--)t.push(e[a]);return t}if($(".filterElements").length){var z=4,Y=7,Z=3,ee=5,te=3,ne=20,ae=document.querySelectorAll(".addRowColumnWrapp .rowQuantity")[0],oe=document.querySelectorAll(".addRowColumnWrapp .cellQuantity")[0],re=document.querySelectorAll(".busLayoutBlock .mainBusBlock .seats")[0],le=document.querySelectorAll(".addRowColumnWrapp.rowOp .addRow")[0],ie=document.querySelectorAll(".addRowColumnWrapp.rowOp .removeRow")[0],ce=document.querySelectorAll(".addRowColumnWrapp.columnOp .addColumn")[0],se=document.querySelectorAll(".addRowColumnWrapp.columnOp .removeColumn")[0],ue=["threeRowsOfSeats","fiveRowsOfSeats"];le.onclick=function(){z<ee&&(i(Y,z),z++,t(ae,z),n(ae,z),p(z),ge&&V())},ie.onclick=function(){z>Z&&(s(),z--,t(ae,z),n(ae,z),p(z),ge&&V())},ce.onclick=function(){Y<ne&&(u(),Y++,t(oe,Y),n(oe,Y),p(z),ge&&V())},se.onclick=function(){Y>te&&(d(),Y--,t(oe,Y),n(oe,Y),ge&&V())};var de,pe=!0;if(pe){var me=[[["cell"],["cell"],["cell seat",1],["cell seat",2],["cell seat",3],["cell seat",4],["cell seat",5]],[["cell"],["cell"],["cell"],["cell"],["cell"],["cell"],["cell seat",6]],[["cell driverCell"],["cell seat",7],["cell seat",8],["cell seat",9],["cell seat",10],["cell seat",11],["cell seat",12]],[["cell driverCell"],["cell seat",13],["cell seat",14],["cell seat",15],["cell seat",16],["cell seat",17],["cell seat",18]]];z=me.length,Y=me[0].length,m(z,Y);var fe=document.querySelectorAll(".busLayoutBlock .mainBusBlock .busBodey")[0],he=fe.getElementsByClassName("cell"),ve=me[0].length;me.length;P(me)}else m(z,Y);var ge,ye,Ce,xe,we=["A","B","C","D","E","F","G","H","I","J","K"],Ne=document.querySelectorAll(".templateOptions .regidNumbering")[0],be=document.querySelectorAll(".templateOptions .reverseLetterNubmering")[0],Ee=document.querySelectorAll(".autoNumbering .yes")[0],je=document.querySelectorAll(".autoNumbering .no")[0],Se=document.querySelectorAll(".regidNumbering .yes")[0],Te=document.querySelectorAll(".regidNumbering .no")[0],Be=document.querySelectorAll(".reverseLetterNubmering .yes")[0],Le=document.querySelectorAll(".reverseLetterNubmering .no")[0],ke=document.querySelectorAll(".manualEnterPlaceNum .yes")[0],Pe=document.querySelectorAll(".manualEnterPlaceNum .no")[0];Ee.onclick=Q,je.onclick=Q,Se.onclick=U,Te.onclick=U,Be.onclick=H,Le.onclick=H,ke.onclick=W,Pe.onclick=W,console.log(Ee),console.log(je),F(Ee,je,"autoNumbering"),F(Se,Te,"regidNumbering"),F(Be,Le,"reverseNumbering"),F(ke,Pe,"manualEnterPlaceNum"),ge&&V(),X();var ke=document.querySelectorAll(".manualEnterPlaceNum .yes")[0],Pe=document.querySelectorAll(".manualEnterPlaceNum .no")[0]}}e(),window.initTemplateBus=e});