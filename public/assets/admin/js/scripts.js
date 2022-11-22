/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/assets/admin/js/app/bus.js":
/*!**********************************************!*\
  !*** ./resources/assets/admin/js/app/bus.js ***!
  \**********************************************/
/***/ (() => {

$(document).ready(function () {
  // ��� ��� ��� ����, ����� ���� "�����" � "������ ���������
  // ������������" ������������ �� ajax, ������ ���� ������������
  // �������� ������ �������� �� ����������� ������. ��� ����� ����
  // ��� �������� �� �������� �������������� ������������, ���������
  // ������ ������� �������������� ��� ������������ ��������, ����
  // "��������" ����� ���������� �� �������� �����������. ��-�� ����
  // �� ��� ��� ��������� ������������� ������� change, ����������
  // �������� ����� ������� ������� templateChange(), �������
  // ���������� ���� "�����" � "������ ��������� ������������". �
  // ���������� �� ��������, ����������� �� ��, ����� ��������
  {
    var templateClick = function templateClick() {
      window.js_template_change_clicked = true;
    };

    $(document).on('click', '.js_template-change', templateClick);
  } // ���������� ������ ������ �������� �� ����������� ������

  $(document).on('change', '.js_template-change', templateChange);
  $(document).on('click', '.js_filter_table-reset', resetSubmitTable);

  function templateChange() {
    if (window.js_template_change_clicked !== true) {
      return;
    }

    window.js_template_change_clicked = false;
    var url = $(this).data('url');
    var val = $(this).val();
    var wrapper = $(this).data('wrapper');
    $.get(url, {
      val: val
    }, function (response) {
      if (response.val) {
        $('.' + wrapper).val(response.val);
        $('.js_template').html(response.view);
      }
    }); // ������ ��������� ���������� ��� �����

    var url1 = $(this).data('url1');
    $.get(url1, {
      company: val
    }, function (response) {
      if (response.val) {
        $('.js_template_positions').html(response.view);
      }
    }); // ������ ��������� ����������� �����

    var url2 = $(this).data('url2');
    var user_id = $('form input[name="id"]').val();
    $.get(url2, {
      user: user_id,
      company: val
    }, function (response) {
      if (response.val) {
        $('.js_template_superiors').html(response.view);
      }
    });
  } // Select Company in Buses


  $(document).on('click', '.js_company-select', selectCompanyClick);

  function selectCompanyClick() {
    window.js_company_template_change_clicked = true;
  }

  $(document).on('change', '.js_company-select', selectCompanyChange);

  function selectCompanyChange() {
    if (window.js_company_template_change_clicked !== true) {
      return;
    }

    window.js_company_template_change_clicked = false;
    var url = $(this).data('url');
    var val = $(this).val();
    $.get(url, {
      val: val
    }, function (response) {
      if (response.val) {
        $('.js_department_select').html(response.view);
      }
    });
  }

  $(document).on('change', '.review_act .onoffswitch', onoffswitchClick); // ���������� ������� �� ��������� � ��������������� �����

  function onoffswitchClick() {
    // ��� ��������� ������� �������� ������������ ����
    // �������� ��������, ��� ���������� - ��������
    if ($(this).find('input:checked').val()) {
      $(this).parent().parent().next().removeClass('invisible');
    } else {
      $(this).parent().parent().next().addClass('invisible');
    }
  } // ���������� ����������� ������ � ��������� ��������������� �����


  $(document).on('change', '.js_diagnostic_card_template-change', diagnosticCardTemplateChange);

  function diagnosticCardTemplateChange() {
    var url = $(this).data('url');
    var val = $(this).val(); //var wrapper = $(this).data('wrapper');

    $.get(url, {
      val: val
    }, function (response) {
      if (response.val) {
        //$('.' + wrapper).val(response.val);
        $('.js_template_buttons').html(response.view_buttons);
        $('.js_template').html(response.view);
        $('.diagnostic_card .act_panel').addClass('pace-active');

        if (val.length) {
          $('.diagnostic_card .buttons').removeClass('pace-active');
          $('.diagnostic_card .js_template_buttons button').removeClass('btn-success');
          $('.diagnostic_card .js_template_buttons button').addClass('btn-default');
          $('.diagnostic_card button0').removeClass('btn-default');
          $('.diagnostic_card button0').addClass('btn-success');
          $('.diagnostic_card .act_panel_0').removeClass('pace-active');
        } else {
          $('.diagnostic_card .buttons').addClass('pace-active');
        }
      }
    });
  } // ���������� ������� ������ ������ ���� �������


  $(document).on('click', '.diagnostic_card .buttons button[type="button"]', diagnosticCardButtonClicked);

  function diagnosticCardButtonClicked() {
    // ����� ������� ������
    var btnNum = $(this).data("review_act_template_id"); // ������� ��������� �� ���� ������

    $('.diagnostic_card .buttons button[type="button"]').removeClass('btn-success');
    $('.diagnostic_card .buttons button[type="button"]').addClass('btn-default'); // �������� ������, �� ������� ��������

    $(this).removeClass('btn-default');
    $(this).addClass('btn-success'); // ��� ������ � ������ �������

    var $panels = $('.diagnostic_card .act_panel');

    for (var i = 0; i < $panels.length; i++) {
      if ($panels.eq(i).data("review_act_template_id") == btnNum) {
        // ���������� ������ � ����� �������, ��������������� ������� ������
        $panels.eq(i).removeClass('pace-active');
      } else {
        // �������� ��� ��������� ������
        $panels.eq(i).addClass('pace-active');
      }
    }
  }

  function resetSubmitTable(e) {
    e.preventDefault();
    console.log('1');
    var $form = $('#filter-table');
    $form.find('.select2-block').val(null).trigger('change');
    $(".btn-filter-submit").trigger('click');
    location.reload();
    return false;
  }
});

/***/ }),

/***/ "./resources/assets/admin/js/app/change_background.js":
/*!************************************************************!*\
  !*** ./resources/assets/admin/js/app/change_background.js ***!
  \************************************************************/
/***/ (() => {

$(document).ready(function () {
  // $("#ch_img").click(function() {
  //     $.post("/admin/change_background_image");
  // });
  var files;
  $('#ch_img_upload').on('change', function () {
    if (this.files.length != 0 && this.files.length != null) {
      $('.wrapper-spinner').show();
      files = this.files[0];
      var formData = new FormData();
      formData.append('file_to_upload', files);
      $.ajax({
        type: 'POST',
        url: '/admin/change_background_image',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function success(data) {
          $('.wrapper-spinner').hide();
          document.location.reload(); // if(data.message) window.showNotification(data.message, data.result);
          // if(data.view_success) $('[data-ajax=content-success]').html(data.view_success);
        } // error: function () {
        //     $('.wrapper-spinner').hide();
        // }

      });
    }
  });
});

/***/ }),

/***/ "./resources/assets/admin/js/app/components/ajax.js":
/*!**********************************************************!*\
  !*** ./resources/assets/admin/js/app/components/ajax.js ***!
  \**********************************************************/
/***/ (() => {

$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $(document).on('change', '.js_ajax-change', ajaxChange);
  $(document).on('submit', '.js_form-ajax', submitFormAjax);
  $(document).on('click', '.js_panel_confirm', confirm);
  $(document).on('click', '.js_panel_choice', reportChoice);

  function confirm(e) {
    e.preventDefault();
    var $link = $(this);
    var url = $link.attr('href');
    var method = $link.attr('method') ? $link.attr('method') : 'get';
    var question = $(this).data('question') || 'Вы действительно хотите удалить?';
    var textSuccess = $(this).data('success') || 'Удаление прошло успешно';
    var reload = $(this).data('reload');
    bootbox.confirm({
      message: question,
      callback: function callback(result) {
        if (result) {
          $.ajax({
            url: url,
            method: method,
            success: function success(data) {
              if (data.result == 'success') {
                if ($link.hasClass('js_update-filter')) {
                  $('.js_table-search select:first').trigger('change');
                } else {
                  $link.closest('tr').remove();
                }

                if ($link.hasClass('js_update-data')) {
                  updateData(data);
                }

                window.showNotification(textSuccess, 'success');

                if (reload) {
                  window.location.reload(false);
                }
              } else {
                window.showNotification(data.message || 'Ошибка', 'error');
              }
            }
          });
        }
      }
    });
    return false;
  }

  function submitFormAjax(e) {
    e.preventDefault();
    var $form = $(this);
    $form.find('button').attr('disabled', true);
    $form.trigger('panel-form-ajax-submitted');
    $('.wrapper-spinner').show();
    $form.find('.form-group').removeClass('has-error');
    $form.find('.error-block').html('');
    $form.ajaxSubmit({
      success: function success(data) {
        $('.wrapper-spinner').hide();
        $form.find('button').attr('disabled', false);

        if (data.result == 'success') {
          $form.trigger('panel-form-ajax-success', [data]);

          if ($form.hasClass('js_form-ajax-redirect')) {
            var redirectLink = data.link || data.redirect;
            setTimeout(function () {
              return window.location.href = redirectLink;
            }, 2000);
          }

          if ($form.hasClass('js_form-ajax-popup')) {
            $form.closest('.modal').modal('hide');
          }

          if ($form.hasClass('js_form-update-data')) {
            updateData(data);
          }

          if ($form.hasClass('js_form-ajax-table')) {
            $('.js_table-search select:first').trigger('change');
          }

          if ($form.hasClass('js_form-current-page')) {
            var $currentPage = $('.js_current-page');

            if ($currentPage.length) {
              $currentPage.click();
            }
          }

          if (data.message) {
            window.showNotification(data.message, 'success');
          } else {
            window.showNotification('Данные успешно сохранены', 'success');
          }

          if (data.view) {
            $('.js_table-wrapper').html(data.view);
          } // window.countPull();

        } else if (data.result == 'warning') {
          if (data.message) {
            window.showNotification(data.message, 'warning');
          }

          if (data.view) $($form.data('wrap')).html(data.view);
          if (data.view_sub) $($form.data('wrap-sub')).html(data.view_sub);
        } else {
          $form.trigger('panel-form-ajax-error', [data]);
          $.each(data.errors, function (input, errors) {
            var inputArray = input.split('.');
            var $input = $form.find(':input[name="' + input + '"]');

            if (!$input.length && inputArray.length == 1) {
              $input = $form.find(':input[name="' + inputArray[0] + '[]"]:eq(' + inputArray[1] + ')');
            }

            if (inputArray.length == 2) {
              $input = $form.find(":input[name=\"".concat(inputArray[0], "[").concat(inputArray[1], "]\"]"));
            }

            if (inputArray.length == 3) {
              $input = $form.find(":input[name=\"".concat(inputArray[0], "[").concat(inputArray[1], "][").concat(inputArray[2], "]\"]"));
            }

            var text = '';
            $.each(errors, function (i, error) {
              return text += error + "<br>";
            });

            if ($input.length) {
              var $wrapper = $input.closest('.form-group');
              var $error_block = $wrapper.find('.error-block');
              $wrapper.addClass('has-error');
              var $help_block = '<span class="help-block">' + text + '</span>';
              $error_block.append($help_block);
            } else {
              window.showNotification(text, 'error');
            }
          });

          if (data.message) {
            window.showNotification(data.message, 'error');
          } else {
            window.showNotification('Ошибка сохранения данных', 'error');
          }

          if (data.view) {
            $('.js_table-wrapper').html(data.view);
          }
        }
      }
    });
    return false;
  }

  function ajaxChange() {
    var val = $(this).val();
    var url = $(this).data('url');
    var wrapper = $(this).data('wrapper');
    $.get(url, {
      val: val
    }, function (response) {
      if (response.html) {
        $(".".concat(wrapper)).html(response.html);
      } else {
        $(".".concat(wrapper)).html('');
      }

      if (response.message) window.showNotification(response.message, 'error');
    });
  }

  function reportChoice(e) {
    e.preventDefault();
    var $link = $(this);
    var date_type = $link.data('date-type');
    var dialog = bootbox.dialog({
      title: $link.data('title'),
      message: "<p>Выберите, какие брони отображать в отчёте.</p>",
      size: 'large',
      buttons: {
        noclose: {
          label: "Все активные",
          className: 'btn-warning',
          callback: function callback() {
            document.getElementById(date_type + "-date-all").click();
          }
        },
        ok: {
          label: "Оплаченные онлайн",
          className: 'btn-info',
          callback: function callback() {
            document.getElementById(date_type + "-date-pay").click();
          }
        }
      }
    });
    return false;
  }
});

/***/ }),

/***/ "./resources/assets/admin/js/app/components/datepicker_localization.js":
/*!*****************************************************************************!*\
  !*** ./resources/assets/admin/js/app/components/datepicker_localization.js ***!
  \*****************************************************************************/
/***/ (() => {

(function ($) {
  $.fn.datepicker.dates['ru'] = {
    days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
    daysShort: ["Вск", "Пнд", "Втр", "Срд", "Чтв", "Птн", "Суб"],
    daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
    months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
    monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
    today: "Сегодня",
    clear: "Очистить",
    format: "dd.mm.yyyy",
    weekStart: 1,
    monthsTitle: 'Месяцы'
  };
})(jQuery);

/***/ }),

/***/ "./resources/assets/admin/js/app/components/grid.js":
/*!**********************************************************!*\
  !*** ./resources/assets/admin/js/app/components/grid.js ***!
  \**********************************************************/
/***/ (() => {

$(document).ready(function () {
  window.reindexBlocks = function (parentName, parentFields, childName, childFields) {
    return function () {
      var i = 0;
      $(".js_reindex-".concat(parentName)).each(function () {
        var _this = this;

        var newName = "".concat(parentName, "[").concat(i, "]");
        parentFields.map(function (parentField) {
          var attribute = "".concat(newName, "[").concat(parentField, "]");
          $(_this).find(".js_".concat(parentName, "-").concat(parentField)).attr('name', attribute);
        });
        var j = 0;
        $(this).find(".js_".concat(parentName, "-").concat(childName)).each(function () {
          var _this2 = this;

          childFields.map(function (childField) {
            var nameOptionName = "".concat(newName, "[").concat(childName, "][]");
            $(_this2).find(".js_".concat(parentName, "-").concat(childName, "-").concat(childField)).attr('name', nameOptionName);
          });
          j++;
        });
        i++;
      });
    };
  };

  $(document).on('click', '.js_multiple-add', multipleAdd);
  $(document).on('click', '.js_multiple-remove', multipleRemove);

  function multipleAdd(e) {
    e.preventDefault();
    var name = $(this).data('name');
    var $wrapper = $(this).closest('.js_multiple-wrapper');
    var $clone = $wrapper.find('.js_multiple-row-clone[data-name="' + name + '"]');
    var $row = $clone.clone(true);
    var $inputs = $row.find(':input');
    $inputs.each(function () {
      var $input = $(this);
      $input.prop('disabled', false);

      if ($input.hasClass('js_panel_input-select2')) {
        $input.next().remove();
        $input.removeData('select2').select2();
      }
    });
    $row.insertBefore($clone);
    $row.removeClass('js_multiple-row-clone');
    $row.trigger('multiple-added');
    return false;
  }

  function multipleRemove(e) {
    e.preventDefault();
    var name = $(this).data('name');
    var $row = $(this).closest('.js_multiple-row[data-name="' + name + '"]');
    var $parent = $row.parent();
    $row.remove();
    $parent.trigger('multiple-removed');
    return false;
  }
});

/***/ }),

/***/ "./resources/assets/admin/js/app/components/import.js":
/*!************************************************************!*\
  !*** ./resources/assets/admin/js/app/components/import.js ***!
  \************************************************************/
/***/ (() => {

$(document).on('change', '.js_import input', importInput);
$(document).on('click', '.js_import a', clickImportInput);

function importInput() {
  $('.wrapper-spinner').show();
  var url = $(this).data('url');
  var formData = new FormData();
  var file = $(this)[0].files[0];
  $(this).val('');
  formData.append('file', file);
  $.ajax({
    type: 'POST',
    url: url,
    data: formData,
    processData: false,
    contentType: false,
    dataType: 'json',
    success: function success(data) {
      $('.wrapper-spinner').hide();
      if (data.message) window.showNotification(data.message, data.result);
      if (data.view_success) $('[data-ajax=content-success]').html(data.view_success);
    },
    error: function error() {
      $('.wrapper-spinner').hide();
    }
  });
}

function clickImportInput() {
  $('.js_import input').click();
  return false;
}

$(document).on('click', '.js_egis_status a', clickEgisStatus);

function clickEgisStatus(e) {
  e.preventDefault();
  $('.wrapper-spinner').show();
  var url = $(this).data('url');
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    success: function success(data) {
      $('.wrapper-spinner').hide();
      if (data.message) window.showNotification(data.message, data.result);
    },
    error: function error() {
      $('.wrapper-spinner').hide();
    }
  });
}

$(document).on('click', '.js_egis_send a', clickEgisSend);

function clickEgisSend(e) {
  e.preventDefault();
  $('.wrapper-spinner').show();
  var url = $(this).data('url');
  $.ajax({
    type: 'POST',
    url: url,
    dataType: 'json',
    success: function success(data) {
      $('.wrapper-spinner').hide();
      if (data.message) window.showNotification(data.message, data.result);
    },
    error: function error() {
      $('.wrapper-spinner').hide();
    }
  });
}

/***/ }),

/***/ "./resources/assets/admin/js/app/components/map.js":
/*!*********************************************************!*\
  !*** ./resources/assets/admin/js/app/components/map.js ***!
  \*********************************************************/
/***/ (() => {

var globalMap;

function map() {
  if ($('#map').length && !$('#map ymaps').length) {
    var myMap;
    var myPlacemark;
    var $lng = $('input[name="longitude"]');
    var $ltd = $('input[name="latitude"]');

    if (!$lng.length) {
      $lng = $('input[name="garage_latitude"]');
      $ltd = $('input[name="garage_longitude"]');
    }

    var lng = $lng.val();
    var ltd = $ltd.val();
    if (!(lng > 0)) lng = 27.553576;
    if (!(ltd > 0)) ltd = 53.901717;

    if (typeof ymaps == 'undefined') {
      return;
    }

    ymaps.ready(function () {
      var center = [ltd, lng];
      myMap = new ymaps.Map("map", {
        center: center,
        controls: ["zoomControl", "searchControl"],
        zoom: 12
      });
      myPlacemark = createPlacemark(center);
      myMap.geoObjects.add(myPlacemark);
      myPlacemark.events.add('dragend', function () {
        setCoords(myPlacemark.geometry.getCoordinates());
      });
      setCoords(center);
      myMap.events.add('click', function (e) {
        var coords = e.get('coords');
        myPlacemark.geometry.setCoordinates(coords);
        setCoords(coords);
      });

      function setCoords(coords) {
        $lng.val(coords[1]);
        $ltd.val(coords[0]);
      }

      function createPlacemark(coords) {
        return new ymaps.Placemark(coords, {}, {
          preset: 'islands#violetDotIconWithCaption',
          draggable: true
        });
      }

      globalMap = myMap;
    });
  }
} // if (typeof ymaps !== 'undefined') {


map();
window.map = map;
$(document).on('change', 'select#street_id', function () {
  ymaps.geocode($('#city_id option:selected').text() + ', ' + $('#street_id option:selected').text(), {
    results: 1
  }).then(function (res) {
    var firstGeoObject = res.geoObjects.get(0),
        bounds = firstGeoObject.properties.get('boundedBy');
    firstGeoObject.options.set('preset', 'islands#darkBlueDotIconWithCaption');
    firstGeoObject.properties.set('iconCaption', firstGeoObject.getAddressLine());
    globalMap.geoObjects.add(firstGeoObject);
    globalMap.setBounds(bounds, {
      checkZoomRange: true
    });
  });
}); // }

/***/ }),

/***/ "./resources/assets/admin/js/app/components/pusher/easy-alert.js":
/*!***********************************************************************!*\
  !*** ./resources/assets/admin/js/app/components/pusher/easy-alert.js ***!
  \***********************************************************************/
/***/ (() => {

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

(function ($) {
  $.fn.easyAlert = $.easyAlert = function (optionsOrMessage, alertType, position) {
    var hasJqueryUI = _typeof(jQuery.ui) !== ( true ? "undefined" : 0);
    var settings = {};

    if (typeof optionsOrMessage === "string") {
      settings = $.extend({}, $.fn.easyAlert.defaults, {
        'message': optionsOrMessage,
        'alertType': _typeof(alertType) === ( true ? "undefined" : 0) ? 'success' : alertType,
        'position': _typeof(position) === ( true ? "undefined" : 0) ? 'b r' : position
      });
    } else if (_typeof(optionsOrMessage) === "object" || _typeof(optionsOrMessage) === ( true ? "undefined" : 0)) {
      settings = $.extend({}, $.fn.easyAlert.defaults, optionsOrMessage);
    }

    var alertContainer = //'<div  class="easy-alert" style="position: relative; top: 50%; left:50%; transform: translate(-50%,-50%);">' +
    '<div  class="easy-alert">' + '<div class="alert alert-' + settings.alertType + '">' + settings.message + '</div>' + '</div>'; //jQuery object container

    var $alertContainer = $(alertContainer);
    var positionsArray = settings.position.split(' ');
    var vPos = _typeof(vPositions[positionsArray[0]]) === ( true ? "undefined" : 0) ? 'top' : vPositions[positionsArray[0]];
    var hPos = _typeof(hPositions[positionsArray[1]]) === ( true ? "undefined" : 0) ? 'right' : hPositions[positionsArray[1]];

    if (positionsArray.length !== 2) {
      console.error('invalid position argument');
      return;
    } //in case auto hide set to false and click to hide set to false then auto hide will be set to ture


    (settings.clickToHide | settings.autoHide) === 0 ? settings.autoHide = true : '';
    /*Todo complete rest of configuration*/

    if (_typeof(this.selector) === ( true ? "undefined" : 0)) {
      //Global alert called via $.easyAlert(...);
      var globalPositionClass;
      var containerStyle = {
        position: 'fixed',
        'min-width': settings.globalMinWidth,
        display: 'none'
      };
      /*Todo Complete mobile global alert position*/

      if (vPos === "top") {
        containerStyle.top = "5px";
        globalPositionClass = 'easy-alert-t';
        $alertContainer.attr('data-vertical', 'top');
      } else {
        containerStyle.bottom = "5px";
        globalPositionClass = 'easy-alert-b';
        $alertContainer.attr('data-vertical', 'buttom');
      }

      if (hPos === "center") {
        //center alert
        containerStyle.left = "50%";
        containerStyle.transform = 'translateX(-50%)';
        globalPositionClass += '-c';
      } else if (hPos === "left") {
        //left alert
        containerStyle.left = "5px";
        globalPositionClass += '-l';
      } else {
        //right alert
        containerStyle.right = "5px";
        globalPositionClass += '-r';
      }

      var hasLeftOrRightAnimationHide = false;
      var hasLeftOrRightAnimationShow = hasLeftOrRightAnimationHide = false;
      if ($.inArray(settings.showAnimation, ['drop', 'fold', 'scale', 'size', 'slide']) > -1) hasLeftOrRightAnimationShow = true;
      if ($.inArray(settings.hideAnimation, ['drop', 'fold', 'scale', 'size', 'slide']) > -1) hasLeftOrRightAnimationHide = true;
      var allGlobalAlerts = $('.' + globalPositionClass);
      var alertsCount = allGlobalAlerts.length;
      var calculatedPos = settings.globalSpace;

      for (var i = 0; i < alertsCount; i++) {
        calculatedPos += $(allGlobalAlerts[i]).height() + settings.globalSpace;
      }

      containerStyle.hasOwnProperty('top') ? containerStyle.top = calculatedPos + "px" : containerStyle.bottom = calculatedPos + "px";
      $alertContainer.addClass(globalPositionClass);
      $alertContainer.css(containerStyle);

      var hideAlert = function hideAlert() {
        if (!hasJqueryUI) $alertContainer.fadeOut(settings.hideDuration, completeHideAlert);else {
          var hideOptions = {
            effect: settings.hideAnimation,
            duration: settings.hideDuration,
            complete: completeHideAlert
          };
          if (hasLeftOrRightAnimationHide) hideOptions.direction = hPos;
          $alertContainer.toggle(hideOptions);
        }
      };

      var isHidden = false;

      var completeHideAlert = function completeHideAlert() {
        var currentVert = $alertContainer.attr('data-vertical');
        var currentVertVal = $alertContainer.css(currentVert);
        $alertContainer.remove();
        typeof settings.hidden === 'function' && !isHidden ? settings.hidden.call(this, this, settings.message) : null;
        isHidden = true;
        shiftGlobalAlerts('.' + globalPositionClass, currentVert, parseInt(currentVertVal), settings.globalSpace);
      };

      if (settings.clickToHide) {
        $alertContainer.on('click', function () {
          location.replace(settings.link_page); //переход на бронирование

          typeof settings.clicked === 'function' ? settings.clicked.call(this, this, settings.message) : null;
          hideAlert();
        });
      }

      if (settings.autoHide) {
        setTimeout(hideAlert, settings.time);
      }

      var showOptions = {
        effect: settings.showAnimation,
        duration: settings.showDuration
      };
      typeof settings.complete === 'function' ? showOptions.complete = function () {
        settings.complete.call(this, settings.message, this);
      } : null;
      if (hasLeftOrRightAnimationShow) showOptions.direction = hPos;
      $alertContainer.appendTo('body').show(showOptions);
      return settings.message;
    }

    return this;
  };

  var shiftGlobalAlerts = function shiftGlobalAlerts(selector, verticalDirection, currentVerticalVal, globalSpace) {
    $(selector).each(function () {
      var currentAlertHeight = Number($(this).height()) + Number(globalSpace);
      var currentAlertVertVal = parseInt($(this).css(verticalDirection));

      if (currentVerticalVal < currentAlertVertVal && currentAlertVertVal - currentAlertHeight >= globalSpace) {
        if (verticalDirection === "top") $(this).animate({
          'top': currentAlertVertVal - currentAlertHeight + 'px'
        }, 300);else $(this).animate({
          'bottom': currentAlertVertVal - currentAlertHeight + 'px'
        }, 300);
      }
    });
  }; //vertical position


  var vPositions = {
    't': 'top',
    'm': 'middle',
    'b': 'bottom'
  }; //horizontal position

  var hPositions = {
    'l': 'left',
    'c': 'center',
    'r': 'right'
  }; // Defaults parameters are here, You can override them for your own purpose.

  $.fn.easyAlert.defaults = {
    'message': "Easy alert-js By Ali Dalal",
    'alertType': 'success',
    'position': "b r",
    globalMinWidth: '250px',
    clickToHide: true,
    autoHide: false,
    time: 5000,
    showAnimation: 'fade',
    showDuration: 300,
    hideAnimation: 'fade',
    hideDuration: 300,
    globalSpace: 5,
    complete: null,
    clicked: null,
    hidden: null
  }; // End of defaults
})(jQuery);

/***/ }),

/***/ "./resources/assets/admin/js/app/components/pusher/pusher.js":
/*!*******************************************************************!*\
  !*** ./resources/assets/admin/js/app/components/pusher/pusher.js ***!
  \*******************************************************************/
/***/ (() => {

//Pusher.logToConsole = true;
var pusher = new Pusher('c3a62f6331e2e8f12695', {
  cluster: 'eu',
  encrypted: true
});
var channel = pusher.subscribe('my-channel');
channel.bind('my-event', function (data) {
  if ((window.user_sip == data.sip || data.sip == 'all') && data.app_url == APP_URL) {
    //window.open('http://'+ window.location.hostname +'/admin/orders/create?incomming_phone='+data.number);
    $.easyAlert({
      'message': '<a><div class="text-center text-uppercase"><b>Входящий звонок: ' + data.message + '</b></div></a>',
      'alertType': data.type,
      'link_page': 'https://' + window.location.hostname + '/admin/tours?status=active&incomming_phone=' + data.number,
      'position': "t c",
      globalMinWidth: '400px',
      clickToHide: true,
      autoHide: true,
      time: data.time_show * 1000,
      showAnimation: 'fade',
      showDuration: 300,
      hideAnimation: 'fade',
      hideDuration: 300,
      globalSpace: 5,
      complete: null,
      clicked: null,
      hidden: null
    });
  }
});

/***/ }),

/***/ "./resources/assets/admin/js/app/components/pusher/pusher.min.js":
/*!***********************************************************************!*\
  !*** ./resources/assets/admin/js/app/components/pusher/pusher.min.js ***!
  \***********************************************************************/
/***/ (function(module, exports, __webpack_require__) {

/* module decorator */ module = __webpack_require__.nmd(module);
var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

/*!
 * Pusher JavaScript Library v4.0.0
 * http://pusher.com/
 *
 * Copyright 2016, Pusher
 * Released under the MIT licence.
 */
!function (t, e) {
  "object" == ( false ? 0 : _typeof(exports)) && "object" == ( false ? 0 : _typeof(module)) ? module.exports = e() :  true ? !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_FACTORY__ = (e),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : 0;
}(this, function () {
  return function (t) {
    function e(i) {
      if (n[i]) return n[i].exports;
      var o = n[i] = {
        exports: {},
        id: i,
        loaded: !1
      };
      return t[i].call(o.exports, o, o.exports, e), o.loaded = !0, o.exports;
    }

    var n = {};
    return e.m = t, e.c = n, e.p = "", e(0);
  }([function (t, e, n) {
    "use strict";

    var i = n(1);
    t.exports = i["default"];
  }, function (t, e, n) {
    "use strict";

    function i(t) {
      if (null === t || void 0 === t) throw "You must pass your app key when you instantiate Pusher.";
    }

    var o = n(2),
        r = n(9),
        s = n(23),
        a = n(38),
        c = n(39),
        u = n(40),
        l = n(12),
        h = n(5),
        p = n(62),
        f = n(8),
        d = n(42),
        y = function () {
      function t(e, n) {
        var l = this;
        i(e), n = n || {}, this.key = e, this.config = r.extend(p.getGlobalConfig(), n.cluster ? p.getClusterConfig(n.cluster) : {}, n), this.channels = d["default"].createChannels(), this.global_emitter = new s["default"](), this.sessionID = Math.floor(1e9 * Math.random()), this.timeline = new a["default"](this.key, this.sessionID, {
          cluster: this.config.cluster,
          features: t.getClientFeatures(),
          params: this.config.timelineParams || {},
          limit: 50,
          level: c["default"].INFO,
          version: h["default"].VERSION
        }), this.config.disableStats || (this.timelineSender = d["default"].createTimelineSender(this.timeline, {
          host: this.config.statsHost,
          path: "/timeline/v2/" + o["default"].TimelineTransport.name
        }));

        var y = function y(t) {
          var e = r.extend({}, l.config, t);
          return u.build(o["default"].getDefaultStrategy(e), e);
        };

        this.connection = d["default"].createConnectionManager(this.key, r.extend({
          getStrategy: y,
          timeline: this.timeline,
          activityTimeout: this.config.activity_timeout,
          pongTimeout: this.config.pong_timeout,
          unavailableTimeout: this.config.unavailable_timeout
        }, this.config, {
          encrypted: this.isEncrypted()
        })), this.connection.bind("connected", function () {
          l.subscribeAll(), l.timelineSender && l.timelineSender.send(l.connection.isEncrypted());
        }), this.connection.bind("message", function (t) {
          var e = 0 === t.event.indexOf("pusher_internal:");

          if (t.channel) {
            var n = l.channel(t.channel);
            n && n.handleEvent(t.event, t.data);
          }

          e || l.global_emitter.emit(t.event, t.data);
        }), this.connection.bind("connecting", function () {
          l.channels.disconnect();
        }), this.connection.bind("disconnected", function () {
          l.channels.disconnect();
        }), this.connection.bind("error", function (t) {
          f["default"].warn("Error", t);
        }), t.instances.push(this), this.timeline.info({
          instances: t.instances.length
        }), t.isReady && this.connect();
      }

      return t.ready = function () {
        t.isReady = !0;

        for (var e = 0, n = t.instances.length; e < n; e++) {
          t.instances[e].connect();
        }
      }, t.log = function (e) {
        t.logToConsole && window.console && window.console.log && window.console.log(e);
      }, t.getClientFeatures = function () {
        return r.keys(r.filterObject({
          ws: o["default"].Transports.ws
        }, function (t) {
          return t.isSupported({});
        }));
      }, t.prototype.channel = function (t) {
        return this.channels.find(t);
      }, t.prototype.allChannels = function () {
        return this.channels.all();
      }, t.prototype.connect = function () {
        if (this.connection.connect(), this.timelineSender && !this.timelineSenderTimer) {
          var t = this.connection.isEncrypted(),
              e = this.timelineSender;
          this.timelineSenderTimer = new l.PeriodicTimer(6e4, function () {
            e.send(t);
          });
        }
      }, t.prototype.disconnect = function () {
        this.connection.disconnect(), this.timelineSenderTimer && (this.timelineSenderTimer.ensureAborted(), this.timelineSenderTimer = null);
      }, t.prototype.bind = function (t, e, n) {
        return this.global_emitter.bind(t, e, n), this;
      }, t.prototype.unbind = function (t, e, n) {
        return this.global_emitter.unbind(t, e, n), this;
      }, t.prototype.bind_global = function (t) {
        return this.global_emitter.bind_global(t), this;
      }, t.prototype.unbind_global = function (t) {
        return this.global_emitter.unbind_global(t), this;
      }, t.prototype.unbind_all = function (t) {
        return this.global_emitter.unbind_all(), this;
      }, t.prototype.subscribeAll = function () {
        var t;

        for (t in this.channels.channels) {
          this.channels.channels.hasOwnProperty(t) && this.subscribe(t);
        }
      }, t.prototype.subscribe = function (t) {
        var e = this.channels.add(t, this);
        return e.subscriptionPending && e.subscriptionCancelled ? e.reinstateSubscription() : e.subscriptionPending || "connected" !== this.connection.state || e.subscribe(), e;
      }, t.prototype.unsubscribe = function (t) {
        var e = this.channels.find(t);
        e && e.subscriptionPending ? e.cancelSubscription() : (e = this.channels.remove(t), e && "connected" === this.connection.state && e.unsubscribe());
      }, t.prototype.send_event = function (t, e, n) {
        return this.connection.send_event(t, e, n);
      }, t.prototype.isEncrypted = function () {
        return "https:" === o["default"].getProtocol() || Boolean(this.config.encrypted);
      }, t.instances = [], t.isReady = !1, t.logToConsole = !1, t.Runtime = o["default"], t.ScriptReceivers = o["default"].ScriptReceivers, t.DependenciesReceivers = o["default"].DependenciesReceivers, t.auth_callbacks = o["default"].auth_callbacks, t;
    }();

    e.__esModule = !0, e["default"] = y, o["default"].setup(y);
  }, function (t, e, n) {
    "use strict";

    var i = n(3),
        o = n(7),
        r = n(14),
        s = n(15),
        a = n(16),
        c = n(4),
        u = n(17),
        l = n(18),
        h = n(25),
        p = n(26),
        f = n(27),
        d = n(28),
        y = {
      nextAuthCallbackID: 1,
      auth_callbacks: {},
      ScriptReceivers: c.ScriptReceivers,
      DependenciesReceivers: i.DependenciesReceivers,
      getDefaultStrategy: p["default"],
      Transports: l["default"],
      transportConnectionInitializer: f["default"],
      HTTPFactory: d["default"],
      TimelineTransport: u["default"],
      getXHRAPI: function getXHRAPI() {
        return window.XMLHttpRequest;
      },
      getWebSocketAPI: function getWebSocketAPI() {
        return window.WebSocket || window.MozWebSocket;
      },
      setup: function setup(t) {
        var e = this;
        window.Pusher = t;

        var n = function n() {
          e.onDocumentBody(t.ready);
        };

        window.JSON ? n() : i.Dependencies.load("json2", {}, n);
      },
      getDocument: function getDocument() {
        return document;
      },
      getProtocol: function getProtocol() {
        return this.getDocument().location.protocol;
      },
      getAuthorizers: function getAuthorizers() {
        return {
          ajax: o["default"],
          jsonp: r["default"]
        };
      },
      onDocumentBody: function onDocumentBody(t) {
        var e = this;
        document.body ? t() : setTimeout(function () {
          e.onDocumentBody(t);
        }, 0);
      },
      createJSONPRequest: function createJSONPRequest(t, e) {
        return new a["default"](t, e);
      },
      createScriptRequest: function createScriptRequest(t) {
        return new s["default"](t);
      },
      getLocalStorage: function getLocalStorage() {
        try {
          return window.localStorage;
        } catch (t) {
          return;
        }
      },
      createXHR: function createXHR() {
        return this.getXHRAPI() ? this.createXMLHttpRequest() : this.createMicrosoftXHR();
      },
      createXMLHttpRequest: function createXMLHttpRequest() {
        var t = this.getXHRAPI();
        return new t();
      },
      createMicrosoftXHR: function createMicrosoftXHR() {
        return new ActiveXObject("Microsoft.XMLHTTP");
      },
      getNetwork: function getNetwork() {
        return h.Network;
      },
      createWebSocket: function createWebSocket(t) {
        var e = this.getWebSocketAPI();
        return new e(t);
      },
      createSocketRequest: function createSocketRequest(t, e) {
        if (this.isXHRSupported()) return this.HTTPFactory.createXHR(t, e);
        if (this.isXDRSupported(0 === e.indexOf("https:"))) return this.HTTPFactory.createXDR(t, e);
        throw "Cross-origin HTTP requests are not supported";
      },
      isXHRSupported: function isXHRSupported() {
        var t = this.getXHRAPI();
        return Boolean(t) && void 0 !== new t().withCredentials;
      },
      isXDRSupported: function isXDRSupported(t) {
        var e = t ? "https:" : "http:",
            n = this.getProtocol();
        return Boolean(window.XDomainRequest) && n === e;
      },
      addUnloadListener: function addUnloadListener(t) {
        void 0 !== window.addEventListener ? window.addEventListener("unload", t, !1) : void 0 !== window.attachEvent && window.attachEvent("onunload", t);
      },
      removeUnloadListener: function removeUnloadListener(t) {
        void 0 !== window.addEventListener ? window.removeEventListener("unload", t, !1) : void 0 !== window.detachEvent && window.detachEvent("onunload", t);
      }
    };
    e.__esModule = !0, e["default"] = y;
  }, function (t, e, n) {
    "use strict";

    var i = n(4),
        o = n(5),
        r = n(6);
    e.DependenciesReceivers = new i.ScriptReceiverFactory("_pusher_dependencies", "Pusher.DependenciesReceivers"), e.Dependencies = new r["default"]({
      cdn_http: o["default"].cdn_http,
      cdn_https: o["default"].cdn_https,
      version: o["default"].VERSION,
      suffix: o["default"].dependency_suffix,
      receivers: e.DependenciesReceivers
    });
  }, function (t, e) {
    "use strict";

    var n = function () {
      function t(t, e) {
        this.lastId = 0, this.prefix = t, this.name = e;
      }

      return t.prototype.create = function (t) {
        this.lastId++;

        var e = this.lastId,
            n = this.prefix + e,
            i = this.name + "[" + e + "]",
            o = !1,
            r = function r() {
          o || (t.apply(null, arguments), o = !0);
        };

        return this[e] = r, {
          number: e,
          id: n,
          name: i,
          callback: r
        };
      }, t.prototype.remove = function (t) {
        delete this[t.number];
      }, t;
    }();

    e.ScriptReceiverFactory = n, e.ScriptReceivers = new n("_pusher_script_", "Pusher.ScriptReceivers");
  }, function (t, e) {
    "use strict";

    var n = {
      VERSION: "4.0.0",
      PROTOCOL: 7,
      host: "ws.pusherapp.com",
      ws_port: 80,
      wss_port: 443,
      sockjs_host: "sockjs.pusher.com",
      sockjs_http_port: 80,
      sockjs_https_port: 443,
      sockjs_path: "/pusher",
      stats_host: "stats.pusher.com",
      channel_auth_endpoint: "/pusher/auth",
      channel_auth_transport: "ajax",
      activity_timeout: 12e4,
      pong_timeout: 3e4,
      unavailable_timeout: 1e4,
      cdn_http: "http://js.pusher.com",
      cdn_https: "https://js.pusher.com",
      dependency_suffix: ".min"
    };
    e.__esModule = !0, e["default"] = n;
  }, function (t, e, n) {
    "use strict";

    var i = n(4),
        o = n(2),
        r = function () {
      function t(t) {
        this.options = t, this.receivers = t.receivers || i.ScriptReceivers, this.loading = {};
      }

      return t.prototype.load = function (t, e, n) {
        var i = this;
        if (i.loading[t] && i.loading[t].length > 0) i.loading[t].push(n);else {
          i.loading[t] = [n];
          var r = o["default"].createScriptRequest(i.getPath(t, e)),
              s = i.receivers.create(function (e) {
            if (i.receivers.remove(s), i.loading[t]) {
              var n = i.loading[t];
              delete i.loading[t];

              for (var o = function o(t) {
                t || r.cleanup();
              }, a = 0; a < n.length; a++) {
                n[a](e, o);
              }
            }
          });
          r.send(s);
        }
      }, t.prototype.getRoot = function (t) {
        var e,
            n = o["default"].getDocument().location.protocol;
        return e = t && t.encrypted || "https:" === n ? this.options.cdn_https : this.options.cdn_http, e.replace(/\/*$/, "") + "/" + this.options.version;
      }, t.prototype.getPath = function (t, e) {
        return this.getRoot(e) + "/" + t + this.options.suffix + ".js";
      }, t;
    }();

    e.__esModule = !0, e["default"] = r;
  }, function (t, e, n) {
    "use strict";

    var i = n(8),
        o = n(2),
        r = function r(t, e, n) {
      var r,
          s = this;
      r = o["default"].createXHR(), r.open("POST", s.options.authEndpoint, !0), r.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      for (var a in this.authOptions.headers) {
        r.setRequestHeader(a, this.authOptions.headers[a]);
      }

      return r.onreadystatechange = function () {
        if (4 === r.readyState) if (200 === r.status) {
          var t,
              e = !1;

          try {
            t = JSON.parse(r.responseText), e = !0;
          } catch (o) {
            n(!0, "JSON returned from webapp was invalid, yet status code was 200. Data was: " + r.responseText);
          }

          e && n(!1, t);
        } else i["default"].warn("Couldn't get auth info from your webapp", r.status), n(!0, r.status);
      }, r.send(this.composeQuery(e)), r;
    };

    e.__esModule = !0, e["default"] = r;
  }, function (t, e, n) {
    "use strict";

    var i = n(9),
        o = n(1),
        r = {
      debug: function debug() {
        for (var t = [], e = 0; e < arguments.length; e++) {
          t[e - 0] = arguments[e];
        }

        o["default"].log && o["default"].log(i.stringify.apply(this, arguments));
      },
      warn: function warn() {
        for (var t = [], e = 0; e < arguments.length; e++) {
          t[e - 0] = arguments[e];
        }

        var n = i.stringify.apply(this, arguments);
        window.console && (window.console.warn ? window.console.warn(n) : window.console.log && window.console.log(n)), o["default"].log && o["default"].log(n);
      }
    };
    e.__esModule = !0, e["default"] = r;
  }, function (t, e, n) {
    "use strict";

    function i(t) {
      for (var e = [], n = 1; n < arguments.length; n++) {
        e[n - 1] = arguments[n];
      }

      for (var o = 0; o < e.length; o++) {
        var r = e[o];

        for (var s in r) {
          r[s] && r[s].constructor && r[s].constructor === Object ? t[s] = i(t[s] || {}, r[s]) : t[s] = r[s];
        }
      }

      return t;
    }

    function o() {
      for (var t = ["Pusher"], e = 0; e < arguments.length; e++) {
        "string" == typeof arguments[e] ? t.push(arguments[e]) : t.push(_(arguments[e]));
      }

      return t.join(" : ");
    }

    function r(t, e) {
      var n = Array.prototype.indexOf;
      if (null === t) return -1;
      if (n && t.indexOf === n) return t.indexOf(e);

      for (var i = 0, o = t.length; i < o; i++) {
        if (t[i] === e) return i;
      }

      return -1;
    }

    function s(t, e) {
      for (var n in t) {
        Object.prototype.hasOwnProperty.call(t, n) && e(t[n], n, t);
      }
    }

    function a(t) {
      var e = [];
      return s(t, function (t, n) {
        e.push(n);
      }), e;
    }

    function c(t) {
      var e = [];
      return s(t, function (t) {
        e.push(t);
      }), e;
    }

    function u(t, e, n) {
      for (var i = 0; i < t.length; i++) {
        e.call(n || window, t[i], i, t);
      }
    }

    function l(t, e) {
      for (var n = [], i = 0; i < t.length; i++) {
        n.push(e(t[i], i, t, n));
      }

      return n;
    }

    function h(t, e) {
      var n = {};
      return s(t, function (t, i) {
        n[i] = e(t);
      }), n;
    }

    function p(t, e) {
      e = e || function (t) {
        return !!t;
      };

      for (var n = [], i = 0; i < t.length; i++) {
        e(t[i], i, t, n) && n.push(t[i]);
      }

      return n;
    }

    function f(t, e) {
      var n = {};
      return s(t, function (i, o) {
        (e && e(i, o, t, n) || Boolean(i)) && (n[o] = i);
      }), n;
    }

    function d(t) {
      var e = [];
      return s(t, function (t, n) {
        e.push([n, t]);
      }), e;
    }

    function y(t, e) {
      for (var n = 0; n < t.length; n++) {
        if (e(t[n], n, t)) return !0;
      }

      return !1;
    }

    function v(t, e) {
      for (var n = 0; n < t.length; n++) {
        if (!e(t[n], n, t)) return !1;
      }

      return !0;
    }

    function g(t) {
      return h(t, function (t) {
        return "object" == _typeof(t) && (t = _(t)), encodeURIComponent(w["default"](t.toString()));
      });
    }

    function m(t) {
      var e = f(t, function (t) {
        return void 0 !== t;
      }),
          n = l(d(g(e)), k["default"].method("join", "=")).join("&");
      return n;
    }

    function b(t) {
      var e = [],
          n = [];
      return function i(t, o) {
        var r, s, a;

        switch (_typeof(t)) {
          case "object":
            if (!t) return null;

            for (r = 0; r < e.length; r += 1) {
              if (e[r] === t) return {
                $ref: n[r]
              };
            }

            if (e.push(t), n.push(o), "[object Array]" === Object.prototype.toString.apply(t)) for (a = [], r = 0; r < t.length; r += 1) {
              a[r] = i(t[r], o + "[" + r + "]");
            } else {
              a = {};

              for (s in t) {
                Object.prototype.hasOwnProperty.call(t, s) && (a[s] = i(t[s], o + "[" + JSON.stringify(s) + "]"));
              }
            }
            return a;

          case "number":
          case "string":
          case "boolean":
            return t;
        }
      }(t, "$");
    }

    function _(t) {
      try {
        return JSON.stringify(t);
      } catch (e) {
        return JSON.stringify(b(t));
      }
    }

    var w = n(10),
        k = n(11);
    e.extend = i, e.stringify = o, e.arrayIndexOf = r, e.objectApply = s, e.keys = a, e.values = c, e.apply = u, e.map = l, e.mapObject = h, e.filter = p, e.filterObject = f, e.flatten = d, e.any = y, e.all = v, e.encodeParamsObject = g, e.buildQueryString = m, e.decycleObject = b, e.safeJSONStringify = _;
  }, function (t, e, n) {
    "use strict";

    function i(t) {
      return p(l(t));
    }

    e.__esModule = !0, e["default"] = i;

    for (var o = String.fromCharCode, r = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/", s = {}, a = 0, c = r.length; a < c; a++) {
      s[r.charAt(a)] = a;
    }

    var u = function u(t) {
      var e = t.charCodeAt(0);
      return e < 128 ? t : e < 2048 ? o(192 | e >>> 6) + o(128 | 63 & e) : o(224 | e >>> 12 & 15) + o(128 | e >>> 6 & 63) + o(128 | 63 & e);
    },
        l = function l(t) {
      return t.replace(/[^\x00-\x7F]/g, u);
    },
        h = function h(t) {
      var e = [0, 2, 1][t.length % 3],
          n = t.charCodeAt(0) << 16 | (t.length > 1 ? t.charCodeAt(1) : 0) << 8 | (t.length > 2 ? t.charCodeAt(2) : 0),
          i = [r.charAt(n >>> 18), r.charAt(n >>> 12 & 63), e >= 2 ? "=" : r.charAt(n >>> 6 & 63), e >= 1 ? "=" : r.charAt(63 & n)];
      return i.join("");
    },
        p = window.btoa || function (t) {
      return t.replace(/[\s\S]{1,3}/g, h);
    };
  }, function (t, e, n) {
    "use strict";

    var i = n(12),
        o = {
      now: function now() {
        return Date.now ? Date.now() : new Date().valueOf();
      },
      defer: function defer(t) {
        return new i.OneOffTimer(0, t);
      },
      method: function method(t) {
        for (var e = [], n = 1; n < arguments.length; n++) {
          e[n - 1] = arguments[n];
        }

        var i = Array.prototype.slice.call(arguments, 1);
        return function (e) {
          return e[t].apply(e, i.concat(arguments));
        };
      }
    };
    e.__esModule = !0, e["default"] = o;
  }, function (t, e, n) {
    "use strict";

    function i(t) {
      window.clearTimeout(t);
    }

    function o(t) {
      window.clearInterval(t);
    }

    var r = this && this.__extends || function (t, e) {
      function n() {
        this.constructor = t;
      }

      for (var i in e) {
        e.hasOwnProperty(i) && (t[i] = e[i]);
      }

      t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
    },
        s = n(13),
        a = function (t) {
      function e(e, n) {
        t.call(this, setTimeout, i, e, function (t) {
          return n(), null;
        });
      }

      return r(e, t), e;
    }(s["default"]);

    e.OneOffTimer = a;

    var c = function (t) {
      function e(e, n) {
        t.call(this, setInterval, o, e, function (t) {
          return n(), t;
        });
      }

      return r(e, t), e;
    }(s["default"]);

    e.PeriodicTimer = c;
  }, function (t, e) {
    "use strict";

    var n = function () {
      function t(t, e, n, i) {
        var o = this;
        this.clear = e, this.timer = t(function () {
          o.timer && (o.timer = i(o.timer));
        }, n);
      }

      return t.prototype.isRunning = function () {
        return null !== this.timer;
      }, t.prototype.ensureAborted = function () {
        this.timer && (this.clear(this.timer), this.timer = null);
      }, t;
    }();

    e.__esModule = !0, e["default"] = n;
  }, function (t, e, n) {
    "use strict";

    var i = n(8),
        o = function o(t, e, n) {
      void 0 !== this.authOptions.headers && i["default"].warn("Warn", "To send headers with the auth request, you must use AJAX, rather than JSONP.");
      var o = t.nextAuthCallbackID.toString();
      t.nextAuthCallbackID++;
      var r = t.getDocument(),
          s = r.createElement("script");

      t.auth_callbacks[o] = function (t) {
        n(!1, t);
      };

      var a = "Pusher.auth_callbacks['" + o + "']";
      s.src = this.options.authEndpoint + "?callback=" + encodeURIComponent(a) + "&" + this.composeQuery(e);
      var c = r.getElementsByTagName("head")[0] || r.documentElement;
      c.insertBefore(s, c.firstChild);
    };

    e.__esModule = !0, e["default"] = o;
  }, function (t, e) {
    "use strict";

    var n = function () {
      function t(t) {
        this.src = t;
      }

      return t.prototype.send = function (t) {
        var e = this,
            n = "Error loading " + e.src;
        e.script = document.createElement("script"), e.script.id = t.id, e.script.src = e.src, e.script.type = "text/javascript", e.script.charset = "UTF-8", e.script.addEventListener ? (e.script.onerror = function () {
          t.callback(n);
        }, e.script.onload = function () {
          t.callback(null);
        }) : e.script.onreadystatechange = function () {
          "loaded" !== e.script.readyState && "complete" !== e.script.readyState || t.callback(null);
        }, void 0 === e.script.async && document.attachEvent && /opera/i.test(navigator.userAgent) ? (e.errorScript = document.createElement("script"), e.errorScript.id = t.id + "_error", e.errorScript.text = t.name + "('" + n + "');", e.script.async = e.errorScript.async = !1) : e.script.async = !0;
        var i = document.getElementsByTagName("head")[0];
        i.insertBefore(e.script, i.firstChild), e.errorScript && i.insertBefore(e.errorScript, e.script.nextSibling);
      }, t.prototype.cleanup = function () {
        this.script && (this.script.onload = this.script.onerror = null, this.script.onreadystatechange = null), this.script && this.script.parentNode && this.script.parentNode.removeChild(this.script), this.errorScript && this.errorScript.parentNode && this.errorScript.parentNode.removeChild(this.errorScript), this.script = null, this.errorScript = null;
      }, t;
    }();

    e.__esModule = !0, e["default"] = n;
  }, function (t, e, n) {
    "use strict";

    var i = n(9),
        o = n(2),
        r = function () {
      function t(t, e) {
        this.url = t, this.data = e;
      }

      return t.prototype.send = function (t) {
        if (!this.request) {
          var e = i.buildQueryString(this.data),
              n = this.url + "/" + t.number + "?" + e;
          this.request = o["default"].createScriptRequest(n), this.request.send(t);
        }
      }, t.prototype.cleanup = function () {
        this.request && this.request.cleanup();
      }, t;
    }();

    e.__esModule = !0, e["default"] = r;
  }, function (t, e, n) {
    "use strict";

    var i = n(2),
        o = n(4),
        r = function r(t, e) {
      return function (n, r) {
        var s = "http" + (e ? "s" : "") + "://",
            a = s + (t.host || t.options.host) + t.options.path,
            c = i["default"].createJSONPRequest(a, n),
            u = i["default"].ScriptReceivers.create(function (e, n) {
          o.ScriptReceivers.remove(u), c.cleanup(), n && n.host && (t.host = n.host), r && r(e, n);
        });
        c.send(u);
      };
    },
        s = {
      name: "jsonp",
      getAgent: r
    };

    e.__esModule = !0, e["default"] = s;
  }, function (t, e, n) {
    "use strict";

    var i = n(19),
        o = n(21),
        r = n(20),
        s = n(2),
        a = n(3),
        c = n(9),
        u = new o["default"]({
      file: "sockjs",
      urls: r.sockjs,
      handlesActivityChecks: !0,
      supportsPing: !1,
      isSupported: function isSupported() {
        return !0;
      },
      isInitialized: function isInitialized() {
        return void 0 !== window.SockJS;
      },
      getSocket: function getSocket(t, e) {
        return new window.SockJS(t, null, {
          js_path: a.Dependencies.getPath("sockjs", {
            encrypted: e.encrypted
          }),
          ignore_null_origin: e.ignoreNullOrigin
        });
      },
      beforeOpen: function beforeOpen(t, e) {
        t.send(JSON.stringify({
          path: e
        }));
      }
    }),
        l = {
      isSupported: function isSupported(t) {
        var e = s["default"].isXDRSupported(t.encrypted);
        return e;
      }
    },
        h = new o["default"](c.extend({}, i.streamingConfiguration, l)),
        p = new o["default"](c.extend({}, i.pollingConfiguration, l));
    i["default"].xdr_streaming = h, i["default"].xdr_polling = p, i["default"].sockjs = u, e.__esModule = !0, e["default"] = i["default"];
  }, function (t, e, n) {
    "use strict";

    var i = n(20),
        o = n(21),
        r = n(9),
        s = n(2),
        a = new o["default"]({
      urls: i.ws,
      handlesActivityChecks: !1,
      supportsPing: !1,
      isInitialized: function isInitialized() {
        return Boolean(s["default"].getWebSocketAPI());
      },
      isSupported: function isSupported() {
        return Boolean(s["default"].getWebSocketAPI());
      },
      getSocket: function getSocket(t) {
        return s["default"].createWebSocket(t);
      }
    }),
        c = {
      urls: i.http,
      handlesActivityChecks: !1,
      supportsPing: !0,
      isInitialized: function isInitialized() {
        return !0;
      }
    };
    e.streamingConfiguration = r.extend({
      getSocket: function getSocket(t) {
        return s["default"].HTTPFactory.createStreamingSocket(t);
      }
    }, c), e.pollingConfiguration = r.extend({
      getSocket: function getSocket(t) {
        return s["default"].HTTPFactory.createPollingSocket(t);
      }
    }, c);
    var u = {
      isSupported: function isSupported() {
        return s["default"].isXHRSupported();
      }
    },
        l = new o["default"](r.extend({}, e.streamingConfiguration, u)),
        h = new o["default"](r.extend({}, e.pollingConfiguration, u)),
        p = {
      ws: a,
      xhr_streaming: l,
      xhr_polling: h
    };
    e.__esModule = !0, e["default"] = p;
  }, function (t, e, n) {
    "use strict";

    function i(t, e, n) {
      var i = t + (e.encrypted ? "s" : ""),
          o = e.encrypted ? e.hostEncrypted : e.hostUnencrypted;
      return i + "://" + o + n;
    }

    function o(t, e) {
      var n = "/app/" + t,
          i = "?protocol=" + r["default"].PROTOCOL + "&client=js&version=" + r["default"].VERSION + (e ? "&" + e : "");
      return n + i;
    }

    var r = n(5);
    e.ws = {
      getInitial: function getInitial(t, e) {
        return i("ws", e, o(t, "flash=false"));
      }
    }, e.http = {
      getInitial: function getInitial(t, e) {
        var n = (e.httpPath || "/pusher") + o(t);
        return i("http", e, n);
      }
    }, e.sockjs = {
      getInitial: function getInitial(t, e) {
        return i("http", e, e.httpPath || "/pusher");
      },
      getPath: function getPath(t, e) {
        return o(t);
      }
    };
  }, function (t, e, n) {
    "use strict";

    var i = n(22),
        o = function () {
      function t(t) {
        this.hooks = t;
      }

      return t.prototype.isSupported = function (t) {
        return this.hooks.isSupported(t);
      }, t.prototype.createConnection = function (t, e, n, o) {
        return new i["default"](this.hooks, t, e, n, o);
      }, t;
    }();

    e.__esModule = !0, e["default"] = o;
  }, function (t, e, n) {
    "use strict";

    var i = this && this.__extends || function (t, e) {
      function n() {
        this.constructor = t;
      }

      for (var i in e) {
        e.hasOwnProperty(i) && (t[i] = e[i]);
      }

      t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
    },
        o = n(11),
        r = n(9),
        s = n(23),
        a = n(8),
        c = n(2),
        u = function (t) {
      function e(e, n, i, o, r) {
        t.call(this), this.initialize = c["default"].transportConnectionInitializer, this.hooks = e, this.name = n, this.priority = i, this.key = o, this.options = r, this.state = "new", this.timeline = r.timeline, this.activityTimeout = r.activityTimeout, this.id = this.timeline.generateUniqueID();
      }

      return i(e, t), e.prototype.handlesActivityChecks = function () {
        return Boolean(this.hooks.handlesActivityChecks);
      }, e.prototype.supportsPing = function () {
        return Boolean(this.hooks.supportsPing);
      }, e.prototype.connect = function () {
        var t = this;
        if (this.socket || "initialized" !== this.state) return !1;
        var e = this.hooks.urls.getInitial(this.key, this.options);

        try {
          this.socket = this.hooks.getSocket(e, this.options);
        } catch (n) {
          return o["default"].defer(function () {
            t.onError(n), t.changeState("closed");
          }), !1;
        }

        return this.bindListeners(), a["default"].debug("Connecting", {
          transport: this.name,
          url: e
        }), this.changeState("connecting"), !0;
      }, e.prototype.close = function () {
        return !!this.socket && (this.socket.close(), !0);
      }, e.prototype.send = function (t) {
        var e = this;
        return "open" === this.state && (o["default"].defer(function () {
          e.socket && e.socket.send(t);
        }), !0);
      }, e.prototype.ping = function () {
        "open" === this.state && this.supportsPing() && this.socket.ping();
      }, e.prototype.onOpen = function () {
        this.hooks.beforeOpen && this.hooks.beforeOpen(this.socket, this.hooks.urls.getPath(this.key, this.options)), this.changeState("open"), this.socket.onopen = void 0;
      }, e.prototype.onError = function (t) {
        this.emit("error", {
          type: "WebSocketError",
          error: t
        }), this.timeline.error(this.buildTimelineMessage({
          error: t.toString()
        }));
      }, e.prototype.onClose = function (t) {
        t ? this.changeState("closed", {
          code: t.code,
          reason: t.reason,
          wasClean: t.wasClean
        }) : this.changeState("closed"), this.unbindListeners(), this.socket = void 0;
      }, e.prototype.onMessage = function (t) {
        this.emit("message", t);
      }, e.prototype.onActivity = function () {
        this.emit("activity");
      }, e.prototype.bindListeners = function () {
        var t = this;
        this.socket.onopen = function () {
          t.onOpen();
        }, this.socket.onerror = function (e) {
          t.onError(e);
        }, this.socket.onclose = function (e) {
          t.onClose(e);
        }, this.socket.onmessage = function (e) {
          t.onMessage(e);
        }, this.supportsPing() && (this.socket.onactivity = function () {
          t.onActivity();
        });
      }, e.prototype.unbindListeners = function () {
        this.socket && (this.socket.onopen = void 0, this.socket.onerror = void 0, this.socket.onclose = void 0, this.socket.onmessage = void 0, this.supportsPing() && (this.socket.onactivity = void 0));
      }, e.prototype.changeState = function (t, e) {
        this.state = t, this.timeline.info(this.buildTimelineMessage({
          state: t,
          params: e
        })), this.emit(t, e);
      }, e.prototype.buildTimelineMessage = function (t) {
        return r.extend({
          cid: this.id
        }, t);
      }, e;
    }(s["default"]);

    e.__esModule = !0, e["default"] = u;
  }, function (t, e, n) {
    "use strict";

    var i = n(9),
        o = n(24),
        r = function () {
      function t(t) {
        this.callbacks = new o["default"](), this.global_callbacks = [], this.failThrough = t;
      }

      return t.prototype.bind = function (t, e, n) {
        return this.callbacks.add(t, e, n), this;
      }, t.prototype.bind_global = function (t) {
        return this.global_callbacks.push(t), this;
      }, t.prototype.unbind = function (t, e, n) {
        return this.callbacks.remove(t, e, n), this;
      }, t.prototype.unbind_global = function (t) {
        return t ? (this.global_callbacks = i.filter(this.global_callbacks || [], function (e) {
          return e !== t;
        }), this) : (this.global_callbacks = [], this);
      }, t.prototype.unbind_all = function () {
        return this.unbind(), this.unbind_global(), this;
      }, t.prototype.emit = function (t, e) {
        var n;

        for (n = 0; n < this.global_callbacks.length; n++) {
          this.global_callbacks[n](t, e);
        }

        var i = this.callbacks.get(t);
        if (i && i.length > 0) for (n = 0; n < i.length; n++) {
          i[n].fn.call(i[n].context || window, e);
        } else this.failThrough && this.failThrough(t, e);
        return this;
      }, t;
    }();

    e.__esModule = !0, e["default"] = r;
  }, function (t, e, n) {
    "use strict";

    function i(t) {
      return "_" + t;
    }

    var o = n(9),
        r = function () {
      function t() {
        this._callbacks = {};
      }

      return t.prototype.get = function (t) {
        return this._callbacks[i(t)];
      }, t.prototype.add = function (t, e, n) {
        var o = i(t);
        this._callbacks[o] = this._callbacks[o] || [], this._callbacks[o].push({
          fn: e,
          context: n
        });
      }, t.prototype.remove = function (t, e, n) {
        if (!t && !e && !n) return void (this._callbacks = {});
        var r = t ? [i(t)] : o.keys(this._callbacks);
        e || n ? this.removeCallback(r, e, n) : this.removeAllCallbacks(r);
      }, t.prototype.removeCallback = function (t, e, n) {
        o.apply(t, function (t) {
          this._callbacks[t] = o.filter(this._callbacks[t] || [], function (t) {
            return e && e !== t.fn || n && n !== t.context;
          }), 0 === this._callbacks[t].length && delete this._callbacks[t];
        }, this);
      }, t.prototype.removeAllCallbacks = function (t) {
        o.apply(t, function (t) {
          delete this._callbacks[t];
        }, this);
      }, t;
    }();

    e.__esModule = !0, e["default"] = r;
  }, function (t, e, n) {
    "use strict";

    var i = this && this.__extends || function (t, e) {
      function n() {
        this.constructor = t;
      }

      for (var i in e) {
        e.hasOwnProperty(i) && (t[i] = e[i]);
      }

      t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
    },
        o = n(23),
        r = function (t) {
      function e() {
        t.call(this);
        var e = this;
        void 0 !== window.addEventListener && (window.addEventListener("online", function () {
          e.emit("online");
        }, !1), window.addEventListener("offline", function () {
          e.emit("offline");
        }, !1));
      }

      return i(e, t), e.prototype.isOnline = function () {
        return void 0 === window.navigator.onLine || window.navigator.onLine;
      }, e;
    }(o["default"]);

    e.NetInfo = r, e.Network = new r();
  }, function (t, e) {
    "use strict";

    var n = function n(t) {
      var e;
      return e = t.encrypted ? [":best_connected_ever", ":ws_loop", [":delayed", 2e3, [":http_fallback_loop"]]] : [":best_connected_ever", ":ws_loop", [":delayed", 2e3, [":wss_loop"]], [":delayed", 5e3, [":http_fallback_loop"]]], [[":def", "ws_options", {
        hostUnencrypted: t.wsHost + ":" + t.wsPort,
        hostEncrypted: t.wsHost + ":" + t.wssPort
      }], [":def", "wss_options", [":extend", ":ws_options", {
        encrypted: !0
      }]], [":def", "sockjs_options", {
        hostUnencrypted: t.httpHost + ":" + t.httpPort,
        hostEncrypted: t.httpHost + ":" + t.httpsPort,
        httpPath: t.httpPath
      }], [":def", "timeouts", {
        loop: !0,
        timeout: 15e3,
        timeoutLimit: 6e4
      }], [":def", "ws_manager", [":transport_manager", {
        lives: 2,
        minPingDelay: 1e4,
        maxPingDelay: t.activity_timeout
      }]], [":def", "streaming_manager", [":transport_manager", {
        lives: 2,
        minPingDelay: 1e4,
        maxPingDelay: t.activity_timeout
      }]], [":def_transport", "ws", "ws", 3, ":ws_options", ":ws_manager"], [":def_transport", "wss", "ws", 3, ":wss_options", ":ws_manager"], [":def_transport", "sockjs", "sockjs", 1, ":sockjs_options"], [":def_transport", "xhr_streaming", "xhr_streaming", 1, ":sockjs_options", ":streaming_manager"], [":def_transport", "xdr_streaming", "xdr_streaming", 1, ":sockjs_options", ":streaming_manager"], [":def_transport", "xhr_polling", "xhr_polling", 1, ":sockjs_options"], [":def_transport", "xdr_polling", "xdr_polling", 1, ":sockjs_options"], [":def", "ws_loop", [":sequential", ":timeouts", ":ws"]], [":def", "wss_loop", [":sequential", ":timeouts", ":wss"]], [":def", "sockjs_loop", [":sequential", ":timeouts", ":sockjs"]], [":def", "streaming_loop", [":sequential", ":timeouts", [":if", [":is_supported", ":xhr_streaming"], ":xhr_streaming", ":xdr_streaming"]]], [":def", "polling_loop", [":sequential", ":timeouts", [":if", [":is_supported", ":xhr_polling"], ":xhr_polling", ":xdr_polling"]]], [":def", "http_loop", [":if", [":is_supported", ":streaming_loop"], [":best_connected_ever", ":streaming_loop", [":delayed", 4e3, [":polling_loop"]]], [":polling_loop"]]], [":def", "http_fallback_loop", [":if", [":is_supported", ":http_loop"], [":http_loop"], [":sockjs_loop"]]], [":def", "strategy", [":cached", 18e5, [":first_connected", [":if", [":is_supported", ":ws"], e, ":http_fallback_loop"]]]]];
    };

    e.__esModule = !0, e["default"] = n;
  }, function (t, e, n) {
    "use strict";

    function i() {
      var t = this;
      t.timeline.info(t.buildTimelineMessage({
        transport: t.name + (t.options.encrypted ? "s" : "")
      })), t.hooks.isInitialized() ? t.changeState("initialized") : t.hooks.file ? (t.changeState("initializing"), o.Dependencies.load(t.hooks.file, {
        encrypted: t.options.encrypted
      }, function (e, n) {
        t.hooks.isInitialized() ? (t.changeState("initialized"), n(!0)) : (e && t.onError(e), t.onClose(), n(!1));
      })) : t.onClose();
    }

    var o = n(3);
    e.__esModule = !0, e["default"] = i;
  }, function (t, e, n) {
    "use strict";

    var i = n(29),
        o = n(31);
    o["default"].createXDR = function (t, e) {
      return this.createRequest(i["default"], t, e);
    }, e.__esModule = !0, e["default"] = o["default"];
  }, function (t, e, n) {
    "use strict";

    var i = n(30),
        o = {
      getRequest: function getRequest(t) {
        var e = new window.XDomainRequest();
        return e.ontimeout = function () {
          t.emit("error", new i.RequestTimedOut()), t.close();
        }, e.onerror = function (e) {
          t.emit("error", e), t.close();
        }, e.onprogress = function () {
          e.responseText && e.responseText.length > 0 && t.onChunk(200, e.responseText);
        }, e.onload = function () {
          e.responseText && e.responseText.length > 0 && t.onChunk(200, e.responseText), t.emit("finished", 200), t.close();
        }, e;
      },
      abortRequest: function abortRequest(t) {
        t.ontimeout = t.onerror = t.onprogress = t.onload = null, t.abort();
      }
    };
    e.__esModule = !0, e["default"] = o;
  }, function (t, e) {
    "use strict";

    var n = this && this.__extends || function (t, e) {
      function n() {
        this.constructor = t;
      }

      for (var i in e) {
        e.hasOwnProperty(i) && (t[i] = e[i]);
      }

      t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
    },
        i = function (t) {
      function e() {
        t.apply(this, arguments);
      }

      return n(e, t), e;
    }(Error);

    e.BadEventName = i;

    var o = function (t) {
      function e() {
        t.apply(this, arguments);
      }

      return n(e, t), e;
    }(Error);

    e.RequestTimedOut = o;

    var r = function (t) {
      function e() {
        t.apply(this, arguments);
      }

      return n(e, t), e;
    }(Error);

    e.TransportPriorityTooLow = r;

    var s = function (t) {
      function e() {
        t.apply(this, arguments);
      }

      return n(e, t), e;
    }(Error);

    e.TransportClosed = s;

    var a = function (t) {
      function e() {
        t.apply(this, arguments);
      }

      return n(e, t), e;
    }(Error);

    e.UnsupportedTransport = a;

    var c = function (t) {
      function e() {
        t.apply(this, arguments);
      }

      return n(e, t), e;
    }(Error);

    e.UnsupportedStrategy = c;
  }, function (t, e, n) {
    "use strict";

    var i = n(32),
        o = n(33),
        r = n(35),
        s = n(36),
        a = n(37),
        c = {
      createStreamingSocket: function createStreamingSocket(t) {
        return this.createSocket(r["default"], t);
      },
      createPollingSocket: function createPollingSocket(t) {
        return this.createSocket(s["default"], t);
      },
      createSocket: function createSocket(t, e) {
        return new o["default"](t, e);
      },
      createXHR: function createXHR(t, e) {
        return this.createRequest(a["default"], t, e);
      },
      createRequest: function createRequest(t, e, n) {
        return new i["default"](t, e, n);
      }
    };
    e.__esModule = !0, e["default"] = c;
  }, function (t, e, n) {
    "use strict";

    var i = this && this.__extends || function (t, e) {
      function n() {
        this.constructor = t;
      }

      for (var i in e) {
        e.hasOwnProperty(i) && (t[i] = e[i]);
      }

      t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
    },
        o = n(2),
        r = n(23),
        s = 262144,
        a = function (t) {
      function e(e, n, i) {
        t.call(this), this.hooks = e, this.method = n, this.url = i;
      }

      return i(e, t), e.prototype.start = function (t) {
        var e = this;
        this.position = 0, this.xhr = this.hooks.getRequest(this), this.unloader = function () {
          e.close();
        }, o["default"].addUnloadListener(this.unloader), this.xhr.open(this.method, this.url, !0), this.xhr.setRequestHeader && this.xhr.setRequestHeader("Content-Type", "application/json"), this.xhr.send(t);
      }, e.prototype.close = function () {
        this.unloader && (o["default"].removeUnloadListener(this.unloader), this.unloader = null), this.xhr && (this.hooks.abortRequest(this.xhr), this.xhr = null);
      }, e.prototype.onChunk = function (t, e) {
        for (;;) {
          var n = this.advanceBuffer(e);
          if (!n) break;
          this.emit("chunk", {
            status: t,
            data: n
          });
        }

        this.isBufferTooLong(e) && this.emit("buffer_too_long");
      }, e.prototype.advanceBuffer = function (t) {
        var e = t.slice(this.position),
            n = e.indexOf("\n");
        return n !== -1 ? (this.position += n + 1, e.slice(0, n)) : null;
      }, e.prototype.isBufferTooLong = function (t) {
        return this.position === t.length && t.length > s;
      }, e;
    }(r["default"]);

    e.__esModule = !0, e["default"] = a;
  }, function (t, e, n) {
    "use strict";

    function i(t) {
      var e = /([^\?]*)\/*(\??.*)/.exec(t);
      return {
        base: e[1],
        queryString: e[2]
      };
    }

    function o(t, e) {
      return t.base + "/" + e + "/xhr_send";
    }

    function r(t) {
      var e = t.indexOf("?") === -1 ? "?" : "&";
      return t + e + "t=" + +new Date() + "&n=" + p++;
    }

    function s(t, e) {
      var n = /(https?:\/\/)([^\/:]+)((\/|:)?.*)/.exec(t);
      return n[1] + e + n[3];
    }

    function a(t) {
      return Math.floor(Math.random() * t);
    }

    function c(t) {
      for (var e = [], n = 0; n < t; n++) {
        e.push(a(32).toString(32));
      }

      return e.join("");
    }

    var u = n(34),
        l = n(11),
        h = n(2),
        p = 1,
        f = function () {
      function t(t, e) {
        this.hooks = t, this.session = a(1e3) + "/" + c(8), this.location = i(e), this.readyState = u["default"].CONNECTING, this.openStream();
      }

      return t.prototype.send = function (t) {
        return this.sendRaw(JSON.stringify([t]));
      }, t.prototype.ping = function () {
        this.hooks.sendHeartbeat(this);
      }, t.prototype.close = function (t, e) {
        this.onClose(t, e, !0);
      }, t.prototype.sendRaw = function (t) {
        if (this.readyState !== u["default"].OPEN) return !1;

        try {
          return h["default"].createSocketRequest("POST", r(o(this.location, this.session))).start(t), !0;
        } catch (e) {
          return !1;
        }
      }, t.prototype.reconnect = function () {
        this.closeStream(), this.openStream();
      }, t.prototype.onClose = function (t, e, n) {
        this.closeStream(), this.readyState = u["default"].CLOSED, this.onclose && this.onclose({
          code: t,
          reason: e,
          wasClean: n
        });
      }, t.prototype.onChunk = function (t) {
        if (200 === t.status) {
          this.readyState === u["default"].OPEN && this.onActivity();
          var e,
              n = t.data.slice(0, 1);

          switch (n) {
            case "o":
              e = JSON.parse(t.data.slice(1) || "{}"), this.onOpen(e);
              break;

            case "a":
              e = JSON.parse(t.data.slice(1) || "[]");

              for (var i = 0; i < e.length; i++) {
                this.onEvent(e[i]);
              }

              break;

            case "m":
              e = JSON.parse(t.data.slice(1) || "null"), this.onEvent(e);
              break;

            case "h":
              this.hooks.onHeartbeat(this);
              break;

            case "c":
              e = JSON.parse(t.data.slice(1) || "[]"), this.onClose(e[0], e[1], !0);
          }
        }
      }, t.prototype.onOpen = function (t) {
        this.readyState === u["default"].CONNECTING ? (t && t.hostname && (this.location.base = s(this.location.base, t.hostname)), this.readyState = u["default"].OPEN, this.onopen && this.onopen()) : this.onClose(1006, "Server lost session", !0);
      }, t.prototype.onEvent = function (t) {
        this.readyState === u["default"].OPEN && this.onmessage && this.onmessage({
          data: t
        });
      }, t.prototype.onActivity = function () {
        this.onactivity && this.onactivity();
      }, t.prototype.onError = function (t) {
        this.onerror && this.onerror(t);
      }, t.prototype.openStream = function () {
        var t = this;
        this.stream = h["default"].createSocketRequest("POST", r(this.hooks.getReceiveURL(this.location, this.session))), this.stream.bind("chunk", function (e) {
          t.onChunk(e);
        }), this.stream.bind("finished", function (e) {
          t.hooks.onFinished(t, e);
        }), this.stream.bind("buffer_too_long", function () {
          t.reconnect();
        });

        try {
          this.stream.start();
        } catch (e) {
          l["default"].defer(function () {
            t.onError(e), t.onClose(1006, "Could not start streaming", !1);
          });
        }
      }, t.prototype.closeStream = function () {
        this.stream && (this.stream.unbind_all(), this.stream.close(), this.stream = null);
      }, t;
    }();

    e.__esModule = !0, e["default"] = f;
  }, function (t, e) {
    "use strict";

    var n;
    !function (t) {
      t[t.CONNECTING = 0] = "CONNECTING", t[t.OPEN = 1] = "OPEN", t[t.CLOSED = 3] = "CLOSED";
    }(n || (n = {})), e.__esModule = !0, e["default"] = n;
  }, function (t, e) {
    "use strict";

    var n = {
      getReceiveURL: function getReceiveURL(t, e) {
        return t.base + "/" + e + "/xhr_streaming" + t.queryString;
      },
      onHeartbeat: function onHeartbeat(t) {
        t.sendRaw("[]");
      },
      sendHeartbeat: function sendHeartbeat(t) {
        t.sendRaw("[]");
      },
      onFinished: function onFinished(t, e) {
        t.onClose(1006, "Connection interrupted (" + e + ")", !1);
      }
    };
    e.__esModule = !0, e["default"] = n;
  }, function (t, e) {
    "use strict";

    var n = {
      getReceiveURL: function getReceiveURL(t, e) {
        return t.base + "/" + e + "/xhr" + t.queryString;
      },
      onHeartbeat: function onHeartbeat() {},
      sendHeartbeat: function sendHeartbeat(t) {
        t.sendRaw("[]");
      },
      onFinished: function onFinished(t, e) {
        200 === e ? t.reconnect() : t.onClose(1006, "Connection interrupted (" + e + ")", !1);
      }
    };
    e.__esModule = !0, e["default"] = n;
  }, function (t, e, n) {
    "use strict";

    var i = n(2),
        o = {
      getRequest: function getRequest(t) {
        var e = i["default"].getXHRAPI(),
            n = new e();
        return n.onreadystatechange = n.onprogress = function () {
          switch (n.readyState) {
            case 3:
              n.responseText && n.responseText.length > 0 && t.onChunk(n.status, n.responseText);
              break;

            case 4:
              n.responseText && n.responseText.length > 0 && t.onChunk(n.status, n.responseText), t.emit("finished", n.status), t.close();
          }
        }, n;
      },
      abortRequest: function abortRequest(t) {
        t.onreadystatechange = null, t.abort();
      }
    };
    e.__esModule = !0, e["default"] = o;
  }, function (t, e, n) {
    "use strict";

    var i = n(9),
        o = n(11),
        r = n(39),
        s = function () {
      function t(t, e, n) {
        this.key = t, this.session = e, this.events = [], this.options = n || {}, this.sent = 0, this.uniqueID = 0;
      }

      return t.prototype.log = function (t, e) {
        t <= this.options.level && (this.events.push(i.extend({}, e, {
          timestamp: o["default"].now()
        })), this.options.limit && this.events.length > this.options.limit && this.events.shift());
      }, t.prototype.error = function (t) {
        this.log(r["default"].ERROR, t);
      }, t.prototype.info = function (t) {
        this.log(r["default"].INFO, t);
      }, t.prototype.debug = function (t) {
        this.log(r["default"].DEBUG, t);
      }, t.prototype.isEmpty = function () {
        return 0 === this.events.length;
      }, t.prototype.send = function (t, e) {
        var n = this,
            o = i.extend({
          session: this.session,
          bundle: this.sent + 1,
          key: this.key,
          lib: "js",
          version: this.options.version,
          cluster: this.options.cluster,
          features: this.options.features,
          timeline: this.events
        }, this.options.params);
        return this.events = [], t(o, function (t, i) {
          t || n.sent++, e && e(t, i);
        }), !0;
      }, t.prototype.generateUniqueID = function () {
        return this.uniqueID++, this.uniqueID;
      }, t;
    }();

    e.__esModule = !0, e["default"] = s;
  }, function (t, e) {
    "use strict";

    var n;
    !function (t) {
      t[t.ERROR = 3] = "ERROR", t[t.INFO = 6] = "INFO", t[t.DEBUG = 7] = "DEBUG";
    }(n || (n = {})), e.__esModule = !0, e["default"] = n;
  }, function (t, e, n) {
    "use strict";

    function i(t) {
      return function (e) {
        return [t.apply(this, arguments), e];
      };
    }

    function o(t) {
      return "string" == typeof t && ":" === t.charAt(0);
    }

    function r(t, e) {
      return e[t.slice(1)];
    }

    function s(t, e) {
      if (0 === t.length) return [[], e];
      var n = u(t[0], e),
          i = s(t.slice(1), n[1]);
      return [[n[0]].concat(i[0]), i[1]];
    }

    function a(t, e) {
      if (!o(t)) return [t, e];
      var n = r(t, e);
      if (void 0 === n) throw "Undefined symbol " + t;
      return [n, e];
    }

    function c(t, e) {
      if (o(t[0])) {
        var n = r(t[0], e);

        if (t.length > 1) {
          if ("function" != typeof n) throw "Calling non-function " + t[0];
          var i = [l.extend({}, e)].concat(l.map(t.slice(1), function (t) {
            return u(t, l.extend({}, e))[0];
          }));
          return n.apply(this, i);
        }

        return [n, e];
      }

      return s(t, e);
    }

    function u(t, e) {
      return "string" == typeof t ? a(t, e) : "object" == _typeof(t) && t instanceof Array && t.length > 0 ? c(t, e) : [t, e];
    }

    var l = n(9),
        h = n(11),
        p = n(41),
        f = n(30),
        d = n(55),
        y = n(56),
        v = n(57),
        g = n(58),
        m = n(59),
        b = n(60),
        _ = n(61),
        w = n(2),
        k = w["default"].Transports;

    e.build = function (t, e) {
      var n = l.extend({}, C, e);
      return u(t, n)[1].strategy;
    };

    var S = {
      isSupported: function isSupported() {
        return !1;
      },
      connect: function connect(t, e) {
        var n = h["default"].defer(function () {
          e(new f.UnsupportedStrategy());
        });
        return {
          abort: function abort() {
            n.ensureAborted();
          },
          forceMinPriority: function forceMinPriority() {}
        };
      }
    },
        C = {
      extend: function extend(t, e, n) {
        return [l.extend({}, e, n), t];
      },
      def: function def(t, e, n) {
        if (void 0 !== t[e]) throw "Redefining symbol " + e;
        return t[e] = n, [void 0, t];
      },
      def_transport: function def_transport(t, e, n, i, o, r) {
        var s = k[n];
        if (!s) throw new f.UnsupportedTransport(n);
        var a,
            c = !(t.enabledTransports && l.arrayIndexOf(t.enabledTransports, e) === -1 || t.disabledTransports && l.arrayIndexOf(t.disabledTransports, e) !== -1);
        a = c ? new d["default"](e, i, r ? r.getAssistant(s) : s, l.extend({
          key: t.key,
          encrypted: t.encrypted,
          timeline: t.timeline,
          ignoreNullOrigin: t.ignoreNullOrigin
        }, o)) : S;
        var u = t.def(t, e, a)[1];
        return u.Transports = t.Transports || {}, u.Transports[e] = a, [void 0, u];
      },
      transport_manager: i(function (t, e) {
        return new p["default"](e);
      }),
      sequential: i(function (t, e) {
        var n = Array.prototype.slice.call(arguments, 2);
        return new y["default"](n, e);
      }),
      cached: i(function (t, e, n) {
        return new g["default"](n, t.Transports, {
          ttl: e,
          timeline: t.timeline,
          encrypted: t.encrypted
        });
      }),
      first_connected: i(function (t, e) {
        return new _["default"](e);
      }),
      best_connected_ever: i(function () {
        var t = Array.prototype.slice.call(arguments, 1);
        return new v["default"](t);
      }),
      delayed: i(function (t, e, n) {
        return new m["default"](n, {
          delay: e
        });
      }),
      "if": i(function (t, e, n, i) {
        return new b["default"](e, n, i);
      }),
      is_supported: i(function (t, e) {
        return function () {
          return e.isSupported();
        };
      })
    };
  }, function (t, e, n) {
    "use strict";

    var i = n(42),
        o = function () {
      function t(t) {
        this.options = t || {}, this.livesLeft = this.options.lives || 1 / 0;
      }

      return t.prototype.getAssistant = function (t) {
        return i["default"].createAssistantToTheTransportManager(this, t, {
          minPingDelay: this.options.minPingDelay,
          maxPingDelay: this.options.maxPingDelay
        });
      }, t.prototype.isAlive = function () {
        return this.livesLeft > 0;
      }, t.prototype.reportDeath = function () {
        this.livesLeft -= 1;
      }, t;
    }();

    e.__esModule = !0, e["default"] = o;
  }, function (t, e, n) {
    "use strict";

    var i = n(43),
        o = n(44),
        r = n(47),
        s = n(48),
        a = n(49),
        c = n(50),
        u = n(51),
        l = n(53),
        h = n(54),
        p = {
      createChannels: function createChannels() {
        return new h["default"]();
      },
      createConnectionManager: function createConnectionManager(t, e) {
        return new l["default"](t, e);
      },
      createChannel: function createChannel(t, e) {
        return new u["default"](t, e);
      },
      createPrivateChannel: function createPrivateChannel(t, e) {
        return new c["default"](t, e);
      },
      createPresenceChannel: function createPresenceChannel(t, e) {
        return new a["default"](t, e);
      },
      createTimelineSender: function createTimelineSender(t, e) {
        return new s["default"](t, e);
      },
      createAuthorizer: function createAuthorizer(t, e) {
        return new r["default"](t, e);
      },
      createHandshake: function createHandshake(t, e) {
        return new o["default"](t, e);
      },
      createAssistantToTheTransportManager: function createAssistantToTheTransportManager(t, e, n) {
        return new i["default"](t, e, n);
      }
    };
    e.__esModule = !0, e["default"] = p;
  }, function (t, e, n) {
    "use strict";

    var i = n(11),
        o = n(9),
        r = function () {
      function t(t, e, n) {
        this.manager = t, this.transport = e, this.minPingDelay = n.minPingDelay, this.maxPingDelay = n.maxPingDelay, this.pingDelay = void 0;
      }

      return t.prototype.createConnection = function (t, e, n, r) {
        var s = this;
        r = o.extend({}, r, {
          activityTimeout: this.pingDelay
        });

        var a = this.transport.createConnection(t, e, n, r),
            c = null,
            u = function u() {
          a.unbind("open", u), a.bind("closed", l), c = i["default"].now();
        },
            l = function l(t) {
          if (a.unbind("closed", l), 1002 === t.code || 1003 === t.code) s.manager.reportDeath();else if (!t.wasClean && c) {
            var e = i["default"].now() - c;
            e < 2 * s.maxPingDelay && (s.manager.reportDeath(), s.pingDelay = Math.max(e / 2, s.minPingDelay));
          }
        };

        return a.bind("open", u), a;
      }, t.prototype.isSupported = function (t) {
        return this.manager.isAlive() && this.transport.isSupported(t);
      }, t;
    }();

    e.__esModule = !0, e["default"] = r;
  }, function (t, e, n) {
    "use strict";

    var i = n(9),
        o = n(45),
        r = n(46),
        s = function () {
      function t(t, e) {
        this.transport = t, this.callback = e, this.bindListeners();
      }

      return t.prototype.close = function () {
        this.unbindListeners(), this.transport.close();
      }, t.prototype.bindListeners = function () {
        var t = this;
        this.onMessage = function (e) {
          t.unbindListeners();
          var n;

          try {
            n = o.processHandshake(e);
          } catch (i) {
            return t.finish("error", {
              error: i
            }), void t.transport.close();
          }

          "connected" === n.action ? t.finish("connected", {
            connection: new r["default"](n.id, t.transport),
            activityTimeout: n.activityTimeout
          }) : (t.finish(n.action, {
            error: n.error
          }), t.transport.close());
        }, this.onClosed = function (e) {
          t.unbindListeners();
          var n = o.getCloseAction(e) || "backoff",
              i = o.getCloseError(e);
          t.finish(n, {
            error: i
          });
        }, this.transport.bind("message", this.onMessage), this.transport.bind("closed", this.onClosed);
      }, t.prototype.unbindListeners = function () {
        this.transport.unbind("message", this.onMessage), this.transport.unbind("closed", this.onClosed);
      }, t.prototype.finish = function (t, e) {
        this.callback(i.extend({
          transport: this.transport,
          action: t
        }, e));
      }, t;
    }();

    e.__esModule = !0, e["default"] = s;
  }, function (t, e) {
    "use strict";

    e.decodeMessage = function (t) {
      try {
        var e = JSON.parse(t.data);
        if ("string" == typeof e.data) try {
          e.data = JSON.parse(e.data);
        } catch (n) {
          if (!(n instanceof SyntaxError)) throw n;
        }
        return e;
      } catch (n) {
        throw {
          type: "MessageParseError",
          error: n,
          data: t.data
        };
      }
    }, e.encodeMessage = function (t) {
      return JSON.stringify(t);
    }, e.processHandshake = function (t) {
      if (t = e.decodeMessage(t), "pusher:connection_established" === t.event) {
        if (!t.data.activity_timeout) throw "No activity timeout specified in handshake";
        return {
          action: "connected",
          id: t.data.socket_id,
          activityTimeout: 1e3 * t.data.activity_timeout
        };
      }

      if ("pusher:error" === t.event) return {
        action: this.getCloseAction(t.data),
        error: this.getCloseError(t.data)
      };
      throw "Invalid handshake";
    }, e.getCloseAction = function (t) {
      return t.code < 4e3 ? t.code >= 1002 && t.code <= 1004 ? "backoff" : null : 4e3 === t.code ? "ssl_only" : t.code < 4100 ? "refused" : t.code < 4200 ? "backoff" : t.code < 4300 ? "retry" : "refused";
    }, e.getCloseError = function (t) {
      return 1e3 !== t.code && 1001 !== t.code ? {
        type: "PusherError",
        data: {
          code: t.code,
          message: t.reason || t.message
        }
      } : null;
    };
  }, function (t, e, n) {
    "use strict";

    var i = this && this.__extends || function (t, e) {
      function n() {
        this.constructor = t;
      }

      for (var i in e) {
        e.hasOwnProperty(i) && (t[i] = e[i]);
      }

      t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
    },
        o = n(9),
        r = n(23),
        s = n(45),
        a = n(8),
        c = function (t) {
      function e(e, n) {
        t.call(this), this.id = e, this.transport = n, this.activityTimeout = n.activityTimeout, this.bindListeners();
      }

      return i(e, t), e.prototype.handlesActivityChecks = function () {
        return this.transport.handlesActivityChecks();
      }, e.prototype.send = function (t) {
        return this.transport.send(t);
      }, e.prototype.send_event = function (t, e, n) {
        var i = {
          event: t,
          data: e
        };
        return n && (i.channel = n), a["default"].debug("Event sent", i), this.send(s.encodeMessage(i));
      }, e.prototype.ping = function () {
        this.transport.supportsPing() ? this.transport.ping() : this.send_event("pusher:ping", {});
      }, e.prototype.close = function () {
        this.transport.close();
      }, e.prototype.bindListeners = function () {
        var t = this,
            e = {
          message: function message(e) {
            var n;

            try {
              n = s.decodeMessage(e);
            } catch (i) {
              t.emit("error", {
                type: "MessageParseError",
                error: i,
                data: e.data
              });
            }

            if (void 0 !== n) {
              switch (a["default"].debug("Event recd", n), n.event) {
                case "pusher:error":
                  t.emit("error", {
                    type: "PusherError",
                    data: n.data
                  });
                  break;

                case "pusher:ping":
                  t.emit("ping");
                  break;

                case "pusher:pong":
                  t.emit("pong");
              }

              t.emit("message", n);
            }
          },
          activity: function activity() {
            t.emit("activity");
          },
          error: function error(e) {
            t.emit("error", {
              type: "WebSocketError",
              error: e
            });
          },
          closed: function closed(e) {
            n(), e && e.code && t.handleCloseEvent(e), t.transport = null, t.emit("closed");
          }
        },
            n = function n() {
          o.objectApply(e, function (e, n) {
            t.transport.unbind(n, e);
          });
        };

        o.objectApply(e, function (e, n) {
          t.transport.bind(n, e);
        });
      }, e.prototype.handleCloseEvent = function (t) {
        var e = s.getCloseAction(t),
            n = s.getCloseError(t);
        n && this.emit("error", n), e && this.emit(e);
      }, e;
    }(r["default"]);

    e.__esModule = !0, e["default"] = c;
  }, function (t, e, n) {
    "use strict";

    var i = n(2),
        o = function () {
      function t(t, e) {
        this.channel = t;
        var n = e.authTransport;
        if ("undefined" == typeof i["default"].getAuthorizers()[n]) throw "'" + n + "' is not a recognized auth transport";
        this.type = n, this.options = e, this.authOptions = (e || {}).auth || {};
      }

      return t.prototype.composeQuery = function (t) {
        var e = "socket_id=" + encodeURIComponent(t) + "&channel_name=" + encodeURIComponent(this.channel.name);

        for (var n in this.authOptions.params) {
          e += "&" + encodeURIComponent(n) + "=" + encodeURIComponent(this.authOptions.params[n]);
        }

        return e;
      }, t.prototype.authorize = function (e, n) {
        return t.authorizers = t.authorizers || i["default"].getAuthorizers(), t.authorizers[this.type].call(this, i["default"], e, n);
      }, t;
    }();

    e.__esModule = !0, e["default"] = o;
  }, function (t, e, n) {
    "use strict";

    var i = n(2),
        o = function () {
      function t(t, e) {
        this.timeline = t, this.options = e || {};
      }

      return t.prototype.send = function (t, e) {
        this.timeline.isEmpty() || this.timeline.send(i["default"].TimelineTransport.getAgent(this, t), e);
      }, t;
    }();

    e.__esModule = !0, e["default"] = o;
  }, function (t, e, n) {
    "use strict";

    var i = this && this.__extends || function (t, e) {
      function n() {
        this.constructor = t;
      }

      for (var i in e) {
        e.hasOwnProperty(i) && (t[i] = e[i]);
      }

      t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
    },
        o = n(50),
        r = n(8),
        s = n(52),
        a = function (t) {
      function e(e, n) {
        t.call(this, e, n), this.members = new s["default"]();
      }

      return i(e, t), e.prototype.authorize = function (e, n) {
        var i = this;
        t.prototype.authorize.call(this, e, function (t, e) {
          if (!t) {
            if (void 0 === e.channel_data) return r["default"].warn("Invalid auth response for channel '" + i.name + "', expected 'channel_data' field"), void n("Invalid auth response");
            var o = JSON.parse(e.channel_data);
            i.members.setMyID(o.user_id);
          }

          n(t, e);
        });
      }, e.prototype.handleEvent = function (t, e) {
        switch (t) {
          case "pusher_internal:subscription_succeeded":
            this.subscriptionPending = !1, this.subscribed = !0, this.subscriptionCancelled ? this.pusher.unsubscribe(this.name) : (this.members.onSubscription(e), this.emit("pusher:subscription_succeeded", this.members));
            break;

          case "pusher_internal:member_added":
            var n = this.members.addMember(e);
            this.emit("pusher:member_added", n);
            break;

          case "pusher_internal:member_removed":
            var i = this.members.removeMember(e);
            i && this.emit("pusher:member_removed", i);
            break;

          default:
            o["default"].prototype.handleEvent.call(this, t, e);
        }
      }, e.prototype.disconnect = function () {
        this.members.reset(), t.prototype.disconnect.call(this);
      }, e;
    }(o["default"]);

    e.__esModule = !0, e["default"] = a;
  }, function (t, e, n) {
    "use strict";

    var i = this && this.__extends || function (t, e) {
      function n() {
        this.constructor = t;
      }

      for (var i in e) {
        e.hasOwnProperty(i) && (t[i] = e[i]);
      }

      t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
    },
        o = n(42),
        r = n(51),
        s = function (t) {
      function e() {
        t.apply(this, arguments);
      }

      return i(e, t), e.prototype.authorize = function (t, e) {
        var n = o["default"].createAuthorizer(this, this.pusher.config);
        return n.authorize(t, e);
      }, e;
    }(r["default"]);

    e.__esModule = !0, e["default"] = s;
  }, function (t, e, n) {
    "use strict";

    var i = this && this.__extends || function (t, e) {
      function n() {
        this.constructor = t;
      }

      for (var i in e) {
        e.hasOwnProperty(i) && (t[i] = e[i]);
      }

      t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
    },
        o = n(23),
        r = n(30),
        s = n(8),
        a = function (t) {
      function e(e, n) {
        t.call(this, function (t, n) {
          s["default"].debug("No callbacks on " + e + " for " + t);
        }), this.name = e, this.pusher = n, this.subscribed = !1, this.subscriptionPending = !1, this.subscriptionCancelled = !1;
      }

      return i(e, t), e.prototype.authorize = function (t, e) {
        return e(!1, {});
      }, e.prototype.trigger = function (t, e) {
        if (0 !== t.indexOf("client-")) throw new r.BadEventName("Event '" + t + "' does not start with 'client-'");
        return this.pusher.send_event(t, e, this.name);
      }, e.prototype.disconnect = function () {
        this.subscribed = !1;
      }, e.prototype.handleEvent = function (t, e) {
        0 === t.indexOf("pusher_internal:") ? "pusher_internal:subscription_succeeded" === t && (this.subscriptionPending = !1, this.subscribed = !0, this.subscriptionCancelled ? this.pusher.unsubscribe(this.name) : this.emit("pusher:subscription_succeeded", e)) : this.emit(t, e);
      }, e.prototype.subscribe = function () {
        var t = this;
        this.subscribed || (this.subscriptionPending = !0, this.subscriptionCancelled = !1, this.authorize(this.pusher.connection.socket_id, function (e, n) {
          e ? t.handleEvent("pusher:subscription_error", n) : t.pusher.send_event("pusher:subscribe", {
            auth: n.auth,
            channel_data: n.channel_data,
            channel: t.name
          });
        }));
      }, e.prototype.unsubscribe = function () {
        this.subscribed = !1, this.pusher.send_event("pusher:unsubscribe", {
          channel: this.name
        });
      }, e.prototype.cancelSubscription = function () {
        this.subscriptionCancelled = !0;
      }, e.prototype.reinstateSubscription = function () {
        this.subscriptionCancelled = !1;
      }, e;
    }(o["default"]);

    e.__esModule = !0, e["default"] = a;
  }, function (t, e, n) {
    "use strict";

    var i = n(9),
        o = function () {
      function t() {
        this.reset();
      }

      return t.prototype.get = function (t) {
        return Object.prototype.hasOwnProperty.call(this.members, t) ? {
          id: t,
          info: this.members[t]
        } : null;
      }, t.prototype.each = function (t) {
        var e = this;
        i.objectApply(this.members, function (n, i) {
          t(e.get(i));
        });
      }, t.prototype.setMyID = function (t) {
        this.myID = t;
      }, t.prototype.onSubscription = function (t) {
        this.members = t.presence.hash, this.count = t.presence.count, this.me = this.get(this.myID);
      }, t.prototype.addMember = function (t) {
        return null === this.get(t.user_id) && this.count++, this.members[t.user_id] = t.user_info, this.get(t.user_id);
      }, t.prototype.removeMember = function (t) {
        var e = this.get(t.user_id);
        return e && (delete this.members[t.user_id], this.count--), e;
      }, t.prototype.reset = function () {
        this.members = {}, this.count = 0, this.myID = null, this.me = null;
      }, t;
    }();

    e.__esModule = !0, e["default"] = o;
  }, function (t, e, n) {
    "use strict";

    var i = this && this.__extends || function (t, e) {
      function n() {
        this.constructor = t;
      }

      for (var i in e) {
        e.hasOwnProperty(i) && (t[i] = e[i]);
      }

      t.prototype = null === e ? Object.create(e) : (n.prototype = e.prototype, new n());
    },
        o = n(23),
        r = n(12),
        s = n(8),
        a = n(9),
        c = n(2),
        u = function (t) {
      function e(e, n) {
        var i = this;
        t.call(this), this.key = e, this.options = n || {}, this.state = "initialized", this.connection = null, this.encrypted = !!n.encrypted, this.timeline = this.options.timeline, this.connectionCallbacks = this.buildConnectionCallbacks(), this.errorCallbacks = this.buildErrorCallbacks(), this.handshakeCallbacks = this.buildHandshakeCallbacks(this.errorCallbacks);
        var o = c["default"].getNetwork();
        o.bind("online", function () {
          i.timeline.info({
            netinfo: "online"
          }), "connecting" !== i.state && "unavailable" !== i.state || i.retryIn(0);
        }), o.bind("offline", function () {
          i.timeline.info({
            netinfo: "offline"
          }), i.connection && i.sendActivityCheck();
        }), this.updateStrategy();
      }

      return i(e, t), e.prototype.connect = function () {
        if (!this.connection && !this.runner) {
          if (!this.strategy.isSupported()) return void this.updateState("failed");
          this.updateState("connecting"), this.startConnecting(), this.setUnavailableTimer();
        }
      }, e.prototype.send = function (t) {
        return !!this.connection && this.connection.send(t);
      }, e.prototype.send_event = function (t, e, n) {
        return !!this.connection && this.connection.send_event(t, e, n);
      }, e.prototype.disconnect = function () {
        this.disconnectInternally(), this.updateState("disconnected");
      }, e.prototype.isEncrypted = function () {
        return this.encrypted;
      }, e.prototype.startConnecting = function () {
        var t = this,
            e = function e(n, i) {
          n ? t.runner = t.strategy.connect(0, e) : "error" === i.action ? (t.emit("error", {
            type: "HandshakeError",
            error: i.error
          }), t.timeline.error({
            handshakeError: i.error
          })) : (t.abortConnecting(), t.handshakeCallbacks[i.action](i));
        };

        this.runner = this.strategy.connect(0, e);
      }, e.prototype.abortConnecting = function () {
        this.runner && (this.runner.abort(), this.runner = null);
      }, e.prototype.disconnectInternally = function () {
        if (this.abortConnecting(), this.clearRetryTimer(), this.clearUnavailableTimer(), this.connection) {
          var t = this.abandonConnection();
          t.close();
        }
      }, e.prototype.updateStrategy = function () {
        this.strategy = this.options.getStrategy({
          key: this.key,
          timeline: this.timeline,
          encrypted: this.encrypted
        });
      }, e.prototype.retryIn = function (t) {
        var e = this;
        this.timeline.info({
          action: "retry",
          delay: t
        }), t > 0 && this.emit("connecting_in", Math.round(t / 1e3)), this.retryTimer = new r.OneOffTimer(t || 0, function () {
          e.disconnectInternally(), e.connect();
        });
      }, e.prototype.clearRetryTimer = function () {
        this.retryTimer && (this.retryTimer.ensureAborted(), this.retryTimer = null);
      }, e.prototype.setUnavailableTimer = function () {
        var t = this;
        this.unavailableTimer = new r.OneOffTimer(this.options.unavailableTimeout, function () {
          t.updateState("unavailable");
        });
      }, e.prototype.clearUnavailableTimer = function () {
        this.unavailableTimer && this.unavailableTimer.ensureAborted();
      }, e.prototype.sendActivityCheck = function () {
        var t = this;
        this.stopActivityCheck(), this.connection.ping(), this.activityTimer = new r.OneOffTimer(this.options.pongTimeout, function () {
          t.timeline.error({
            pong_timed_out: t.options.pongTimeout
          }), t.retryIn(0);
        });
      }, e.prototype.resetActivityCheck = function () {
        var t = this;
        this.stopActivityCheck(), this.connection.handlesActivityChecks() || (this.activityTimer = new r.OneOffTimer(this.activityTimeout, function () {
          t.sendActivityCheck();
        }));
      }, e.prototype.stopActivityCheck = function () {
        this.activityTimer && this.activityTimer.ensureAborted();
      }, e.prototype.buildConnectionCallbacks = function () {
        var t = this;
        return {
          message: function message(e) {
            t.resetActivityCheck(), t.emit("message", e);
          },
          ping: function ping() {
            t.send_event("pusher:pong", {});
          },
          activity: function activity() {
            t.resetActivityCheck();
          },
          error: function error(e) {
            t.emit("error", {
              type: "WebSocketError",
              error: e
            });
          },
          closed: function closed() {
            t.abandonConnection(), t.shouldRetry() && t.retryIn(1e3);
          }
        };
      }, e.prototype.buildHandshakeCallbacks = function (t) {
        var e = this;
        return a.extend({}, t, {
          connected: function connected(t) {
            e.activityTimeout = Math.min(e.options.activityTimeout, t.activityTimeout, t.connection.activityTimeout || 1 / 0), e.clearUnavailableTimer(), e.setConnection(t.connection), e.socket_id = e.connection.id, e.updateState("connected", {
              socket_id: e.socket_id
            });
          }
        });
      }, e.prototype.buildErrorCallbacks = function () {
        var t = this,
            e = function e(_e) {
          return function (n) {
            n.error && t.emit("error", {
              type: "WebSocketError",
              error: n.error
            }), _e(n);
          };
        };

        return {
          ssl_only: e(function () {
            t.encrypted = !0, t.updateStrategy(), t.retryIn(0);
          }),
          refused: e(function () {
            t.disconnect();
          }),
          backoff: e(function () {
            t.retryIn(1e3);
          }),
          retry: e(function () {
            t.retryIn(0);
          })
        };
      }, e.prototype.setConnection = function (t) {
        this.connection = t;

        for (var e in this.connectionCallbacks) {
          this.connection.bind(e, this.connectionCallbacks[e]);
        }

        this.resetActivityCheck();
      }, e.prototype.abandonConnection = function () {
        if (this.connection) {
          this.stopActivityCheck();

          for (var t in this.connectionCallbacks) {
            this.connection.unbind(t, this.connectionCallbacks[t]);
          }

          var e = this.connection;
          return this.connection = null, e;
        }
      }, e.prototype.updateState = function (t, e) {
        var n = this.state;

        if (this.state = t, n !== t) {
          var i = t;
          "connected" === i && (i += " with new socket ID " + e.socket_id), s["default"].debug("State changed", n + " -> " + i), this.timeline.info({
            state: t,
            params: e
          }), this.emit("state_change", {
            previous: n,
            current: t
          }), this.emit(t, e);
        }
      }, e.prototype.shouldRetry = function () {
        return "connecting" === this.state || "connected" === this.state;
      }, e;
    }(o["default"]);

    e.__esModule = !0, e["default"] = u;
  }, function (t, e, n) {
    "use strict";

    function i(t, e) {
      return 0 === t.indexOf("private-") ? r["default"].createPrivateChannel(t, e) : 0 === t.indexOf("presence-") ? r["default"].createPresenceChannel(t, e) : r["default"].createChannel(t, e);
    }

    var o = n(9),
        r = n(42),
        s = function () {
      function t() {
        this.channels = {};
      }

      return t.prototype.add = function (t, e) {
        return this.channels[t] || (this.channels[t] = i(t, e)), this.channels[t];
      }, t.prototype.all = function () {
        return o.values(this.channels);
      }, t.prototype.find = function (t) {
        return this.channels[t];
      }, t.prototype.remove = function (t) {
        var e = this.channels[t];
        return delete this.channels[t], e;
      }, t.prototype.disconnect = function () {
        o.objectApply(this.channels, function (t) {
          t.disconnect();
        });
      }, t;
    }();

    e.__esModule = !0, e["default"] = s;
  }, function (t, e, n) {
    "use strict";

    function i(t, e) {
      return r["default"].defer(function () {
        e(t);
      }), {
        abort: function abort() {},
        forceMinPriority: function forceMinPriority() {}
      };
    }

    var o = n(42),
        r = n(11),
        s = n(30),
        a = n(9),
        c = function () {
      function t(t, e, n, i) {
        this.name = t, this.priority = e, this.transport = n, this.options = i || {};
      }

      return t.prototype.isSupported = function () {
        return this.transport.isSupported({
          encrypted: this.options.encrypted
        });
      }, t.prototype.connect = function (t, e) {
        var n = this;
        if (!this.isSupported()) return i(new s.UnsupportedStrategy(), e);
        if (this.priority < t) return i(new s.TransportPriorityTooLow(), e);

        var r = !1,
            c = this.transport.createConnection(this.name, this.priority, this.options.key, this.options),
            u = null,
            l = function l() {
          c.unbind("initialized", l), c.connect();
        },
            h = function h() {
          u = o["default"].createHandshake(c, function (t) {
            r = !0, d(), e(null, t);
          });
        },
            p = function p(t) {
          d(), e(t);
        },
            f = function f() {
          d();
          var t;
          t = a.safeJSONStringify(c), e(new s.TransportClosed(t));
        },
            d = function d() {
          c.unbind("initialized", l), c.unbind("open", h), c.unbind("error", p), c.unbind("closed", f);
        };

        return c.bind("initialized", l), c.bind("open", h), c.bind("error", p), c.bind("closed", f), c.initialize(), {
          abort: function abort() {
            r || (d(), u ? u.close() : c.close());
          },
          forceMinPriority: function forceMinPriority(t) {
            r || n.priority < t && (u ? u.close() : c.close());
          }
        };
      }, t;
    }();

    e.__esModule = !0, e["default"] = c;
  }, function (t, e, n) {
    "use strict";

    var i = n(9),
        o = n(11),
        r = n(12),
        s = function () {
      function t(t, e) {
        this.strategies = t, this.loop = Boolean(e.loop), this.failFast = Boolean(e.failFast), this.timeout = e.timeout, this.timeoutLimit = e.timeoutLimit;
      }

      return t.prototype.isSupported = function () {
        return i.any(this.strategies, o["default"].method("isSupported"));
      }, t.prototype.connect = function (t, e) {
        var n = this,
            i = this.strategies,
            o = 0,
            r = this.timeout,
            s = null,
            a = function a(c, u) {
          u ? e(null, u) : (o += 1, n.loop && (o %= i.length), o < i.length ? (r && (r = 2 * r, n.timeoutLimit && (r = Math.min(r, n.timeoutLimit))), s = n.tryStrategy(i[o], t, {
            timeout: r,
            failFast: n.failFast
          }, a)) : e(!0));
        };

        return s = this.tryStrategy(i[o], t, {
          timeout: r,
          failFast: this.failFast
        }, a), {
          abort: function abort() {
            s.abort();
          },
          forceMinPriority: function forceMinPriority(e) {
            t = e, s && s.forceMinPriority(e);
          }
        };
      }, t.prototype.tryStrategy = function (t, e, n, i) {
        var o = null,
            s = null;
        return n.timeout > 0 && (o = new r.OneOffTimer(n.timeout, function () {
          s.abort(), i(!0);
        })), s = t.connect(e, function (t, e) {
          t && o && o.isRunning() && !n.failFast || (o && o.ensureAborted(), i(t, e));
        }), {
          abort: function abort() {
            o && o.ensureAborted(), s.abort();
          },
          forceMinPriority: function forceMinPriority(t) {
            s.forceMinPriority(t);
          }
        };
      }, t;
    }();

    e.__esModule = !0, e["default"] = s;
  }, function (t, e, n) {
    "use strict";

    function i(t, e, n) {
      var i = s.map(t, function (t, i, o, r) {
        return t.connect(e, n(i, r));
      });
      return {
        abort: function abort() {
          s.apply(i, r);
        },
        forceMinPriority: function forceMinPriority(t) {
          s.apply(i, function (e) {
            e.forceMinPriority(t);
          });
        }
      };
    }

    function o(t) {
      return s.all(t, function (t) {
        return Boolean(t.error);
      });
    }

    function r(t) {
      t.error || t.aborted || (t.abort(), t.aborted = !0);
    }

    var s = n(9),
        a = n(11),
        c = function () {
      function t(t) {
        this.strategies = t;
      }

      return t.prototype.isSupported = function () {
        return s.any(this.strategies, a["default"].method("isSupported"));
      }, t.prototype.connect = function (t, e) {
        return i(this.strategies, t, function (t, n) {
          return function (i, r) {
            return n[t].error = i, i ? void (o(n) && e(!0)) : (s.apply(n, function (t) {
              t.forceMinPriority(r.transport.priority);
            }), void e(null, r));
          };
        });
      }, t;
    }();

    e.__esModule = !0, e["default"] = c;
  }, function (t, e, n) {
    "use strict";

    function i(t) {
      return "pusherTransport" + (t ? "Encrypted" : "Unencrypted");
    }

    function o(t) {
      var e = c["default"].getLocalStorage();
      if (e) try {
        var n = e[i(t)];
        if (n) return JSON.parse(n);
      } catch (o) {
        s(t);
      }
      return null;
    }

    function r(t, e, n) {
      var o = c["default"].getLocalStorage();
      if (o) try {
        o[i(t)] = l.safeJSONStringify({
          timestamp: a["default"].now(),
          transport: e,
          latency: n
        });
      } catch (r) {}
    }

    function s(t) {
      var e = c["default"].getLocalStorage();
      if (e) try {
        delete e[i(t)];
      } catch (n) {}
    }

    var a = n(11),
        c = n(2),
        u = n(56),
        l = n(9),
        h = function () {
      function t(t, e, n) {
        this.strategy = t, this.transports = e, this.ttl = n.ttl || 18e5, this.encrypted = n.encrypted, this.timeline = n.timeline;
      }

      return t.prototype.isSupported = function () {
        return this.strategy.isSupported();
      }, t.prototype.connect = function (t, e) {
        var n = this.encrypted,
            i = o(n),
            c = [this.strategy];

        if (i && i.timestamp + this.ttl >= a["default"].now()) {
          var l = this.transports[i.transport];
          l && (this.timeline.info({
            cached: !0,
            transport: i.transport,
            latency: i.latency
          }), c.push(new u["default"]([l], {
            timeout: 2 * i.latency + 1e3,
            failFast: !0
          })));
        }

        var h = a["default"].now(),
            p = c.pop().connect(t, function f(i, o) {
          i ? (s(n), c.length > 0 ? (h = a["default"].now(), p = c.pop().connect(t, f)) : e(i)) : (r(n, o.transport.name, a["default"].now() - h), e(null, o));
        });
        return {
          abort: function abort() {
            p.abort();
          },
          forceMinPriority: function forceMinPriority(e) {
            t = e, p && p.forceMinPriority(e);
          }
        };
      }, t;
    }();

    e.__esModule = !0, e["default"] = h;
  }, function (t, e, n) {
    "use strict";

    var i = n(12),
        o = function () {
      function t(t, e) {
        var n = e.delay;
        this.strategy = t, this.options = {
          delay: n
        };
      }

      return t.prototype.isSupported = function () {
        return this.strategy.isSupported();
      }, t.prototype.connect = function (t, e) {
        var n,
            o = this.strategy,
            r = new i.OneOffTimer(this.options.delay, function () {
          n = o.connect(t, e);
        });
        return {
          abort: function abort() {
            r.ensureAborted(), n && n.abort();
          },
          forceMinPriority: function forceMinPriority(e) {
            t = e, n && n.forceMinPriority(e);
          }
        };
      }, t;
    }();

    e.__esModule = !0, e["default"] = o;
  }, function (t, e) {
    "use strict";

    var n = function () {
      function t(t, e, n) {
        this.test = t, this.trueBranch = e, this.falseBranch = n;
      }

      return t.prototype.isSupported = function () {
        var t = this.test() ? this.trueBranch : this.falseBranch;
        return t.isSupported();
      }, t.prototype.connect = function (t, e) {
        var n = this.test() ? this.trueBranch : this.falseBranch;
        return n.connect(t, e);
      }, t;
    }();

    e.__esModule = !0, e["default"] = n;
  }, function (t, e) {
    "use strict";

    var n = function () {
      function t(t) {
        this.strategy = t;
      }

      return t.prototype.isSupported = function () {
        return this.strategy.isSupported();
      }, t.prototype.connect = function (t, e) {
        var n = this.strategy.connect(t, function (t, i) {
          i && n.abort(), e(t, i);
        });
        return n;
      }, t;
    }();

    e.__esModule = !0, e["default"] = n;
  }, function (t, e, n) {
    "use strict";

    var i = n(5);
    e.getGlobalConfig = function () {
      return {
        wsHost: i["default"].host,
        wsPort: i["default"].ws_port,
        wssPort: i["default"].wss_port,
        httpHost: i["default"].sockjs_host,
        httpPort: i["default"].sockjs_http_port,
        httpsPort: i["default"].sockjs_https_port,
        httpPath: i["default"].sockjs_path,
        statsHost: i["default"].stats_host,
        authEndpoint: i["default"].channel_auth_endpoint,
        authTransport: i["default"].channel_auth_transport,
        activity_timeout: i["default"].activity_timeout,
        pong_timeout: i["default"].pong_timeout,
        unavailable_timeout: i["default"].unavailable_timeout
      };
    }, e.getClusterConfig = function (t) {
      return {
        wsHost: "ws-" + t + ".pusher.com",
        httpHost: "sockjs-" + t + ".pusher.com"
      };
    };
  }]);
});

/***/ }),

/***/ "./resources/assets/admin/js/app/components/sortable.js":
/*!**************************************************************!*\
  !*** ./resources/assets/admin/js/app/components/sortable.js ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var sortablejs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! sortablejs */ "./node_modules/sortablejs/modular/sortable.esm.js");


function initSortable() {
  $('.js-sortable').each(function () {
    var _this = this;

    var reindex = function reindex() {
      var $that = $(_this);
      var data = [];
      $(_this).children().each(function () {
        data.push({
          id: $(this).data('id'),
          order: $(this).index()
        });
      });
      $.post($that.data('url'), {
        data: data
      }, function () {
        return window.showNotification('Список успешно отсортирован');
      });
    };

    sortablejs__WEBPACK_IMPORTED_MODULE_0__["default"].create($(this)[0], {
      onEnd: reindex
    });
  });
}

initSortable();
window.initSortable = initSortable;

/***/ }),

/***/ "./resources/assets/admin/js/app/monitoring.js":
/*!*****************************************************!*\
  !*** ./resources/assets/admin/js/app/monitoring.js ***!
  \*****************************************************/
/***/ (() => {

$(document).ready(function () {
  var busSpeeds = {};

  if ($('meta[id="env_speed"]').attr('content') == true) {
    setInterval(function () {
      $.get('/admin/users/coordinate/is-high-speed', function (data) {
        if (busSpeeds[data.busId] != data.speed) {
          busSpeeds[data.busId] = data.speed;

          if (data.isExceeded) {
            $.easyAlert({
              'message': data.message,
              'alertType': 'danger',
              'link_page': 'https://' + window.location.hostname + '/admin/monitoring',
              'position': 't c',
              'autoHide': true
            });
          }
        }
      });
    }, 1000 * 10);
    $("#setSpeed").click(function () {
      var highSpeed = $('input[name="high_speed"]').val();
      $.post("/admin/monitoring/sethighspeed", {
        highSpeed: highSpeed
      });
    });
  }
});

/***/ }),

/***/ "./resources/assets/admin/js/app/order.js":
/*!************************************************!*\
  !*** ./resources/assets/admin/js/app/order.js ***!
  \************************************************/
/***/ (() => {

$(document).ready(function () {
  $(document).on('panel-form-ajax-success', '.js_orders-from', eventOrdersForm);
  $(document).on('panel-form-ajax-error', '.js_orders-from', eventOrdersForm);
  $(document).on('click', '.js_orders-client-search', getClientInfo);
  $(document).on('click', '.js_orders-toTour', toTour);
  $(document).on('click', '.seat:not(.reserved)', toggleSeat);
  $(document).on('change', '.js_orders-count_places', updateFormSeatActive);
  $(document).on('click', '.js_orders-to-tours', toTours);
  $(document).on('click', '.js_orders-get-check', getCheck);
  $(document).on('click', '.js_orders-completed', orderCompletedType);
  $(document).on('click', '.js_orders-completed_continue', orderCompletedContinueType);
  $(document).on('click', '.js_orders-completed_return', orderCompletedReturnType);
  $(document).on('click', '.js_order_calculation', orderCalculation);
  $(document).on('click', '.js_order-cancel', orderCancel);
  $(document).on('click', '.js_orders-selection-places', orderSelectionPlaces);
  $(document).on('change', '.js_admin_orders-count_places_child', setChild);
  $(document).on('change', '.js_set_station_from', setNewStation);
  $(document).on('change', '.js_station_to_filter', ChangeToStation);
  $(document).on('change', '.js_orders-client-status_is', setNewStatus);
  $(document).on('change', '#country', maskPhone);
  $(document).on('click', '.save_order_places', SaveOrderPlaces);
  $(document).on('click', '.save_order', SaveOrder);
  $(document).on('focusout', '.js_input_order_places', SaveOrderPlacesData);
  $(document).on('change', '.js_datepicker_order_places', SaveOrderPlacesData);

  if (!$('#phone').val()) {
    $("#station_from_id option:not(:selected), #station_to_id option:not(:selected)").prop('disabled', true);
  } else {
    $("#station_from_id, #station_to_id").tooltip('disable');
  }

  function orderCalculation() {
    $('.js_orders-type').val('no_completed');
  }

  function ChangeToStation() {
    $('.wrapper-spinner').show();
    checkStation('to');
  }

  function checkStation(destination) {
    var data = {
      tour_id: $("input[name='tour_id']").val(),
      station_from_id: $("#station_from_id").val(),
      station_to_id: $("#station_to_id").val(),
      count_places: $("#count_places").val(),
      order_id: $('.js_orders-id').val(),
      order_slug: $('.js_orders-slug').val(),
      status: $('#status').val(),
      destination: destination
    };
    $.get('/admin/orders/check_stations', data, function (response) {
      $('.wrapper-spinner').hide();

      if (response.result === 'error') {
        $('.ibox-footer').hide();
        toastr.error(response.message);
      } else {
        $('#BusLayout').html(response.bus_tour);

        if ($('.js_orders-client-phone').inputmask("isComplete")) {
          $(".js_order_calculation").parent().parent().submit();
          $('.ibox-footer').show();
          toastr.success(response.message);
        }
      }
    });
  }

  function getCheck() {
    var url = $(this).data('url');
    $.get("".concat(url), function (response) {
      if (response.result == 'success') {
        toastr.success(response.message);
        window.open(response.link, '_blank').focus();
      } else {
        toastr.error(response.message);
      }
    });
  }

  function SaveOrderPlaces() {
    var order_id = $(this).data('order_id');
    var el = '#order_places-' + order_id;
    var url = $(this).data('url');
    var data = $(el + ' :input').serializeArray();
    $.post(url, data, function () {
      $('a[data-target="#order-' + order_id + '"]').click();
      toastr.success('Данные сохранены!');
    });
  }

  function SaveOrder() {
    var order_id = $(this).data('order_id');
    var el = '#' + order_id;
    var url = $(this).data('url');
    var data = $(el + ' :input').serializeArray();
    $.post(url, data, function () {
      $('a[data-target="#order_places-' + order_id + '"]').click();
      toastr.success('Данные сохранены!');
    });
  }

  function SaveOrderPlacesData() {
    var order_id = $('.js_div_order_places').data('order_id');
    var url = $('.js_div_order_places').data('url');
    var data = $('.js_div_order_places :input').serializeArray();
    $.post(url, data);
  }

  function setNewStatus() {
    if (confirm("Вы подтверждаете изменение?")) {
      var status_id = $(this).val();
      var url = $(this).data('url');
      var id = $('input[name=client_id]').val();
      $(this).data("current", $(this).val());

      if (id) {
        $('.wrapper-spinner').show();
        $.get(url + '?id=' + id + '&status_id=' + status_id, function (response) {
          $('.wrapper-spinner').hide();
          $(".js_order_calculation").click();
          toastr.success('Социальный статус успешно обновлён');
        });
      } else {
        toastr.warning('Клиент не загружен');
      }

      return true;
    } else {
      $(this).val($(this).data('current'));
      return false;
    }
  }

  function setNewStation() {
    var $this = $(this);
    var url = $this.data('url');
    var route_id = $this.data('route_id');
    var station_from_id = $this.val();
    var station_to_id = $this.data('station_to_id');
    $('.wrapper-spinner').show();
    $.get(url + '?route_id=' + route_id + '&station_from_id=' + station_from_id + '&station_to_id=' + station_to_id, function (response) {
      $('.js_set_station_to').html(response);
      checkStation('from');
    });
  }

  function orderSelectionPlaces() {
    var $this = $(this);
    var url = $this.data('url');
    $.get(url, function (response) {
      if (response.result == 'success') {
        $('.js_orders-left').html(response.html);
        $('.js_orders-places_with_number').val(1);
        $this.remove();
        updateFormSeatActive();
      }
    });
    return false;
  }

  function setChild() {
    var url = $(this).data('url');
    var order_id = $(this).data('order_id');
    var $prices = $('.js_admin_price_places');
    var count = $(this).val();
    $('.js_input_order_places').first().trigger('focusout');
    $.get("".concat(url, "?count=").concat(count, "&order_id=").concat(order_id), function (response) {
      if (response.result == 'success') {
        toastr.success(response.message);
      } else {
        toastr.error(response.message);
      }

      $prices.html(response.view);
    });
  }

  function orderCancel() {
    var url = $(this).attr('href') + '?id=' + $(this).data('id');
    $.get(url, function (response) {
      if (response.result == 'success') {
        window.showNotification(response.message, 'error');
        $('.js_form-ajax-back').click();
      }
    });
    return false;
  }

  function orderCompletedType() {
    $(this).addClass('click');
    var $type = $('.js_orders-type'); // let type = $type.val();

    $type.val($(this).data('type'));
    $('.js_orders-from').submit(); // setTimeout(function () {
    // //     let orderId = $('.js_orders-slug').val();
    //     // toastr.success('Номер брони ' + orderId);
    // }, 2000);
    // $type.val(type)
  }

  function orderCompletedContinueType() {
    if ($('.js_orders-client-phone').val().length > 10) {
      var location_href = $(this).data('url') + '?incomming_phone=' + $('.js_orders-client-phone').val();
      $(this).addClass('click');
      var $type = $('.js_orders-type'); // let type = $type.val();

      $type.val($(this).data('type'));
      $('.js_orders-from').ajaxSubmit({
        success: function success(data) {
          if (data.id !== undefined) {
            window.location.href = location_href;
          } else {
            toastr.error('Не заполнены все обязательные поля!');
          }
        }
      });
    } else toastr.error('Не заполнен номер телефона');
  }

  function orderCompletedReturnType() {
    if ($('.js_orders-client-phone').val().length > 10) {
      var location_href = $(this).data('url') + '?incomming_phone=' + $('.js_orders-client-phone').val();
      $(this).addClass('click');
      var $type = $('.js_orders-type');
      $type.val($(this).data('type'));
      $('.js_orders-from').ajaxSubmit({
        success: function success(data) {
          if (data.id !== undefined) {
            window.location.href = location_href + '&order_return=' + data.id;
          } else {
            toastr.error('Не заполнены все обязательные поля!');
          }
        }
      });
    } else toastr.error('Не заполнен номер телефона');
  }

  function toggleSeat() {
    var phone = $('.js_orders-client-phone').val();
    var firstName = $('.js_orders-client-first_name').val();

    if (phone.replace(/\D/g, '').length > 10 && firstName) {
      $(this).toggleClass('active');
      updateFormSeatActive(); //$('.js_orders-from').submit()
    } else {
      window.showNotification('Введите сперва номер телефона и имя', 'error');
    }
  }

  function eventOrdersForm(e, response) {
    if (response.result == 'success' && $('.js_orders-type').val() == $('.js_orders-completed').data('type') && $('.js_orders-completed').hasClass('click')) {
      setTimeout(function () {
        return $('.js_form-ajax-back').click();
      }, 500);
    } else {
      $('.js_orders-completed').removeClass('click');
    } // console.log(response);


    if (response.id) {
      $('.js_orders-slug').val(response.slug);
      $('.js_orders-id').val(response.id); // hm....

      toastr.success('Номер брони ' + response.slug);

      if (!$('.js_order-old-places').length) {
        $('.js_order-cancel').data('id', response.id).removeClass('hidden');
      }
    }

    if (response.view_tour) {
      $('.js_orders-left').html(response.view_tour);
      $('.js_input_order_places').first().trigger('focusout');
    }

    if (response.result == 'error') updateFormSeatActive();
  }

  function toTours() {
    var url = $(this).data('url');
    $.get(url, function (response) {
      if (response.result == 'success') {
        $('.js_orders-left').html(response.html);
        $('.js_orders-filter').html(response.filter);
        window.init();
      }
    });
  }

  function toTour() {
    var phone = $('input[name=phone]').val();
    var city_from_id = $(this).data('city_from_id');
    var city_to_id = $(this).data('city_to_id');
    var url = $(this).data('url') + '?phone=' + phone + '&city_from_id=' + city_from_id + '&city_to_id=' + city_to_id + '&with_phone=' + Number(phone.length === 0);
    $.get(url, function (response) {
      if (response.result == 'success') {
        $('.js_orders-filter').html('');
        $('.js_orders-left').html(response.html);
        $('.js_orders-tour-info').html(response.tour_info);
        $('.js_orders-client-info').html(response.viewClientInfo);
        $('.js_orders-tour_id').val(response.tour_id);
        updateFormSeatActive();

        if (response.clientPhone) {
          $('#country').val(response.clientPhone);
          switchCountry(response.clientPhone);
        }
      }
    });
  }

  function maskPhone() {
    var country = $('#country option:selected').val();
    switchCountry(country);
  }

  function switchCountry(country) {
    switch (country) {
      case "ru":
        $("#phone").inputmask("+7(999) 999-99-99", {
          'oncomplete': getClientInfo
        });
        break;

      case "ua":
        $("#phone").inputmask("+380(99) 999-99-99", {
          'oncomplete': getClientInfo
        });
        break;

      case "by":
        $("#phone").inputmask("+375(99) 999-99-99", {
          'oncomplete': getClientInfo
        });
        break;

      case "de":
        $("#phone").inputmask("+4\\9(999) 9999-999", {
          'oncomplete': getClientInfo
        });
        break;

      case "dee":
        $("#phone").inputmask("+4\\9(999) 999-99-999", {
          'oncomplete': getClientInfo
        });
        break;

      case "cz":
        $("#phone").inputmask("+420(999) 999-999", {
          'oncomplete': getClientInfo
        });
        break;

      case "il":
        $("#phone").inputmask("+\\972(99) 999-9999", {
          'oncomplete': getClientInfo
        });
        break;

      case "us":
        $("#phone").inputmask("+1(999) 999-9999", {
          'oncomplete': getClientInfo
        });
        break;

      case "fi":
        $("#phone").inputmask("+358(99) 999-999", {
          'oncomplete': getClientInfo
        });
        break;

      case "no":
        $("#phone").inputmask("+47(99) 999-999", {
          'oncomplete': getClientInfo
        });
        break;

      case "pl":
        $("#phone").inputmask("+48(999) 999-999", {
          'oncomplete': getClientInfo
        });
        break;

      case "uz":
        $("#phone").inputmask("+\\9\\98(99) 999-99-99", {
          'oncomplete': getClientInfo
        });
        break;

      case "tm":
        $("#phone").inputmask("+\\9\\93(999) 999-999", {
          'oncomplete': getClientInfo
        });
        break;

      case "md":
        $("#phone").inputmask("+373(99) 999-999", {
          'oncomplete': getClientInfo
        });
        break;

      case "az":
        $("#phone").inputmask("+\\9\\94(99) 999-99-99", {
          'oncomplete': getClientInfo
        });
        break;

      case "tj":
        $("#phone").inputmask("+\\9\\92(9999) 9-99-99", {
          'oncomplete': getClientInfo
        });
        break;

      case "fr":
        $("#phone").inputmask("+33(999) 999-999", {
          'oncomplete': getClientInfo
        });
        break;

      case "gr":
        $("#phone").inputmask("+30(999) 999-99-99", {
          'oncomplete': getClientInfo
        });
        break;
    }
  }

  maskPhone();

  function getClientInfo() {
    var tour_id = $('input[name=tour_id]').val();
    var $this = $('.js_orders-client-phone');
    var url = $this.data('url-client-info');
    var phone = $this.val();
    $.get("".concat(url), {
      'phone': phone,
      'tour_id': tour_id
    }, function (response) {
      $('.js_orders-client-info').html(response.viewClientInfo);
      $('.js_orders-client-status_is').val(response.status_id);
      $('.js_orders-client-date_social').val(response.date_social);
      $('.js_transfer_street').val(response.last_address.street);
      $('.js_transfer_house').val(response.last_address.house);
      $('.js_transfer_building').val(response.last_address.building);
      $('.js_transfer_apart').val(response.last_address.apart);
      $('.js-taxi-history').on('change', function () {
        $('#suggest_from').val($(this).find(':selected').data('from'));
        $('#suggest_to').val($(this).find(':selected').data('to'));
      });
      $("#station_from_id option:not(:selected), #station_to_id option:not(:selected)").prop('disabled', false);
      $("#station_from_id, #station_to_id").tooltip('disable');

      if ($("#station_from_id").val()) {
        ChangeToStation();
      }

      init();
      window.showNotification(response.message, response.type);
    });
  }

  function initOrderClientPhone() {
    if ($('#country').val()) {
      switchCountry($('#country').val());
    } else {
      $("#country").val(InputCodeValue);
      switchCountry(InputCodeValue);
    }
  }

  function updateFormSeatActive() {
    var $wrapper = $('.js_orders-places-input');
    $wrapper.html('');
    var $countPlaces = $('.js_orders-count_places');

    if ($countPlaces.length) {
      var val = $countPlaces.val();

      for (var $i = 0; $i < val; $i++) {
        $wrapper.prepend("<input type=\"hidden\" name=\"places[]\" value=\"\" data-number=\"\"/>");
      }
    } else {
      $('.seat.active:not(.reserved)').each(function () {
        var number = $(this).data('number');
        $wrapper.prepend("<input type=\"hidden\" name=\"places[]\" value=\"".concat(number, "\" data-number=\"").concat(number, "\"/>"));
      });
    }
  }

  initOrderClientPhone();
  window.initOrderClientPhone = initOrderClientPhone;
});

/***/ }),

/***/ "./resources/assets/admin/js/app/package.js":
/*!**************************************************!*\
  !*** ./resources/assets/admin/js/app/package.js ***!
  \**************************************************/
/***/ (() => {

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

$(document).ready(function () {
  $('#popup_package-add').on('show.bs.modal', function (e) {
    var _this = this;

    var $button = $(e.relatedTarget);
    var url = $button.data('url');
    $.get(url, function (response) {
      $(_this).find('.modal-content').html(response.html);
      window.init();
    });
  });
  $('#popup_packages_of_tour').on('show.bs.modal', function (e) {
    var _this2 = this;

    var $button = $(e.relatedTarget);
    var url = $button.data('url');
    $.get(url, function (response) {
      $(_this2).find('.modal-content').html(response.html);
      window.init();
    });
  });
  $(document).on('changeDate', '#time_start', ChangeDate);
  $(document).on('change', '#route_id', ChangeRoute);
  $(document).on('click', '#index-packages', IndexPackagesByDate);
  $(document).on('click', 'input[name="station_radio"]', stationRadio);
  var url_cal_glob;

  function ChangeDate() {
    var time_start;
    time_start = $('[name=time_start]').val();
    url_cal_glob = "".concat($(this).data('url'), "/").concat(time_start);
    $('.from_dist_package').css('display', 'none');
    $('#tour_id').prop('disabled', true);
    $.get(url_cal_glob, function (response) {
      //tours
      $('#tour_id').empty();
      $('#tour_id').append('<option value="">' + 'Выберите рейс' + '</option>');
      $.each(response.tours, function (key, value) {
        $('#tour_id').append('<option value="' + value.id + '">' + value.start + ', ' + value.route_name + ', ' + value.bus_name + ', ' + value.driver_name + '</option>');
      }); //routes

      $('#route_id').empty();
      $('#route_id').append('<option value="">выберите направление</option>');

      var routes = _toConsumableArray(new Map(response.tours.map(function (item) {
        return [item['route_id'], item];
      })).values());

      $.each(routes, function (key, value) {
        $('#route_id').append('<option value="' + value.route_id + '">' + value.route_name + '</option>');
      });
      $('#route_id').prop('disabled', false);
    });
  }

  function ChangeRoute() {
    $('#package_from').val('');
    $('#package_destination').val('');
    $('#from_station_id').val('');
    $('#destination_station_id').val('');
    $('.from_dist_package_field').css('display', 'none');
    $('input[name="station_radio"]:first').prop('checked', true);

    if ($('#route_id').val() != '') {
      $('#tour_id').prop('disabled', false);
      $('.from_dist_package').css('display', 'block');
      $('.from_dist_package_select').css('display', 'block');
    } else {
      $('#tour_id').append('<option value="">выберите рейс</option>');
      $('#tour_id').prop('disabled', true);
      $('.from_dist_package').css('display', 'none');
      $('.from_dist_package_field').css('display', 'none');
      $('.from_dist_package_select').css('display', 'none');
    }

    var url_cal_route = url_cal_glob + "/" + $(this).children('option:selected').val();
    $.get(url_cal_route, function (response) {
      $('#tour_id').empty();
      $('#from_station_id').empty();
      $('#destination_station_id').empty();
      $.each(response.tours, function (key, value) {
        $('#tour_id').append('<option value="' + value.id + '">' + value.start + ', ' + value.route_name + ', ' + value.bus_name + ', ' + value.driver_name + '</option>');
      }); //$('#from_station_id').append('<option value="">выберите отстановку</option>');

      $.each(response.stations.stations, function (key, value) {
        $('#from_station_id').append('<option value="' + value.id + '">' + value.name + '</option>');
      }); //$('#destination_station_id').append('<option value="">выберите отстановку</option>');

      $.each(response.stations.stations, function (key, value) {
        $('#destination_station_id').append('<option value="' + value.id + '">' + value.name + '</option>');
      });
    });
    url_cal_glob_route = null;
  }

  function stationRadio() {
    var radioValue = $(this).val();
    $('#package_from').val('');
    $('#package_destination').val('');
    $('#from_station_id').val('');
    $('#destination_station_id').val('');

    if (radioValue == 0) {
      $('.from_dist_package_select').css('display', 'none');
      $('.from_dist_package_field').css('display', 'block');
    } else if (radioValue == 1) {
      $('.from_dist_package_select').css('display', 'block');
      $('.from_dist_package_field').css('display', 'none');
    }
  }

  function IndexPackagesByDate() {
    var packagesBtn = $('.packages-button');
    var toursBtn = $('.tours-button');
    var toursIndex = $('#tours-index');
    var packagesIndex = $('#packages-index');

    if (packagesBtn.hasClass('packages-button-active')) {
      packagesBtn.removeClass('packages-button-active').css('display', 'none');
      toursBtn.addClass('tours-button-active').css('display', 'inline');
      toursIndex.css('display', 'none');
      packagesIndex.css('display', 'block');
      var url_params = {};
      location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (s, k, v) {
        url_params[k] = v;
      });
      var url = $('#index-packages').data('url');
      if (url_params.date) url = url + '/' + url_params.date;
      $.get(url, function (response) {
        console.log(url);
        $('#packages-index').html(response.html);
      });
    } else {
      toursBtn.removeClass('tours-button-active').css('display', 'none');
      packagesBtn.addClass('packages-button-active').css('display', 'inline');
      toursIndex.css('display', 'block');
      packagesIndex.css('display', 'none');
    }
  }
});

/***/ }),

/***/ "./resources/assets/admin/js/app/pusher.js":
/*!*************************************************!*\
  !*** ./resources/assets/admin/js/app/pusher.js ***!
  \*************************************************/
/***/ (() => {

$(document).ready(function () {
  var today = new Date();
  var dd = String(today.getDate()).padStart(2, '0');

  if (dd < 15 && dd > 0) {
    setInterval(function () {
      $.get("/admin/check_is_pay", function (data) {
        if (data.is_paid == false) {
          $('.my-alert-warning').fadeIn(2000);
          setTimeout(function () {
            $('.my-alert-warning').fadeOut(2000);
          }, 15000);
        }
      });
    }, 60000);
  } else {
    setInterval(function () {
      $.get("/admin/check_is_pay", function (data) {
        if (data.is_paid == false) {
          $('.my-alert-danger').fadeIn(2000);
          setTimeout(function () {
            $('.my-alert-danger').fadeOut(2000);
          }, 15000);
        }
      });
    }, 60000);
  }
});

/***/ }),

/***/ "./resources/assets/admin/js/app/repair.js":
/*!*************************************************!*\
  !*** ./resources/assets/admin/js/app/repair.js ***!
  \*************************************************/
/***/ (() => {

$(document).ready(function () {
  $(document).on('change', '.js_department_select', selectDepartmentChange);
  $(document).on('change', '.js_car_select', selectCarChange);
  $(document).on('click', '.js_finish_repair', finishRepair);

  function selectDepartmentChange() {
    var url = $(this).data('url');
    var val = $(this).val();
    $.get(url, {
      val: val
    }, function (response) {
      if (response.val) {
        $('.js_cars_template').html(response.view);
      }
    });
  }

  function selectCarChange() {
    var url = $(this).data('url');
    var val = $(this).val();
    $.get(url, {
      val: val
    }, function (response) {
      if (response.val) {
        $('.js_cards_template').html(response.view);
      }
    });
  }

  function finishRepair() {
    var href = $(this).data('href');
    var redirect = $(this).data('redirect');
    var status = $(this).data('status');
    var question = $(this).data('question');
    var bus_status = $(this).data('busStatus');
    bootbox.confirm({
      message: question,
      callback: function callback(result) {
        if (result) {
          $.ajax({
            url: href,
            method: 'post',
            data: {
              status: status,
              bus_status: bus_status
            },
            success: function success(data) {
              if (data.result == 'success') {
                window.showNotification(data.message, 'success');

                if (typeof changed_parts !== 'undefined') {
                  changed_parts = [];
                }

                location.href = redirect;
              } else {
                window.showNotification(data.message, 'error');
              }
            }
          });
        }
      }
    });
  } // очистить все поля формы по id


  $(document).on('click', '#clearCarRepairFilter', clearForm);

  function clearForm() {
    $('#createRepairFilter').trigger("reset");
  } // $(document).on('change', '.card-checkbox', onoffswitchClick)


  function onoffswitchClick() {
    // ��� ��������� ������� �������� ������������ ����
    // �������� ��������, ��� ���������� - ��������
    if ($(this).find('input:checked').val()) {
      $(this).parent().next().removeClass('invisible');
    } else {
      $(this).parent().next().addClass('invisible');
    }
  }
});

/***/ }),

/***/ "./resources/assets/admin/js/app/route.js":
/*!************************************************!*\
  !*** ./resources/assets/admin/js/app/route.js ***!
  \************************************************/
/***/ (() => {

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

$(document).ready(function () {
  $(document).on('change', '.js_city_filter', setStreets);
  $(document).on('change', '.js_stations_from_to_price', setDataInArrows);
  $(document).on('change', '.js_stations_from_to_price', setStationFromToPrice);
  $(document).on('click', '.js_stations_price', setStationPrice);
  $(document).on('click', '.js_stations_all_price', setStationAllPrice);
  $(document).on('change', '.js_route_type', setFlights);
  $(document).delegate('.js-input-route-sales', 'select2:selecting', init);

  function init() {
    $(document).undelegate('.js-input-route-sales', 'select2:selecting', init);
    var element = $('.js-input-route-sales');
    var value = element.val();
    salesIds = (value ? value : []).reduce(function (result, value) {
      result[value] = $('.js-input-route-sales option[value="' + value + '"]').data('type');
      return result;
    }, {});
    element.on('select2:select', removeSameTypeSale);
  }

  var salesIds = [];

  function setStreets() {
    var $this = $(this);
    var $streets = $('.js_street_filter');
    var $url = $streets.data('url') + '?city_id=' + $this.val();
    $('.js_street_filter option').each(function () {
      $(this).remove();
    });
    $.get($url, function (response) {
      for (var key in response) {
        $('.js_street_filter').append($('<option>', {
          value: response[key].id,
          text: response[key].name
        }));
      }

      $('.js_street_filter').trigger('change');
    });
  }

  function setDataInArrows() {
    var price = $(this).val();
    var station_from_id = $(this).data('station_from_id');
    var station_to_id = $(this).data('station_to_id');
    $(".arrow").each(function () {
      if (station_from_id == $(this).data('station_from_id')) {
        $(this).attr('price', price);
      }

      if (station_to_id == $(this).data('station_to_id')) {
        $(this).attr('price', price);
      }
    });
  }

  function setStationFromToPrice() {
    $.get($(this).data('url'), _defineProperty({
      route_id: $(this).data('route_id'),
      station_from_id: $(this).data('station_from_id'),
      station_to_id: $(this).data('station_to_id')
    }, $(this).data('type'), $(this).val()), function (response) {
      window.showNotification(response.message, response.type);
    });
  }

  function setStationPrice() {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    var price = $(this).attr('price');
    var station_from_id = null,
        station_to_id = null;
    $(".arrow").each(function () {
      if (price == $(this).attr('price')) {
        if ($(this).data('station_from_id') != null) {
          station_from_id = $(this).data('station_from_id');
        } else {
          station_to_id = $(this).data('station_to_id');
        }
      }
    });
    $.get($(this).data('url'), {
      route_id: $(this).data('route_id'),
      price: $(this).attr('price'),
      station_from_id: station_from_id,
      station_to_id: station_to_id
    }, function (response) {
      $('.js_spinner-overlay').hide();
      $('.background-spinner').hide();
      window.showNotification(response.message, response.type);
      location.reload();
    });
  }

  function setStationAllPrice() {
    $('.js_spinner-overlay').show();
    $('.background-spinner').show();
    $.get($('#all-sells').data('url'), {
      route_id: $('#all-sells').data('route_id'),
      price: $('#all-sells').val()
    }, function (response) {
      $('.js_spinner-overlay').hide();
      $('.background-spinner').hide();
      window.showNotification(response.message, response.type);
      location.reload();
    });
  }

  function setFlights() {
    if (this.value == 'is_transfer') {
      $('.js_flight_type').slideDown();
    } else {
      $('.js_flight_type').slideUp();
    }
  }

  function removeSameTypeSale() {
    var values = $(this).val();
    var ids = Object.keys(salesIds);
    var newValue = values.filter(function (value) {
      return ids.indexOf(value) === -1;
    }).shift();
    var type = $('.js-input-route-sales option[value="' + newValue + '"]').data('type');
    salesIds = ids.reduce(function (result, value) {
      if (salesIds[value] === type && type === 'each') {
        return result;
      }

      result[value] = salesIds[value];
      return result;
    }, {});
    salesIds[newValue] = type;
    $(this).val(Object.keys(salesIds)).change();
  }

  $('#popup_route-edit').on('show.bs.modal', function (e) {
    var _this = this;

    var $button = $(e.relatedTarget);
    var url = $button.data('url');
    $.get(url, function (response) {
      $(_this).find('.modal-content').html(response.html);
      window.init();
    });
  });
  $(document).on('hidden.bs.modal', function (e) {
    $(this).find('.modal-content').html('');
  });
});

/***/ }),

/***/ "./resources/assets/admin/js/app/schedule.js":
/*!***************************************************!*\
  !*** ./resources/assets/admin/js/app/schedule.js ***!
  \***************************************************/
/***/ (() => {

$(document).ready(function () {
  $(document).on('change', '.js_bus-change', templateChange);
  $(document).on('change', '.js_route-change', routeChange);
  $(document).on('change', '.js-days-yes-or-no', selectNoOrYesOnShedule);
  $(document).on('click', '.js-button-copy', copyPrices);
  $(document).on('click', '.js_change_sched_price', changeSchedPrice);
  $('.js_route-change').trigger('change');
  $('.js-days-yes-or-no').trigger('change');

  function templateChange() {
    var url = $(this).data('url');
    var val = $(this).val();
    $.get("".concat(url, "/").concat(val), function (response) {
      if (response.val) {
        $('.js_driver-select').val(response.val);
      }
    });
  }

  function routeChange() {
    var url = $(this).data('url');
    var val = $(this).val();
    $.get(url + '/' + val, function (response) {
      if (response.data) {
        var data = response.data;

        if (data['is_transfer']) {
          $('.js_flight-data').slideDown();
          $('#flight-offset').data('interval', data['interval']);
          $('#flight-time').data('type', data['flight_type']); // Сохраняем тип рейса - прилет или вылет

          $('#date_start_time').prop('readonly', true);

          if (data['flight_type'] == 'arrival') {
            $('#to-flight').text('К прилету рейса');
            $('#flight-time').attr('placeholder', 'время прилета');
          } else {
            $('#to-flight').text('К вылету рейса');
            $('#flight-time').attr('placeholder', 'время вылета');
          }
        } else {
          $('.js_flight-data').slideUp();
          $('#date_start_time').prop('readonly', false);
        }
      }
    });
  }

  function selectNoOrYesOnShedule() {
    var day = $(this).attr('day');

    if ($(this).is(':checked')) {
      if ($(this).val() == 0) {
        $(".no-display-fields").each(function () {
          if ($(this).attr('day') == day) {
            $(this).attr('hidden', '');
          }
        });
      } else {
        $(".no-display-fields").each(function () {
          if ($(this).attr('day') == day) {
            $(this).removeAttr('hidden');
          }
        });
      }
    }
  }

  function copyPrices() {
    var day = $(this).attr('day');
    var price;
    $(".schedule-price").each(function () {
      if (day == $(this).attr('day')) {
        price = $(this).val();
      }

      $(this).val(price);
    });
  }

  $("#flight-time, #flight-offset").change(function () {
    if ($('#flight-time').data('type') == 'arrival') {
      // Если рейс прилетает, то ко времени прибытия добавляем время сдвига
      $('#date_start_time').val(addTimes($('#flight-time').val(), $('#flight-offset').val()));
    } else {
      // Если рейс вылетает, то от времени вылета отнимаем время поездки (интервал направления) и отнимаем время сдвига
      var registrTime = timeToMins($('#flight-time').val()) - $('#flight-offset').data('interval');
      var offsetMins = timeToMins($('#flight-offset').val());

      if (registrTime < offsetMins) {
        registrTime += 1440;
      }

      $('#date_start_time').val(timeFromMins(registrTime - offsetMins));
    }
  });

  function changeSchedPrice(e) {
    e.preventDefault();
    var $link = $(this);
    var dialog = bootbox.prompt({
      title: $link.data('title'),
      placeholder: "Новая цена",
      message: "<p>Внимание, будет изменена цена всех отображаемых расписаний!<br><br></p>",
      size: "large",
      callback: function callback(result) {
        if (result !== null && $.isNumeric(result)) {
          $('#mass_price_update').prop('disabled', false);
          $('#mass_price_update').val(result);
          $('.js_table-search').submit();
          $('#mass_price_update').prop('disabled', true);
        }
      }
    });
    return false;
  } // Convert a time in hh:mm format to minutes


  function timeToMins(time) {
    var b = time.split(':');
    var mins = b[0] * 60 + +b[1];
    return isNaN(mins) ? 0 : mins;
  } // Convert minutes to a time in format hh:mm. Returned value is in range 00 to 24 hrs


  function timeFromMins(mins) {
    function z(n) {
      return (n < 10 ? '0' : '') + n;
    }

    var h = (mins / 60 | 0) % 24;
    var m = mins % 60;
    return z(h) + ':' + z(m);
  } // Add two times in hh:mm format


  function addTimes(t0, t1) {
    return timeFromMins(timeToMins(t0) + timeToMins(t1));
  }
});

/***/ }),

/***/ "./resources/assets/admin/js/app/settings.js":
/*!***************************************************!*\
  !*** ./resources/assets/admin/js/app/settings.js ***!
  \***************************************************/
/***/ (() => {

$(document).ready(function () {
  $(document).on('click', '.closes', deleteImage);
  var type = $("#ticket_type > option:selected").val();

  if (type == 2) {
    $("#field").attr("hidden", false);
  } else {
    $("#field").attr("hidden", true);
  }

  $("#ticket_type").on('change', function () {
    type = $("#ticket_type > option:selected").val();

    if (type == 2) {
      $("#field").attr("hidden", false);
    } else {
      $("#field").attr("hidden", true);
    }
  });
});

function deleteImage() {
  var path = $(this).attr('path');
  $.post("/admin/settings/clients_interface_settings/image-delete", {
    imagePath: path
  }).done(function (data) {
    location.reload();
  });
}

$(document).on('change', '#font_size', function () {
  var v = $(this).val();
  $('#example_size').css('font-size', v + 'px');
});
$(document).on('change', '#border_radius', function () {
  var v = $(this).val();
  $('#example_radius').css('border-radius', v + 'px');
});
$(document).on('change', '#opacity', function () {
  var v = $(this).val();
  $('#example_opacity').css('opacity', v);
});
$(document).on('click', '.button_color', function () {
  var v = $(this).val();
  $('#button_color').val(v);
});
$(document).on('click', '.font_color', function () {
  var v = $(this).val();
  $('#font_color').val(v);
});
$(document).on('click', '.background_color', function () {
  var v = $(this).val();
  $('#background_color').val(v);
});
$(document).on('click', '.font_color_authorization_buttons', function () {
  var v = $(this).val();
  $('#font_color_authorization_buttons').val(v);
});

/***/ }),

/***/ "./resources/assets/admin/js/app/smsconfig.js":
/*!****************************************************!*\
  !*** ./resources/assets/admin/js/app/smsconfig.js ***!
  \****************************************************/
/***/ (() => {

$(document).ready(function () {
  $(document).on('click', '#js_provider-add-new', addProvider);
  $(document).on('click', '.js-btn-remove', removeProvider);
  var fields = ['provider_name_new', 'provider_number_prefix_new', 'sms_send', 'sms_sender', 'sms_api_login', 'sms_api_password', 'is_latin'];

  function addProvider() {
    var el = $(this).closest('.tab-pane');

    if (validate(el)) {
      var newElement = $(el).closest('.tab-pane').clone(); // generate new ID

      var id = Math.random().toString(36).substring(7);
      $(newElement).attr('id', 'provider-tab' + id).removeClass('active in');
      $(newElement).find('#js_provider-add-new').removeClass('btn-primary').addClass('btn-danger js-btn-remove').attr('data-id', id).attr('id', '').html('<i class="fa fa-dot-circle-o"></i> ' + 'Удалить оператора');
      $(newElement).find('#provider_name_new').attr('id', 'provider_name' + id).attr('name', 'provider_name[' + id + ']');
      $(newElement).find('#provider_number_prefix_new').attr('id', 'provider_number_prefix' + id).attr('name', 'provider_number_prefix[' + id + ']');
      $(newElement).find('#sms_send').attr('id', 'sms_send' + id).attr('name', 'provider_sms_send[' + id + ']');
      $(newElement).find('#sms_sender').attr('id', 'sms_sender' + id).attr('name', 'provider_sms_sender[' + id + ']');
      $(newElement).find('#sms_api_login').attr('id', 'sms_api_login' + id).attr('name', 'provider_sms_api_login[' + id + ']');
      $(newElement).find('#sms_api_password').attr('id', 'sms_api_password' + id).attr('name', 'provider_sms_api_password[' + id + ']');
      $(newElement).find('#is_latin').attr('id', 'is_latin' + id).attr('name', 'provider_is_latin[' + id + ']');
      $(newElement).find('#provider_active_new').attr('id', 'provider_active' + id).attr('name', 'provider_active[' + id + ']');
      $(newElement).find('#provider_default_new').attr('id', 'provider_default' + id).attr('name', 'provider_default').attr('value', id).addClass('js-default');
      var nav = '<li class="nav-item "><a class="nav-link" id="provider' + id + '" data-toggle="tab" href="#provider-tab' + id + '" role="tab" aria-controls="provider' + id + '" aria-selected="true">' + $('#provider_name_new').val() + '</a></li>'; // reset form

      $("#js_provider-new input").val('');
      $("#js_provider-new").before(newElement);
      $("#add-tab").before(nav);
      $("#provider" + id).trigger('click');
    }
  }

  function validate(el) {
    var error = false;

    for (var i = 0; i < fields.length; i++) {
      var _field = $(el).find("#" + fields[i]).closest('.form-group');

      if (!document.getElementById(fields[i]).value) {
        _field.addClass('has-error');

        error = true;
      } else {
        _field.removeClass('has-error');
      }
    }

    return !error;
  }

  function removeProvider() {
    var $this = this;
    var providerId = $($this).attr("data-id");

    if (confirm('Вы уверены? Нажмите кнопку Сохранить для сохранения изменений.')) {
      if ($(".nav-item").length > 2) {
        $('#provider' + providerId).closest('.nav-item').remove();
        $('#provider-tab' + providerId).remove();

        if (!$(".js-default").checked) {
          $(".js-default").first().prop('checked', 'checked');
        }

        $('#js_sms-providers a').first().trigger('click');
      } else {
        alert('Это единственный оператор, удалить невозможно :(');
      }
    }
  }
});

/***/ }),

/***/ "./resources/assets/admin/js/app/station.js":
/*!**************************************************!*\
  !*** ./resources/assets/admin/js/app/station.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var sortablejs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! sortablejs */ "./node_modules/sortablejs/modular/sortable.esm.js");


function initSortableStation() {
  $('.js-sortable-station').each(function () {
    var reindex = function reindex() {
      $('.js_reindex-stations').trigger('sortable-stations');
      window.showNotification('Остановки успешно отсортированы');
    };

    sortablejs__WEBPACK_IMPORTED_MODULE_0__["default"].create($(this)[0], {
      onEnd: reindex,
      handle: ".js_multiple-order"
    });
  });
  $('.js-sortable-routes').each(function () {
    var reindex = function reindex() {
      var form = $('#sort-form');
      $.ajax({
        type: "POST",
        url: form.attr('action'),
        data: form.serialize(),
        success: function success(data) {
          if (data.status == 'success') {
            window.showNotification(data.message);
          }
        }
      });
    };

    _sortablejs2["default"].create($(this)[0], {
      onEnd: reindex,
      handle: ".js_multiple-order"
    });
  });
}

$(document).ready(function () {
  $(document).on('multiple-added', '.js_reindex-stations', window.reindexBlocks('stations', ['station_id', 'time', 'cost_start', 'cost_finish', 'tickets_from', 'tickets_to'], '', ['']));
  $(document).on('sortable-stations', window.reindexBlocks('stations', ['station_id', 'time', 'cost_start', 'cost_finish', 'tickets_from', 'tickets_to'], '', ['']));
  $(document).on('multiple-removed', window.reindexBlocks('stations', ['station_id', 'time', 'cost_start', 'cost_finish', 'tickets_from', 'tickets_to'], '', ['']));
  initSortableStation();
});
window.initSortableStation = initSortableStation;

/***/ }),

/***/ "./resources/assets/admin/js/app/tariff.js":
/*!*************************************************!*\
  !*** ./resources/assets/admin/js/app/tariff.js ***!
  \*************************************************/
/***/ (() => {

$(document).ready(function () {
  $(document).on('change', '.js_tariff_change_type', TariffChangeType);
  $(document).on('change', '#id_tariff_type_select', TariffTypeChange);

  function TariffChangeType() {
    var sendData = {
      bus_type_id: $('#id_tariff_bus_type_select').val(),
      type: $('#id_tariff_type_select').val(),
      agreement_id: $("[name='agreement_id']").val()
    };
    $.get('/admin/tariffs/get_min_value', sendData, function (response) {
      $("#min").val(response);
      $("#max").val(parseInt(response) + 1);
    });
  }

  function TariffTypeChange() {
    type = $('[name=type]').val();

    if (type == 'route') {
      $('.tariff_route_group').removeClass('hidden');
      $.get('/admin/tariffs/get_routes', function (response) {
        $('#route_id').empty(); //$('#route_id').append('<option value="">' + 'Выберите направление' + '</option>');

        $.each(response.routes, function (key, value) {
          $('#route_id').append('<option value="' + key + '">' + value + '</option>');
        }); //

        $('#revert_route_id').empty(); //$('#revert_route_id').append('<option value="">' + 'Выберите направление' + '</option>');

        $.each(response.routes, function (key, value) {
          $('#revert_route_id').append('<option value="' + key + '">' + value + '</option>');
        });
      });
    } else {
      $('.tariff_route_group').addClass('hidden');
      $('[name=route_id]').val('2');
      $('[name=revert_route_id]').val('2');
    } //alert($('#route_id').val(), '', $('#revert_route_id').val())

  }
});

/***/ }),

/***/ "./resources/assets/admin/js/app/tour.js":
/*!***********************************************!*\
  !*** ./resources/assets/admin/js/app/tour.js ***!
  \***********************************************/
/***/ (() => {

$(document).ready(function () {
  $(document).on('click', '.js_tour-edit-calculation', tourCalculation);
  $(document).on('click', '.js_tour-edit-forced', tourEditForced);
  $(document).on('click', '.js_input_is_call', OrderInputIsCall);
  $(document).on('click', '.js_admin_send_actual_sms', OrderSendActualSms);
  $(document).on('click', '.js_admin_call', CallClient);
  $(document).on('click', '#print_page', PrintPageTour);
  $(document).on('click', '#print_doc', PrintDocTour);
  $(document).on('click', '#print_page_reverse', PrintPageTourReverse);
  $(document).on('change', '.station_from_time', setTimeFrom);
  $(document).on('change', '.js_city_from_id', CityFrom);
  $(document).on('focusout', '.js_input_order_price', ChangePrice);
  $(document).on('change', '.js_all_dates', allDates);
  $(document).on('change', '.js_only_visible', onlyVisible);
  $(document).on('click', '.js_mass_change_price', massChangePrice);
  $(document).on('click', '.js_copy_to_clipboard', copyToClipboard);

  function ChangePrice() {
    var id = $(this).data('order_id');
    var url = $(this).data('url');
    var price = $(this).val();
    $.get(url + '?id=' + id + '&price=' + price, function (response) {
      console.log(response);
      if (response.message != '') toastr.success(response.message);
    });
  }

  function CityFrom() {
    var url = $(this).data().url;
    var value = $(this).val();
    $(".js_city_to_id option").remove();
    $('.js_city_to_id').prop('disabled', false);
    $.get(url + '?city_from_id=' + value, function (response) {
      $('.js_city_to_id').append($('<option>', {
        value: 0,
        text: '-Куда-'
      }));

      for (var key in response) {
        $('.js_city_to_id').append($('<option>', {
          value: response[key].id,
          text: response[key].name
        }));
      }
    });
  }

  function CallClient() {
    $.get($(this).data('url') + '?phone=' + $(this).data('phone'), function (response) {
      var Type = response.type;

      if (response.type === 'error') {
        toastr.error(toastr.error);
      } else {
        toastr.success('звонок сгенерирован');
      }
    });
  }

  function OrderSendActualSms() {
    var order_id = $(this).data('id');
    var url = $(this).data('url');
    var count_sms = $('.js_order_row_' + order_id).find('.js_count_sms').html();
    count_sms = parseInt(count_sms) + 1;
    $('.js_order_row_' + order_id).find('.js_count_sms').text(count_sms);
    $.get(url + '?id=' + order_id, function (response) {
      toastr.success('смс отправлена');
    });
  }

  function setTimeFrom() {
    var url = $(this).data('url');
    var id = $(this).data('id');
    var station_from_time = $(this).val();
    $.get(url + '?id=' + id + '&station_from_time=' + station_from_time, function (response) {
      if (response.result == 'success') {
        toastr.success(response.message);
      } else {
        toastr.error('что-то пошло не так');
      }
    });
  }

  function dump(obj) {
    var out = '';

    for (var i in obj) {
      out += i + ": " + obj[i] + "\n";
    }

    alert(out); // or, if you wanted to avoid alerts...

    var pre = document.createElement('pre');
    pre.innerHTML = out;
    document.body.appendChild(pre);
  }

  function OrderInputIsCall() {
    var value = 0;
    var id = $(this).attr('id');
    var url = $(this).attr('url');
    var phone = $(this).attr('phone');
    if ($(this).is(':checked')) value = 1;
    $.get(url + '?id=' + id + '&is_call=' + value + '&phone=' + phone, function (response) {
      if (response.result == 'success') {
        toastr.success(response.message);
      } else {
        toastr.error('что-то пошло не так(');
      }
    });
  }

  function tourCalculation() {
    var $calculation = $('[name=calculation]');
    $calculation.val(1);
    $('.js_tours-from').submit();
    $calculation.val(0);
  }

  function tourEditForced() {
    $('.js_tours-from').append('<input type="hidden" name="action" value="forceEdit" />');
    $('.js_tours-from').submit();
    $('input[value=forceEdit]').remove();
  }

  $('#popup_tour-edit').on('show.bs.modal', function (e) {
    var _this = this;

    var $button = $(e.relatedTarget);
    var url = $button.data('url');
    $.get(url, function (response) {
      $(_this).find('.modal-content').html(response.html);
      window.init();
    });
  });
  $('#popup_rent-edit').on('show.bs.modal', function (e) {
    var _this2 = this;

    var $button = $(e.relatedTarget);
    var url = $button.data('url');
    $.get(url, function (response) {
      $(_this2).find('.modal-content').html(response.html);
      $('.js-select-search-single').select2({
        width: "100%"
      });
      window.init();
    });
  });
  $(document).on('hidden.bs.modal', function (e) {
    $(this).find('.modal-content').html('');
  });

  function PrintPageTour() {
    var printWindow = window.open(window.location.href + '/print');
    printWindow.addEventListener('load', function () {
      printWindow.print(); // printWindow.close();
    }, true);
  }

  function PrintDocTour() {
    var printWindow = window.open(window.location.href + '/doc/print');
    printWindow.addEventListener('load', function () {
      printWindow.print(); // printWindow.close();
    }, true);
  }

  function PrintPageTourReverse() {
    var printWindow = window.open(window.location.href + '/print/reverse');
    printWindow.addEventListener('load', function () {
      printWindow.print(); // printWindow.close();
    }, true);
  }

  function countPull() {
    $.get('/admin/pulls/count', function (response) {
      $('.js_pull-count').html(response.view);
    });
  }

  function countNotification() {
    $.get('/admin/notifications/count', function (response) {
      $('.js_noti-count').html(response.view);
    });
  }

  function allDates() {
    $('#all_dates').val($(this).prop('checked') ? 1 : 0);
    $('#all_dates').prop('disabled', $(this).prop('checked') ? false : true);
    $('.js_table-search').submit();
  }

  function onlyVisible() {
    $('#only_visible').val($(this).prop('checked') ? 1 : 0);
    $('#only_visible').prop('disabled', $(this).prop('checked') ? false : true);
    $('.js_table-search').submit();
  }

  function massChangePrice(e) {
    e.preventDefault();
    var $link = $(this);
    var dialog = bootbox.prompt({
      title: $link.data('title'),
      placeholder: "Новая цена",
      message: "<p>Внимание, будет изменена цена всех отображаемых рейсов!<br><br></p>",
      size: "large",
      callback: function callback(result) {
        if (result !== null && $.isNumeric(result)) {
          $('#mass_price_update').prop('disabled', false);
          $('#mass_price_update').val(result);
          $('.js_table-search').submit();
          $('#mass_price_update').prop('disabled', true);
        }
      }
    });
    return false;
  }

  function copyToClipboard(e) {
    e.preventDefault();
    var copyText = $(this).data('text');
    navigator.clipboard.writeText(copyText).then(function () {
      toastr.success('Ссылка успешно скопирована');
    }, function (err) {
      toastr.success('Ссылка не скопирована');
    });
  }

  countPull();
  countNotification();
  setInterval(function () {
    return countPull();
  }, 100000);
  window.countPull = countPull;
  window.countNotification = countNotification;
});

/***/ }),

/***/ "./resources/assets/admin/js/app/user.js":
/*!***********************************************!*\
  !*** ./resources/assets/admin/js/app/user.js ***!
  \***********************************************/
/***/ (() => {

$(document).ready(function () {
  $(document).on('change', '.js_checkbox-all', checkboxAll);
  $(document).on('change', '.js_checkbox', checkbox);

  function checkboxAll() {
    $(this).closest('.js_checkbox-wrap').find('.js_checkbox').prop('checked', $(this).is(':checked')).change();
  }

  function checkbox() {
    var $table = $(this).closest('.js_checkbox-wrap');
    var $checked = $table.find('.js_checkbox:checked');
    var $all = $table.find('.js_checkbox');

    if ($checked.length == $all.length) {
      $table.find('.js_checkbox-all').prop('checked', true);
    } else {
      $table.find('.js_checkbox-all').prop('checked', false);
    }
  }

  jQuery.expr[':'].contains = function (a, i, m) {
    return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
  };

  $(document).on('keyup', '.js_checkbox-search-input', function () {
    var input = $(this),
        value = input.val(),
        checkboxBox = input.closest('.js_checkbox-search').siblings('.js_checkbox-wrap');

    if (value.length > 0) {
      checkboxBox.children().hide();
      checkboxBox.find('label:contains(' + $(this).val() + ')').closest('.checkbox').show().next().filter('.row').show();
    } else {
      checkboxBox.children().show();
    }
  });
  $(document).on('click', '.js_checkbox-search-filter', function () {
    var filter = $(this),
        value = filter.data('filter'),
        checkboxBox = filter.closest('.js_checkbox-search').siblings('.js_checkbox-wrap');
    filter.siblings('.js_checkbox-search-input').val('');

    if (value == 'all') {
      checkboxBox.children().show();
    }

    if (value == 'selected') {
      checkboxBox.children().hide().find('.js_checkbox:checked').parent().show().next().filter('.row').show();
    }
  });
});

/***/ }),

/***/ "./resources/assets/admin/js/custom.js":
/*!*********************************************!*\
  !*** ./resources/assets/admin/js/custom.js ***!
  \*********************************************/
/***/ (() => {

$(document).ready(function () {
  var alertOpen = false;
  $(document).on('change', '.js_table-search :input', searchTable) // .on('select', '.js_table-search select', searchTable)
  // .on('select2:select', '.js_table-search select', searchTable)
  .on('submit', '.js_table-search', searchTable).on('change', '#hide_filter', changeFilterMode).on('click', '.js_table-pagination a', paginateTable).on('click', '.btn-filter-submit', searchTableSubmit).on('click', '.js_table-reset', resetSearchTable).on('panel-form-ajax-success', '.js_form-ajax', resetAjaxForm).on('click', '#side-menu .pjax-link', activeMenu).on('click', '#side-menu .pjax-link', activeMenu);
  $(document).pjax('.pjax-link', '#pjax-container', {
    fragment: '#pjax-container',
    timeout: 20000
  }); // $('#side-menu').on('click', function() {
  //     if ($(window).width() < 768) {
  //         $('.fixed-sidebar').toggleClass('mini-navbar');
  //     }
  // });

  function activeMenu() {
    $('#side-menu li').not('.sub-menu').removeClass('active');
    $(this).closest('li').addClass('active');
  }

  $('#pjax-container').on('pjax:beforeSend', function () {
    $('.wrapper-spinner').show();
  });
  $('#pjax-container').on('pjax:complete', function () {
    $('.wrapper-spinner').hide();
    window.initSortable();
    window.map();
    window.initSortableStation();
    window.initOrderClientPhone();
    window.initTemplateBus();
    $('[data-toggle="tooltip"]').tooltip();
    init();
  });

  function resetAjaxForm(e, response) {
    var $back = $('.js_form-ajax-back');
    if (response.redirect_url) $back.attr('href', response.redirect_url);

    if ($(this).hasClass('js_form-ajax-reset') || response.redirect_url) {
      setTimeout(function () {
        return $back.click();
      }, 500);
    }
  }

  function searchTable(e) {
    e.preventDefault();
    $('.wrapper-spinner').show();
    var $form = $('.js_table-search');
    history.pushState({}, '', '?' + $form.serialize());
    $form.ajaxSubmit({
      success: function success(data) {
        $('.wrapper-spinner').hide();
        renderData(data);
      }
    });
    return false;
  }

  function searchTableSubmit(e) {
    var hide_filter = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
    e.preventDefault();
    $('.wrapper-spinner').show();
    var $form = $('.js_table-submit'); // let condition_str = e.target.checked ? $form.serialize() + "&" + e.target.name + "=1" : $form.serialize();
    // history.pushState({}, '', '?' + condition_str);

    var data = serializeObject($form.serializeArray());

    if (hide_filter) {
      data['hide_filter'] = 1;
    }

    console.log(data);
    var link = $form.data("link");
    $(".close").trigger("click");
    $.ajax({
      type: "POST",
      url: link,
      data: data,
      // serializes the form's elements.
      success: function success(data) {
        $('.wrapper-spinner').hide();
        renderData(data);
      }
    });
    return false;
  }

  function serializeObject(serializeArray) {
    console.log(serializeArray);
    var result = {};
    result['fields'] = [];
    serializeArray.map(function (item) {
      console.log(item['name']);

      if (item['name'] == "fields[]") {
        result['fields'].push(item['value']);
      } else {
        result[item['name']] = item['value'];
      }
    });
    return result;
  }

  function paginateTable(e) {
    e.preventDefault();
    var link = $(this).attr('href');
    $('#js-current-page').val($(this).text());
    $('.wrapper-spinner').show();
    $.get(link, function (data) {
      $('.wrapper-spinner').hide();
      renderData(data);
      $.scrollTo($('.ibox-content'), 400);
    });
    return false;
  }

  function renderData(data) {
    var $table = $('.js_table-wrapper');
    var $pagination = $('.js_table-pagination');
    var $filter = $('.filter-data');
    $table.html(data.view);
    $pagination.html(data.pagination);
    data.filter ? $filter.html(data.filter) : '';
    $('[data-toggle="tooltip"]').tooltip();
    $('.packages-button').addClass('packages-button-active').css('display', 'inline');
    $('.tours-button').removeClass('tours-button-active').css('display', 'none');
  }

  function resetSearchTable(e) {
    e.preventDefault();
    var $form = $('.js_table-search');
    $form.find('input').not('.js_table-reset-no').val('');
    $form.find('textarea').val('');
    $form.find('select').prop('selectedIndex', 0);
    searchTable(e);
    return false;
  }

  function init() {
    $(".js_input-select2").select2({
      allowClear: true
    });
    $('.time-mask').inputmask({
      alias: "h:s"
    });
    $('.js_datepicker').datepicker({
      format: 'dd.mm.yyyy',
      autoclose: true,
      todayHighlight: true,
      language: 'ru',
      dateFormat: 'dd.mm.yyyy',
      changeMonth: true,
      changeYear: true,
      startDate: '-1200m'
    }).on('changeDate', function (ev) {
      if ($(this).hasClass('js_table-reset-no')) $(this).trigger('change');

      if ($(this).data('date')) {
        var $date = $('[name=date]');
        $date.val($('.js_datepicker').datepicker('getFormattedDate'));
        $date.trigger('change');
      }
    });
    $('.js_datepicker2').datepicker({
      format: 'dd.mm.yyyy',
      autoclose: true,
      todayHighlight: true,
      language: 'ru',
      dateFormat: 'dd.mm.yyyy',
      changeMonth: true,
      changeYear: true,
      startDate: '-1200m'
    }).on('changeDate', function (ev) {
      if ($(this).hasClass('js_table-reset-no')) $(this).trigger('change');

      if ($(this).data('date')) {
        var $date = $('[name=date]');
        $date.val($('.js_datepicker').datepicker('getFormattedDate'));
        $date.trigger('change');
      }
    });
    $('.js_datepicker_without_previous').datepicker({
      format: 'dd.mm.yyyy',
      autoclose: true,
      todayHighlight: true,
      language: 'ru',
      dateFormat: 'dd.mm.yyyy',
      changeMonth: true,
      changeYear: true,
      // startDate: '-1200m',
      minDate: 0,
      startDate: new Date()
    }).on('changeDate', function (ev) {
      if ($(this).hasClass('js_table-reset-no')) $(this).trigger('change');

      if ($(this).data('date')) {
        var $date = $('[name=date]');
        $date.val($('.js_datepicker_without_previous').datepicker('getFormattedDate'));
        $date.trigger('change');
      }
    });
    $('.input-daterange').datepicker({
      format: 'yyyy-mm-dd',
      keyboardNavigation: false,
      forceParse: true,
      toggleActive: false,
      language: 'ru',
      autoclose: true
    }).on('changeDate', function (ev) {
      $(this).trigger('input');
    });
    $('#is_egis-yes').on('click', function () {
      if (confirm('Установить обязательные поля для заполнения в соответствии с требованиями ЕГИС?')) {
        $(".js_input-select2").val(['first_name', 'last_name', 'middle_name', 'doc_type', 'doc_number', 'phone', 'birth_day', 'gender', 'country_id']).trigger("change");
      }
    });
    $(".js-select-search-tours").each(function () {
      var $this = $(this);
      $this.select2({
        width: "100%"
      }).on('select2:unselecting', function () {
        $(this).data('unselecting', true);
      }).on('select2:opening', function (e) {
        if ($(this).data('unselecting')) {
          $(this).removeData('unselecting');
          e.preventDefault();
        }
      });
    });
    $(".js-select-company").on('change', function () {
      if ('URLSearchParams' in window) {
        var searchParams = new URLSearchParams(window.location.search);
        searchParams.set("company", $(this).find(":selected").val());
        searchParams.set("driver_id", '');
        window.location.search = searchParams.toString();
      }
    });
    /*$(".js_orders-count_places").mouseleave(function () {
        if (!$('.box').hasClass('.js_div_order_places')) {
            $(".js_order_calculation").click();
        }
    });*/
    //checkAllowIp();
  }

  init();
  window.init = init;
  $(document).on('pjax:beforeSend', checkOrder);

  function checkOrder() {
    if ($('.js_orders-id').val() && $('.js_orders-type').val() === 'no_completed' && !alertOpen) {
      alertOpen = true;

      if (confirm("Бронь будет удалена. Покинуть страницу?")) {
        $('.js_order-cancel').click();
        $(document).one('pjax:success', function () {
          alertOpen = false;
        });
        return true;
      } else {
        $('.wrapper-spinner').hide();
        alertOpen = false;
        return false;
      }
    }
  }
  /*function checkAllowIp() {
      if (SETTING.allowed_ip && SETTING.allowed_ip.length && SETTING.allowed_ip.indexOf(SETTING.CURRENT_IP) < 0 && SETTING.IS_AUTH === '1' && SETTING.IS_OPERATOR === '1') {
          if (window.location.pathname !== '/admin') {
              window.location.replace("/admin");
              alert('Доступ к сайту запрещён с вашего IP-адреса.');
          }
      }
  }*/


  $(document).on('click', '.onoffswitch-checkbox-panel', function () {
    var placeId = $(this).data('place_id');
    console.log(placeId);
    var elPrice = "#order_place_price_" + placeId;
    var elSpan = "#order_place_span_" + placeId;

    if ($(elPrice).is(":visible")) {
      $(elPrice).hide();
      $(elSpan).show();
    } else {
      $(elPrice).show();
      $(elSpan).hide();
    }

    return true;
  });
  $(document).on('click', '.onoffswitch-checkbox', function () {
    var placeId = $(this).data('place_id');
    console.log(placeId);
    var elPrice = "#order_place_price_" + placeId;
    var elSpan = "#order_place_span_" + placeId;

    if ($(elPrice).is(":visible")) {
      $(elPrice).hide();
      $(elSpan).show();
    } else {
      $(elPrice).show();
      $(elSpan).hide();
    } //$("#is_handler_price_" + placeId).click();


    var order_id = $('.js_div_order_places').data('order_id');
    var url = $('.js_div_order_places').data('url');
    var data = $('.js_div_order_places :input').serializeArray();
    $.get(url, data);
    return true;
  });

  function changeFilterMode(e) {
    searchTableSubmit(event, true); // e.preventDefault();

    /*
            $('.wrapper-spinner').show();
            let $form = $('.js_table-submit');
            let condition_str = e.target.checked ? $form.serialize() + "&" + e.target.name + "=1" : $form.serialize();
              history.pushState({}, '', '?' + condition_str);
            $(".close").trigger("click");
            $.ajax({
                type: "GET",
                url: location.href,
                data: condition_str, // serializes the form's elements.
                success: function (data) {
                    $('.wrapper-spinner').hide();
                    renderData(data);
                }
            });
    */
  }
});

/***/ }),

/***/ "./resources/assets/admin/js/index.js":
/*!********************************************!*\
  !*** ./resources/assets/admin/js/index.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _app_components_pusher_easy_alert__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./app/components/pusher/easy-alert */ "./resources/assets/admin/js/app/components/pusher/easy-alert.js");
/* harmony import */ var _app_components_pusher_easy_alert__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_app_components_pusher_easy_alert__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _app_components_pusher_pusher_min__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./app/components/pusher/pusher.min */ "./resources/assets/admin/js/app/components/pusher/pusher.min.js");
/* harmony import */ var _app_components_pusher_pusher_min__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_app_components_pusher_pusher_min__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _app_components_pusher_pusher__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./app/components/pusher/pusher */ "./resources/assets/admin/js/app/components/pusher/pusher.js");
/* harmony import */ var _app_components_pusher_pusher__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_app_components_pusher_pusher__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _app_components_sortable__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./app/components/sortable */ "./resources/assets/admin/js/app/components/sortable.js");
/* harmony import */ var _app_components_ajax__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./app/components/ajax */ "./resources/assets/admin/js/app/components/ajax.js");
/* harmony import */ var _app_components_ajax__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_app_components_ajax__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _app_components_map__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./app/components/map */ "./resources/assets/admin/js/app/components/map.js");
/* harmony import */ var _app_components_map__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_app_components_map__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _app_components_grid__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./app/components/grid */ "./resources/assets/admin/js/app/components/grid.js");
/* harmony import */ var _app_components_grid__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_app_components_grid__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _app_components_datepicker_localization__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./app/components/datepicker_localization */ "./resources/assets/admin/js/app/components/datepicker_localization.js");
/* harmony import */ var _app_components_datepicker_localization__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_app_components_datepicker_localization__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _app_components_import__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./app/components/import */ "./resources/assets/admin/js/app/components/import.js");
/* harmony import */ var _app_components_import__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_app_components_import__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var _app_user__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./app/user */ "./resources/assets/admin/js/app/user.js");
/* harmony import */ var _app_user__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(_app_user__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _app_bus__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./app/bus */ "./resources/assets/admin/js/app/bus.js");
/* harmony import */ var _app_bus__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_app_bus__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var _app_station__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./app/station */ "./resources/assets/admin/js/app/station.js");
/* harmony import */ var _app_schedule__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./app/schedule */ "./resources/assets/admin/js/app/schedule.js");
/* harmony import */ var _app_schedule__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_app_schedule__WEBPACK_IMPORTED_MODULE_12__);
/* harmony import */ var _app_package__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./app/package */ "./resources/assets/admin/js/app/package.js");
/* harmony import */ var _app_package__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(_app_package__WEBPACK_IMPORTED_MODULE_13__);
/* harmony import */ var _app_tour__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./app/tour */ "./resources/assets/admin/js/app/tour.js");
/* harmony import */ var _app_tour__WEBPACK_IMPORTED_MODULE_14___default = /*#__PURE__*/__webpack_require__.n(_app_tour__WEBPACK_IMPORTED_MODULE_14__);
/* harmony import */ var _app_order__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./app/order */ "./resources/assets/admin/js/app/order.js");
/* harmony import */ var _app_order__WEBPACK_IMPORTED_MODULE_15___default = /*#__PURE__*/__webpack_require__.n(_app_order__WEBPACK_IMPORTED_MODULE_15__);
/* harmony import */ var _app_repair__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./app/repair */ "./resources/assets/admin/js/app/repair.js");
/* harmony import */ var _app_repair__WEBPACK_IMPORTED_MODULE_16___default = /*#__PURE__*/__webpack_require__.n(_app_repair__WEBPACK_IMPORTED_MODULE_16__);
/* harmony import */ var _app_route__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./app/route */ "./resources/assets/admin/js/app/route.js");
/* harmony import */ var _app_route__WEBPACK_IMPORTED_MODULE_17___default = /*#__PURE__*/__webpack_require__.n(_app_route__WEBPACK_IMPORTED_MODULE_17__);
/* harmony import */ var _app_tariff__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ./app/tariff */ "./resources/assets/admin/js/app/tariff.js");
/* harmony import */ var _app_tariff__WEBPACK_IMPORTED_MODULE_18___default = /*#__PURE__*/__webpack_require__.n(_app_tariff__WEBPACK_IMPORTED_MODULE_18__);
/* harmony import */ var _app_smsconfig__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ./app/smsconfig */ "./resources/assets/admin/js/app/smsconfig.js");
/* harmony import */ var _app_smsconfig__WEBPACK_IMPORTED_MODULE_19___default = /*#__PURE__*/__webpack_require__.n(_app_smsconfig__WEBPACK_IMPORTED_MODULE_19__);
/* harmony import */ var _app_monitoring__WEBPACK_IMPORTED_MODULE_20__ = __webpack_require__(/*! ./app/monitoring */ "./resources/assets/admin/js/app/monitoring.js");
/* harmony import */ var _app_monitoring__WEBPACK_IMPORTED_MODULE_20___default = /*#__PURE__*/__webpack_require__.n(_app_monitoring__WEBPACK_IMPORTED_MODULE_20__);
/* harmony import */ var _app_settings__WEBPACK_IMPORTED_MODULE_21__ = __webpack_require__(/*! ./app/settings */ "./resources/assets/admin/js/app/settings.js");
/* harmony import */ var _app_settings__WEBPACK_IMPORTED_MODULE_21___default = /*#__PURE__*/__webpack_require__.n(_app_settings__WEBPACK_IMPORTED_MODULE_21__);
/* harmony import */ var _app_change_background__WEBPACK_IMPORTED_MODULE_22__ = __webpack_require__(/*! ./app/change_background */ "./resources/assets/admin/js/app/change_background.js");
/* harmony import */ var _app_change_background__WEBPACK_IMPORTED_MODULE_22___default = /*#__PURE__*/__webpack_require__.n(_app_change_background__WEBPACK_IMPORTED_MODULE_22__);
/* harmony import */ var _app_pusher__WEBPACK_IMPORTED_MODULE_23__ = __webpack_require__(/*! ./app/pusher */ "./resources/assets/admin/js/app/pusher.js");
/* harmony import */ var _app_pusher__WEBPACK_IMPORTED_MODULE_23___default = /*#__PURE__*/__webpack_require__.n(_app_pusher__WEBPACK_IMPORTED_MODULE_23__);
/* harmony import */ var _custom__WEBPACK_IMPORTED_MODULE_24__ = __webpack_require__(/*! ./custom */ "./resources/assets/admin/js/custom.js");
/* harmony import */ var _custom__WEBPACK_IMPORTED_MODULE_24___default = /*#__PURE__*/__webpack_require__.n(_custom__WEBPACK_IMPORTED_MODULE_24__);


























/***/ }),

/***/ "./resources/assets/admin/less/forBusScale.css":
/*!*****************************************************!*\
  !*** ./resources/assets/admin/less/forBusScale.css ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/assets/driver/css/main.css":
/*!**********************************************!*\
  !*** ./resources/assets/driver/css/main.css ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/assets/index/css/plugins/froala.css":
/*!*******************************************************!*\
  !*** ./resources/assets/index/css/plugins/froala.css ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/assets/index/plugins/datepicker3.css":
/*!********************************************************!*\
  !*** ./resources/assets/index/plugins/datepicker3.css ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/assets/index/css/main.css":
/*!*********************************************!*\
  !*** ./resources/assets/index/css/main.css ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/assets/admin/less/styles.less":
/*!*************************************************!*\
  !*** ./resources/assets/admin/less/styles.less ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./node_modules/toastr/toastr.less":
/*!*****************************************!*\
  !*** ./node_modules/toastr/toastr.less ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/assets/index/css/bootstrap.scss":
/*!***************************************************!*\
  !*** ./resources/assets/index/css/bootstrap.scss ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./resources/assets/admin/less/mainStyles.css":
/*!****************************************************!*\
  !*** ./resources/assets/admin/less/mainStyles.css ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./node_modules/sortablejs/modular/sortable.esm.js":
/*!*********************************************************!*\
  !*** ./node_modules/sortablejs/modular/sortable.esm.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "MultiDrag": () => (/* binding */ MultiDragPlugin),
/* harmony export */   "Sortable": () => (/* binding */ Sortable),
/* harmony export */   "Swap": () => (/* binding */ SwapPlugin),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**!
 * Sortable 1.14.0
 * @author	RubaXa   <trash@rubaxa.org>
 * @author	owenm    <owen23355@gmail.com>
 * @license MIT
 */
function ownKeys(object, enumerableOnly) {
  var keys = Object.keys(object);

  if (Object.getOwnPropertySymbols) {
    var symbols = Object.getOwnPropertySymbols(object);

    if (enumerableOnly) {
      symbols = symbols.filter(function (sym) {
        return Object.getOwnPropertyDescriptor(object, sym).enumerable;
      });
    }

    keys.push.apply(keys, symbols);
  }

  return keys;
}

function _objectSpread2(target) {
  for (var i = 1; i < arguments.length; i++) {
    var source = arguments[i] != null ? arguments[i] : {};

    if (i % 2) {
      ownKeys(Object(source), true).forEach(function (key) {
        _defineProperty(target, key, source[key]);
      });
    } else if (Object.getOwnPropertyDescriptors) {
      Object.defineProperties(target, Object.getOwnPropertyDescriptors(source));
    } else {
      ownKeys(Object(source)).forEach(function (key) {
        Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));
      });
    }
  }

  return target;
}

function _typeof(obj) {
  "@babel/helpers - typeof";

  if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
    _typeof = function (obj) {
      return typeof obj;
    };
  } else {
    _typeof = function (obj) {
      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };
  }

  return _typeof(obj);
}

function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

function _extends() {
  _extends = Object.assign || function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];

      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }

    return target;
  };

  return _extends.apply(this, arguments);
}

function _objectWithoutPropertiesLoose(source, excluded) {
  if (source == null) return {};
  var target = {};
  var sourceKeys = Object.keys(source);
  var key, i;

  for (i = 0; i < sourceKeys.length; i++) {
    key = sourceKeys[i];
    if (excluded.indexOf(key) >= 0) continue;
    target[key] = source[key];
  }

  return target;
}

function _objectWithoutProperties(source, excluded) {
  if (source == null) return {};

  var target = _objectWithoutPropertiesLoose(source, excluded);

  var key, i;

  if (Object.getOwnPropertySymbols) {
    var sourceSymbolKeys = Object.getOwnPropertySymbols(source);

    for (i = 0; i < sourceSymbolKeys.length; i++) {
      key = sourceSymbolKeys[i];
      if (excluded.indexOf(key) >= 0) continue;
      if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue;
      target[key] = source[key];
    }
  }

  return target;
}

function _toConsumableArray(arr) {
  return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread();
}

function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) return _arrayLikeToArray(arr);
}

function _iterableToArray(iter) {
  if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
}

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return _arrayLikeToArray(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
}

function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];

  return arr2;
}

function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

var version = "1.14.0";

function userAgent(pattern) {
  if (typeof window !== 'undefined' && window.navigator) {
    return !! /*@__PURE__*/navigator.userAgent.match(pattern);
  }
}

var IE11OrLess = userAgent(/(?:Trident.*rv[ :]?11\.|msie|iemobile|Windows Phone)/i);
var Edge = userAgent(/Edge/i);
var FireFox = userAgent(/firefox/i);
var Safari = userAgent(/safari/i) && !userAgent(/chrome/i) && !userAgent(/android/i);
var IOS = userAgent(/iP(ad|od|hone)/i);
var ChromeForAndroid = userAgent(/chrome/i) && userAgent(/android/i);

var captureMode = {
  capture: false,
  passive: false
};

function on(el, event, fn) {
  el.addEventListener(event, fn, !IE11OrLess && captureMode);
}

function off(el, event, fn) {
  el.removeEventListener(event, fn, !IE11OrLess && captureMode);
}

function matches(
/**HTMLElement*/
el,
/**String*/
selector) {
  if (!selector) return;
  selector[0] === '>' && (selector = selector.substring(1));

  if (el) {
    try {
      if (el.matches) {
        return el.matches(selector);
      } else if (el.msMatchesSelector) {
        return el.msMatchesSelector(selector);
      } else if (el.webkitMatchesSelector) {
        return el.webkitMatchesSelector(selector);
      }
    } catch (_) {
      return false;
    }
  }

  return false;
}

function getParentOrHost(el) {
  return el.host && el !== document && el.host.nodeType ? el.host : el.parentNode;
}

function closest(
/**HTMLElement*/
el,
/**String*/
selector,
/**HTMLElement*/
ctx, includeCTX) {
  if (el) {
    ctx = ctx || document;

    do {
      if (selector != null && (selector[0] === '>' ? el.parentNode === ctx && matches(el, selector) : matches(el, selector)) || includeCTX && el === ctx) {
        return el;
      }

      if (el === ctx) break;
      /* jshint boss:true */
    } while (el = getParentOrHost(el));
  }

  return null;
}

var R_SPACE = /\s+/g;

function toggleClass(el, name, state) {
  if (el && name) {
    if (el.classList) {
      el.classList[state ? 'add' : 'remove'](name);
    } else {
      var className = (' ' + el.className + ' ').replace(R_SPACE, ' ').replace(' ' + name + ' ', ' ');
      el.className = (className + (state ? ' ' + name : '')).replace(R_SPACE, ' ');
    }
  }
}

function css(el, prop, val) {
  var style = el && el.style;

  if (style) {
    if (val === void 0) {
      if (document.defaultView && document.defaultView.getComputedStyle) {
        val = document.defaultView.getComputedStyle(el, '');
      } else if (el.currentStyle) {
        val = el.currentStyle;
      }

      return prop === void 0 ? val : val[prop];
    } else {
      if (!(prop in style) && prop.indexOf('webkit') === -1) {
        prop = '-webkit-' + prop;
      }

      style[prop] = val + (typeof val === 'string' ? '' : 'px');
    }
  }
}

function matrix(el, selfOnly) {
  var appliedTransforms = '';

  if (typeof el === 'string') {
    appliedTransforms = el;
  } else {
    do {
      var transform = css(el, 'transform');

      if (transform && transform !== 'none') {
        appliedTransforms = transform + ' ' + appliedTransforms;
      }
      /* jshint boss:true */

    } while (!selfOnly && (el = el.parentNode));
  }

  var matrixFn = window.DOMMatrix || window.WebKitCSSMatrix || window.CSSMatrix || window.MSCSSMatrix;
  /*jshint -W056 */

  return matrixFn && new matrixFn(appliedTransforms);
}

function find(ctx, tagName, iterator) {
  if (ctx) {
    var list = ctx.getElementsByTagName(tagName),
        i = 0,
        n = list.length;

    if (iterator) {
      for (; i < n; i++) {
        iterator(list[i], i);
      }
    }

    return list;
  }

  return [];
}

function getWindowScrollingElement() {
  var scrollingElement = document.scrollingElement;

  if (scrollingElement) {
    return scrollingElement;
  } else {
    return document.documentElement;
  }
}
/**
 * Returns the "bounding client rect" of given element
 * @param  {HTMLElement} el                       The element whose boundingClientRect is wanted
 * @param  {[Boolean]} relativeToContainingBlock  Whether the rect should be relative to the containing block of (including) the container
 * @param  {[Boolean]} relativeToNonStaticParent  Whether the rect should be relative to the relative parent of (including) the contaienr
 * @param  {[Boolean]} undoScale                  Whether the container's scale() should be undone
 * @param  {[HTMLElement]} container              The parent the element will be placed in
 * @return {Object}                               The boundingClientRect of el, with specified adjustments
 */


function getRect(el, relativeToContainingBlock, relativeToNonStaticParent, undoScale, container) {
  if (!el.getBoundingClientRect && el !== window) return;
  var elRect, top, left, bottom, right, height, width;

  if (el !== window && el.parentNode && el !== getWindowScrollingElement()) {
    elRect = el.getBoundingClientRect();
    top = elRect.top;
    left = elRect.left;
    bottom = elRect.bottom;
    right = elRect.right;
    height = elRect.height;
    width = elRect.width;
  } else {
    top = 0;
    left = 0;
    bottom = window.innerHeight;
    right = window.innerWidth;
    height = window.innerHeight;
    width = window.innerWidth;
  }

  if ((relativeToContainingBlock || relativeToNonStaticParent) && el !== window) {
    // Adjust for translate()
    container = container || el.parentNode; // solves #1123 (see: https://stackoverflow.com/a/37953806/6088312)
    // Not needed on <= IE11

    if (!IE11OrLess) {
      do {
        if (container && container.getBoundingClientRect && (css(container, 'transform') !== 'none' || relativeToNonStaticParent && css(container, 'position') !== 'static')) {
          var containerRect = container.getBoundingClientRect(); // Set relative to edges of padding box of container

          top -= containerRect.top + parseInt(css(container, 'border-top-width'));
          left -= containerRect.left + parseInt(css(container, 'border-left-width'));
          bottom = top + elRect.height;
          right = left + elRect.width;
          break;
        }
        /* jshint boss:true */

      } while (container = container.parentNode);
    }
  }

  if (undoScale && el !== window) {
    // Adjust for scale()
    var elMatrix = matrix(container || el),
        scaleX = elMatrix && elMatrix.a,
        scaleY = elMatrix && elMatrix.d;

    if (elMatrix) {
      top /= scaleY;
      left /= scaleX;
      width /= scaleX;
      height /= scaleY;
      bottom = top + height;
      right = left + width;
    }
  }

  return {
    top: top,
    left: left,
    bottom: bottom,
    right: right,
    width: width,
    height: height
  };
}
/**
 * Checks if a side of an element is scrolled past a side of its parents
 * @param  {HTMLElement}  el           The element who's side being scrolled out of view is in question
 * @param  {String}       elSide       Side of the element in question ('top', 'left', 'right', 'bottom')
 * @param  {String}       parentSide   Side of the parent in question ('top', 'left', 'right', 'bottom')
 * @return {HTMLElement}               The parent scroll element that the el's side is scrolled past, or null if there is no such element
 */


function isScrolledPast(el, elSide, parentSide) {
  var parent = getParentAutoScrollElement(el, true),
      elSideVal = getRect(el)[elSide];
  /* jshint boss:true */

  while (parent) {
    var parentSideVal = getRect(parent)[parentSide],
        visible = void 0;

    if (parentSide === 'top' || parentSide === 'left') {
      visible = elSideVal >= parentSideVal;
    } else {
      visible = elSideVal <= parentSideVal;
    }

    if (!visible) return parent;
    if (parent === getWindowScrollingElement()) break;
    parent = getParentAutoScrollElement(parent, false);
  }

  return false;
}
/**
 * Gets nth child of el, ignoring hidden children, sortable's elements (does not ignore clone if it's visible)
 * and non-draggable elements
 * @param  {HTMLElement} el       The parent element
 * @param  {Number} childNum      The index of the child
 * @param  {Object} options       Parent Sortable's options
 * @return {HTMLElement}          The child at index childNum, or null if not found
 */


function getChild(el, childNum, options, includeDragEl) {
  var currentChild = 0,
      i = 0,
      children = el.children;

  while (i < children.length) {
    if (children[i].style.display !== 'none' && children[i] !== Sortable.ghost && (includeDragEl || children[i] !== Sortable.dragged) && closest(children[i], options.draggable, el, false)) {
      if (currentChild === childNum) {
        return children[i];
      }

      currentChild++;
    }

    i++;
  }

  return null;
}
/**
 * Gets the last child in the el, ignoring ghostEl or invisible elements (clones)
 * @param  {HTMLElement} el       Parent element
 * @param  {selector} selector    Any other elements that should be ignored
 * @return {HTMLElement}          The last child, ignoring ghostEl
 */


function lastChild(el, selector) {
  var last = el.lastElementChild;

  while (last && (last === Sortable.ghost || css(last, 'display') === 'none' || selector && !matches(last, selector))) {
    last = last.previousElementSibling;
  }

  return last || null;
}
/**
 * Returns the index of an element within its parent for a selected set of
 * elements
 * @param  {HTMLElement} el
 * @param  {selector} selector
 * @return {number}
 */


function index(el, selector) {
  var index = 0;

  if (!el || !el.parentNode) {
    return -1;
  }
  /* jshint boss:true */


  while (el = el.previousElementSibling) {
    if (el.nodeName.toUpperCase() !== 'TEMPLATE' && el !== Sortable.clone && (!selector || matches(el, selector))) {
      index++;
    }
  }

  return index;
}
/**
 * Returns the scroll offset of the given element, added with all the scroll offsets of parent elements.
 * The value is returned in real pixels.
 * @param  {HTMLElement} el
 * @return {Array}             Offsets in the format of [left, top]
 */


function getRelativeScrollOffset(el) {
  var offsetLeft = 0,
      offsetTop = 0,
      winScroller = getWindowScrollingElement();

  if (el) {
    do {
      var elMatrix = matrix(el),
          scaleX = elMatrix.a,
          scaleY = elMatrix.d;
      offsetLeft += el.scrollLeft * scaleX;
      offsetTop += el.scrollTop * scaleY;
    } while (el !== winScroller && (el = el.parentNode));
  }

  return [offsetLeft, offsetTop];
}
/**
 * Returns the index of the object within the given array
 * @param  {Array} arr   Array that may or may not hold the object
 * @param  {Object} obj  An object that has a key-value pair unique to and identical to a key-value pair in the object you want to find
 * @return {Number}      The index of the object in the array, or -1
 */


function indexOfObject(arr, obj) {
  for (var i in arr) {
    if (!arr.hasOwnProperty(i)) continue;

    for (var key in obj) {
      if (obj.hasOwnProperty(key) && obj[key] === arr[i][key]) return Number(i);
    }
  }

  return -1;
}

function getParentAutoScrollElement(el, includeSelf) {
  // skip to window
  if (!el || !el.getBoundingClientRect) return getWindowScrollingElement();
  var elem = el;
  var gotSelf = false;

  do {
    // we don't need to get elem css if it isn't even overflowing in the first place (performance)
    if (elem.clientWidth < elem.scrollWidth || elem.clientHeight < elem.scrollHeight) {
      var elemCSS = css(elem);

      if (elem.clientWidth < elem.scrollWidth && (elemCSS.overflowX == 'auto' || elemCSS.overflowX == 'scroll') || elem.clientHeight < elem.scrollHeight && (elemCSS.overflowY == 'auto' || elemCSS.overflowY == 'scroll')) {
        if (!elem.getBoundingClientRect || elem === document.body) return getWindowScrollingElement();
        if (gotSelf || includeSelf) return elem;
        gotSelf = true;
      }
    }
    /* jshint boss:true */

  } while (elem = elem.parentNode);

  return getWindowScrollingElement();
}

function extend(dst, src) {
  if (dst && src) {
    for (var key in src) {
      if (src.hasOwnProperty(key)) {
        dst[key] = src[key];
      }
    }
  }

  return dst;
}

function isRectEqual(rect1, rect2) {
  return Math.round(rect1.top) === Math.round(rect2.top) && Math.round(rect1.left) === Math.round(rect2.left) && Math.round(rect1.height) === Math.round(rect2.height) && Math.round(rect1.width) === Math.round(rect2.width);
}

var _throttleTimeout;

function throttle(callback, ms) {
  return function () {
    if (!_throttleTimeout) {
      var args = arguments,
          _this = this;

      if (args.length === 1) {
        callback.call(_this, args[0]);
      } else {
        callback.apply(_this, args);
      }

      _throttleTimeout = setTimeout(function () {
        _throttleTimeout = void 0;
      }, ms);
    }
  };
}

function cancelThrottle() {
  clearTimeout(_throttleTimeout);
  _throttleTimeout = void 0;
}

function scrollBy(el, x, y) {
  el.scrollLeft += x;
  el.scrollTop += y;
}

function clone(el) {
  var Polymer = window.Polymer;
  var $ = window.jQuery || window.Zepto;

  if (Polymer && Polymer.dom) {
    return Polymer.dom(el).cloneNode(true);
  } else if ($) {
    return $(el).clone(true)[0];
  } else {
    return el.cloneNode(true);
  }
}

function setRect(el, rect) {
  css(el, 'position', 'absolute');
  css(el, 'top', rect.top);
  css(el, 'left', rect.left);
  css(el, 'width', rect.width);
  css(el, 'height', rect.height);
}

function unsetRect(el) {
  css(el, 'position', '');
  css(el, 'top', '');
  css(el, 'left', '');
  css(el, 'width', '');
  css(el, 'height', '');
}

var expando = 'Sortable' + new Date().getTime();

function AnimationStateManager() {
  var animationStates = [],
      animationCallbackId;
  return {
    captureAnimationState: function captureAnimationState() {
      animationStates = [];
      if (!this.options.animation) return;
      var children = [].slice.call(this.el.children);
      children.forEach(function (child) {
        if (css(child, 'display') === 'none' || child === Sortable.ghost) return;
        animationStates.push({
          target: child,
          rect: getRect(child)
        });

        var fromRect = _objectSpread2({}, animationStates[animationStates.length - 1].rect); // If animating: compensate for current animation


        if (child.thisAnimationDuration) {
          var childMatrix = matrix(child, true);

          if (childMatrix) {
            fromRect.top -= childMatrix.f;
            fromRect.left -= childMatrix.e;
          }
        }

        child.fromRect = fromRect;
      });
    },
    addAnimationState: function addAnimationState(state) {
      animationStates.push(state);
    },
    removeAnimationState: function removeAnimationState(target) {
      animationStates.splice(indexOfObject(animationStates, {
        target: target
      }), 1);
    },
    animateAll: function animateAll(callback) {
      var _this = this;

      if (!this.options.animation) {
        clearTimeout(animationCallbackId);
        if (typeof callback === 'function') callback();
        return;
      }

      var animating = false,
          animationTime = 0;
      animationStates.forEach(function (state) {
        var time = 0,
            target = state.target,
            fromRect = target.fromRect,
            toRect = getRect(target),
            prevFromRect = target.prevFromRect,
            prevToRect = target.prevToRect,
            animatingRect = state.rect,
            targetMatrix = matrix(target, true);

        if (targetMatrix) {
          // Compensate for current animation
          toRect.top -= targetMatrix.f;
          toRect.left -= targetMatrix.e;
        }

        target.toRect = toRect;

        if (target.thisAnimationDuration) {
          // Could also check if animatingRect is between fromRect and toRect
          if (isRectEqual(prevFromRect, toRect) && !isRectEqual(fromRect, toRect) && // Make sure animatingRect is on line between toRect & fromRect
          (animatingRect.top - toRect.top) / (animatingRect.left - toRect.left) === (fromRect.top - toRect.top) / (fromRect.left - toRect.left)) {
            // If returning to same place as started from animation and on same axis
            time = calculateRealTime(animatingRect, prevFromRect, prevToRect, _this.options);
          }
        } // if fromRect != toRect: animate


        if (!isRectEqual(toRect, fromRect)) {
          target.prevFromRect = fromRect;
          target.prevToRect = toRect;

          if (!time) {
            time = _this.options.animation;
          }

          _this.animate(target, animatingRect, toRect, time);
        }

        if (time) {
          animating = true;
          animationTime = Math.max(animationTime, time);
          clearTimeout(target.animationResetTimer);
          target.animationResetTimer = setTimeout(function () {
            target.animationTime = 0;
            target.prevFromRect = null;
            target.fromRect = null;
            target.prevToRect = null;
            target.thisAnimationDuration = null;
          }, time);
          target.thisAnimationDuration = time;
        }
      });
      clearTimeout(animationCallbackId);

      if (!animating) {
        if (typeof callback === 'function') callback();
      } else {
        animationCallbackId = setTimeout(function () {
          if (typeof callback === 'function') callback();
        }, animationTime);
      }

      animationStates = [];
    },
    animate: function animate(target, currentRect, toRect, duration) {
      if (duration) {
        css(target, 'transition', '');
        css(target, 'transform', '');
        var elMatrix = matrix(this.el),
            scaleX = elMatrix && elMatrix.a,
            scaleY = elMatrix && elMatrix.d,
            translateX = (currentRect.left - toRect.left) / (scaleX || 1),
            translateY = (currentRect.top - toRect.top) / (scaleY || 1);
        target.animatingX = !!translateX;
        target.animatingY = !!translateY;
        css(target, 'transform', 'translate3d(' + translateX + 'px,' + translateY + 'px,0)');
        this.forRepaintDummy = repaint(target); // repaint

        css(target, 'transition', 'transform ' + duration + 'ms' + (this.options.easing ? ' ' + this.options.easing : ''));
        css(target, 'transform', 'translate3d(0,0,0)');
        typeof target.animated === 'number' && clearTimeout(target.animated);
        target.animated = setTimeout(function () {
          css(target, 'transition', '');
          css(target, 'transform', '');
          target.animated = false;
          target.animatingX = false;
          target.animatingY = false;
        }, duration);
      }
    }
  };
}

function repaint(target) {
  return target.offsetWidth;
}

function calculateRealTime(animatingRect, fromRect, toRect, options) {
  return Math.sqrt(Math.pow(fromRect.top - animatingRect.top, 2) + Math.pow(fromRect.left - animatingRect.left, 2)) / Math.sqrt(Math.pow(fromRect.top - toRect.top, 2) + Math.pow(fromRect.left - toRect.left, 2)) * options.animation;
}

var plugins = [];
var defaults = {
  initializeByDefault: true
};
var PluginManager = {
  mount: function mount(plugin) {
    // Set default static properties
    for (var option in defaults) {
      if (defaults.hasOwnProperty(option) && !(option in plugin)) {
        plugin[option] = defaults[option];
      }
    }

    plugins.forEach(function (p) {
      if (p.pluginName === plugin.pluginName) {
        throw "Sortable: Cannot mount plugin ".concat(plugin.pluginName, " more than once");
      }
    });
    plugins.push(plugin);
  },
  pluginEvent: function pluginEvent(eventName, sortable, evt) {
    var _this = this;

    this.eventCanceled = false;

    evt.cancel = function () {
      _this.eventCanceled = true;
    };

    var eventNameGlobal = eventName + 'Global';
    plugins.forEach(function (plugin) {
      if (!sortable[plugin.pluginName]) return; // Fire global events if it exists in this sortable

      if (sortable[plugin.pluginName][eventNameGlobal]) {
        sortable[plugin.pluginName][eventNameGlobal](_objectSpread2({
          sortable: sortable
        }, evt));
      } // Only fire plugin event if plugin is enabled in this sortable,
      // and plugin has event defined


      if (sortable.options[plugin.pluginName] && sortable[plugin.pluginName][eventName]) {
        sortable[plugin.pluginName][eventName](_objectSpread2({
          sortable: sortable
        }, evt));
      }
    });
  },
  initializePlugins: function initializePlugins(sortable, el, defaults, options) {
    plugins.forEach(function (plugin) {
      var pluginName = plugin.pluginName;
      if (!sortable.options[pluginName] && !plugin.initializeByDefault) return;
      var initialized = new plugin(sortable, el, sortable.options);
      initialized.sortable = sortable;
      initialized.options = sortable.options;
      sortable[pluginName] = initialized; // Add default options from plugin

      _extends(defaults, initialized.defaults);
    });

    for (var option in sortable.options) {
      if (!sortable.options.hasOwnProperty(option)) continue;
      var modified = this.modifyOption(sortable, option, sortable.options[option]);

      if (typeof modified !== 'undefined') {
        sortable.options[option] = modified;
      }
    }
  },
  getEventProperties: function getEventProperties(name, sortable) {
    var eventProperties = {};
    plugins.forEach(function (plugin) {
      if (typeof plugin.eventProperties !== 'function') return;

      _extends(eventProperties, plugin.eventProperties.call(sortable[plugin.pluginName], name));
    });
    return eventProperties;
  },
  modifyOption: function modifyOption(sortable, name, value) {
    var modifiedValue;
    plugins.forEach(function (plugin) {
      // Plugin must exist on the Sortable
      if (!sortable[plugin.pluginName]) return; // If static option listener exists for this option, call in the context of the Sortable's instance of this plugin

      if (plugin.optionListeners && typeof plugin.optionListeners[name] === 'function') {
        modifiedValue = plugin.optionListeners[name].call(sortable[plugin.pluginName], value);
      }
    });
    return modifiedValue;
  }
};

function dispatchEvent(_ref) {
  var sortable = _ref.sortable,
      rootEl = _ref.rootEl,
      name = _ref.name,
      targetEl = _ref.targetEl,
      cloneEl = _ref.cloneEl,
      toEl = _ref.toEl,
      fromEl = _ref.fromEl,
      oldIndex = _ref.oldIndex,
      newIndex = _ref.newIndex,
      oldDraggableIndex = _ref.oldDraggableIndex,
      newDraggableIndex = _ref.newDraggableIndex,
      originalEvent = _ref.originalEvent,
      putSortable = _ref.putSortable,
      extraEventProperties = _ref.extraEventProperties;
  sortable = sortable || rootEl && rootEl[expando];
  if (!sortable) return;
  var evt,
      options = sortable.options,
      onName = 'on' + name.charAt(0).toUpperCase() + name.substr(1); // Support for new CustomEvent feature

  if (window.CustomEvent && !IE11OrLess && !Edge) {
    evt = new CustomEvent(name, {
      bubbles: true,
      cancelable: true
    });
  } else {
    evt = document.createEvent('Event');
    evt.initEvent(name, true, true);
  }

  evt.to = toEl || rootEl;
  evt.from = fromEl || rootEl;
  evt.item = targetEl || rootEl;
  evt.clone = cloneEl;
  evt.oldIndex = oldIndex;
  evt.newIndex = newIndex;
  evt.oldDraggableIndex = oldDraggableIndex;
  evt.newDraggableIndex = newDraggableIndex;
  evt.originalEvent = originalEvent;
  evt.pullMode = putSortable ? putSortable.lastPutMode : undefined;

  var allEventProperties = _objectSpread2(_objectSpread2({}, extraEventProperties), PluginManager.getEventProperties(name, sortable));

  for (var option in allEventProperties) {
    evt[option] = allEventProperties[option];
  }

  if (rootEl) {
    rootEl.dispatchEvent(evt);
  }

  if (options[onName]) {
    options[onName].call(sortable, evt);
  }
}

var _excluded = ["evt"];

var pluginEvent = function pluginEvent(eventName, sortable) {
  var _ref = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {},
      originalEvent = _ref.evt,
      data = _objectWithoutProperties(_ref, _excluded);

  PluginManager.pluginEvent.bind(Sortable)(eventName, sortable, _objectSpread2({
    dragEl: dragEl,
    parentEl: parentEl,
    ghostEl: ghostEl,
    rootEl: rootEl,
    nextEl: nextEl,
    lastDownEl: lastDownEl,
    cloneEl: cloneEl,
    cloneHidden: cloneHidden,
    dragStarted: moved,
    putSortable: putSortable,
    activeSortable: Sortable.active,
    originalEvent: originalEvent,
    oldIndex: oldIndex,
    oldDraggableIndex: oldDraggableIndex,
    newIndex: newIndex,
    newDraggableIndex: newDraggableIndex,
    hideGhostForTarget: _hideGhostForTarget,
    unhideGhostForTarget: _unhideGhostForTarget,
    cloneNowHidden: function cloneNowHidden() {
      cloneHidden = true;
    },
    cloneNowShown: function cloneNowShown() {
      cloneHidden = false;
    },
    dispatchSortableEvent: function dispatchSortableEvent(name) {
      _dispatchEvent({
        sortable: sortable,
        name: name,
        originalEvent: originalEvent
      });
    }
  }, data));
};

function _dispatchEvent(info) {
  dispatchEvent(_objectSpread2({
    putSortable: putSortable,
    cloneEl: cloneEl,
    targetEl: dragEl,
    rootEl: rootEl,
    oldIndex: oldIndex,
    oldDraggableIndex: oldDraggableIndex,
    newIndex: newIndex,
    newDraggableIndex: newDraggableIndex
  }, info));
}

var dragEl,
    parentEl,
    ghostEl,
    rootEl,
    nextEl,
    lastDownEl,
    cloneEl,
    cloneHidden,
    oldIndex,
    newIndex,
    oldDraggableIndex,
    newDraggableIndex,
    activeGroup,
    putSortable,
    awaitingDragStarted = false,
    ignoreNextClick = false,
    sortables = [],
    tapEvt,
    touchEvt,
    lastDx,
    lastDy,
    tapDistanceLeft,
    tapDistanceTop,
    moved,
    lastTarget,
    lastDirection,
    pastFirstInvertThresh = false,
    isCircumstantialInvert = false,
    targetMoveDistance,
    // For positioning ghost absolutely
ghostRelativeParent,
    ghostRelativeParentInitialScroll = [],
    // (left, top)
_silent = false,
    savedInputChecked = [];
/** @const */

var documentExists = typeof document !== 'undefined',
    PositionGhostAbsolutely = IOS,
    CSSFloatProperty = Edge || IE11OrLess ? 'cssFloat' : 'float',
    // This will not pass for IE9, because IE9 DnD only works on anchors
supportDraggable = documentExists && !ChromeForAndroid && !IOS && 'draggable' in document.createElement('div'),
    supportCssPointerEvents = function () {
  if (!documentExists) return; // false when <= IE11

  if (IE11OrLess) {
    return false;
  }

  var el = document.createElement('x');
  el.style.cssText = 'pointer-events:auto';
  return el.style.pointerEvents === 'auto';
}(),
    _detectDirection = function _detectDirection(el, options) {
  var elCSS = css(el),
      elWidth = parseInt(elCSS.width) - parseInt(elCSS.paddingLeft) - parseInt(elCSS.paddingRight) - parseInt(elCSS.borderLeftWidth) - parseInt(elCSS.borderRightWidth),
      child1 = getChild(el, 0, options),
      child2 = getChild(el, 1, options),
      firstChildCSS = child1 && css(child1),
      secondChildCSS = child2 && css(child2),
      firstChildWidth = firstChildCSS && parseInt(firstChildCSS.marginLeft) + parseInt(firstChildCSS.marginRight) + getRect(child1).width,
      secondChildWidth = secondChildCSS && parseInt(secondChildCSS.marginLeft) + parseInt(secondChildCSS.marginRight) + getRect(child2).width;

  if (elCSS.display === 'flex') {
    return elCSS.flexDirection === 'column' || elCSS.flexDirection === 'column-reverse' ? 'vertical' : 'horizontal';
  }

  if (elCSS.display === 'grid') {
    return elCSS.gridTemplateColumns.split(' ').length <= 1 ? 'vertical' : 'horizontal';
  }

  if (child1 && firstChildCSS["float"] && firstChildCSS["float"] !== 'none') {
    var touchingSideChild2 = firstChildCSS["float"] === 'left' ? 'left' : 'right';
    return child2 && (secondChildCSS.clear === 'both' || secondChildCSS.clear === touchingSideChild2) ? 'vertical' : 'horizontal';
  }

  return child1 && (firstChildCSS.display === 'block' || firstChildCSS.display === 'flex' || firstChildCSS.display === 'table' || firstChildCSS.display === 'grid' || firstChildWidth >= elWidth && elCSS[CSSFloatProperty] === 'none' || child2 && elCSS[CSSFloatProperty] === 'none' && firstChildWidth + secondChildWidth > elWidth) ? 'vertical' : 'horizontal';
},
    _dragElInRowColumn = function _dragElInRowColumn(dragRect, targetRect, vertical) {
  var dragElS1Opp = vertical ? dragRect.left : dragRect.top,
      dragElS2Opp = vertical ? dragRect.right : dragRect.bottom,
      dragElOppLength = vertical ? dragRect.width : dragRect.height,
      targetS1Opp = vertical ? targetRect.left : targetRect.top,
      targetS2Opp = vertical ? targetRect.right : targetRect.bottom,
      targetOppLength = vertical ? targetRect.width : targetRect.height;
  return dragElS1Opp === targetS1Opp || dragElS2Opp === targetS2Opp || dragElS1Opp + dragElOppLength / 2 === targetS1Opp + targetOppLength / 2;
},

/**
 * Detects first nearest empty sortable to X and Y position using emptyInsertThreshold.
 * @param  {Number} x      X position
 * @param  {Number} y      Y position
 * @return {HTMLElement}   Element of the first found nearest Sortable
 */
_detectNearestEmptySortable = function _detectNearestEmptySortable(x, y) {
  var ret;
  sortables.some(function (sortable) {
    var threshold = sortable[expando].options.emptyInsertThreshold;
    if (!threshold || lastChild(sortable)) return;
    var rect = getRect(sortable),
        insideHorizontally = x >= rect.left - threshold && x <= rect.right + threshold,
        insideVertically = y >= rect.top - threshold && y <= rect.bottom + threshold;

    if (insideHorizontally && insideVertically) {
      return ret = sortable;
    }
  });
  return ret;
},
    _prepareGroup = function _prepareGroup(options) {
  function toFn(value, pull) {
    return function (to, from, dragEl, evt) {
      var sameGroup = to.options.group.name && from.options.group.name && to.options.group.name === from.options.group.name;

      if (value == null && (pull || sameGroup)) {
        // Default pull value
        // Default pull and put value if same group
        return true;
      } else if (value == null || value === false) {
        return false;
      } else if (pull && value === 'clone') {
        return value;
      } else if (typeof value === 'function') {
        return toFn(value(to, from, dragEl, evt), pull)(to, from, dragEl, evt);
      } else {
        var otherGroup = (pull ? to : from).options.group.name;
        return value === true || typeof value === 'string' && value === otherGroup || value.join && value.indexOf(otherGroup) > -1;
      }
    };
  }

  var group = {};
  var originalGroup = options.group;

  if (!originalGroup || _typeof(originalGroup) != 'object') {
    originalGroup = {
      name: originalGroup
    };
  }

  group.name = originalGroup.name;
  group.checkPull = toFn(originalGroup.pull, true);
  group.checkPut = toFn(originalGroup.put);
  group.revertClone = originalGroup.revertClone;
  options.group = group;
},
    _hideGhostForTarget = function _hideGhostForTarget() {
  if (!supportCssPointerEvents && ghostEl) {
    css(ghostEl, 'display', 'none');
  }
},
    _unhideGhostForTarget = function _unhideGhostForTarget() {
  if (!supportCssPointerEvents && ghostEl) {
    css(ghostEl, 'display', '');
  }
}; // #1184 fix - Prevent click event on fallback if dragged but item not changed position


if (documentExists) {
  document.addEventListener('click', function (evt) {
    if (ignoreNextClick) {
      evt.preventDefault();
      evt.stopPropagation && evt.stopPropagation();
      evt.stopImmediatePropagation && evt.stopImmediatePropagation();
      ignoreNextClick = false;
      return false;
    }
  }, true);
}

var nearestEmptyInsertDetectEvent = function nearestEmptyInsertDetectEvent(evt) {
  if (dragEl) {
    evt = evt.touches ? evt.touches[0] : evt;

    var nearest = _detectNearestEmptySortable(evt.clientX, evt.clientY);

    if (nearest) {
      // Create imitation event
      var event = {};

      for (var i in evt) {
        if (evt.hasOwnProperty(i)) {
          event[i] = evt[i];
        }
      }

      event.target = event.rootEl = nearest;
      event.preventDefault = void 0;
      event.stopPropagation = void 0;

      nearest[expando]._onDragOver(event);
    }
  }
};

var _checkOutsideTargetEl = function _checkOutsideTargetEl(evt) {
  if (dragEl) {
    dragEl.parentNode[expando]._isOutsideThisEl(evt.target);
  }
};
/**
 * @class  Sortable
 * @param  {HTMLElement}  el
 * @param  {Object}       [options]
 */


function Sortable(el, options) {
  if (!(el && el.nodeType && el.nodeType === 1)) {
    throw "Sortable: `el` must be an HTMLElement, not ".concat({}.toString.call(el));
  }

  this.el = el; // root element

  this.options = options = _extends({}, options); // Export instance

  el[expando] = this;
  var defaults = {
    group: null,
    sort: true,
    disabled: false,
    store: null,
    handle: null,
    draggable: /^[uo]l$/i.test(el.nodeName) ? '>li' : '>*',
    swapThreshold: 1,
    // percentage; 0 <= x <= 1
    invertSwap: false,
    // invert always
    invertedSwapThreshold: null,
    // will be set to same as swapThreshold if default
    removeCloneOnHide: true,
    direction: function direction() {
      return _detectDirection(el, this.options);
    },
    ghostClass: 'sortable-ghost',
    chosenClass: 'sortable-chosen',
    dragClass: 'sortable-drag',
    ignore: 'a, img',
    filter: null,
    preventOnFilter: true,
    animation: 0,
    easing: null,
    setData: function setData(dataTransfer, dragEl) {
      dataTransfer.setData('Text', dragEl.textContent);
    },
    dropBubble: false,
    dragoverBubble: false,
    dataIdAttr: 'data-id',
    delay: 0,
    delayOnTouchOnly: false,
    touchStartThreshold: (Number.parseInt ? Number : window).parseInt(window.devicePixelRatio, 10) || 1,
    forceFallback: false,
    fallbackClass: 'sortable-fallback',
    fallbackOnBody: false,
    fallbackTolerance: 0,
    fallbackOffset: {
      x: 0,
      y: 0
    },
    supportPointer: Sortable.supportPointer !== false && 'PointerEvent' in window && !Safari,
    emptyInsertThreshold: 5
  };
  PluginManager.initializePlugins(this, el, defaults); // Set default options

  for (var name in defaults) {
    !(name in options) && (options[name] = defaults[name]);
  }

  _prepareGroup(options); // Bind all private methods


  for (var fn in this) {
    if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
      this[fn] = this[fn].bind(this);
    }
  } // Setup drag mode


  this.nativeDraggable = options.forceFallback ? false : supportDraggable;

  if (this.nativeDraggable) {
    // Touch start threshold cannot be greater than the native dragstart threshold
    this.options.touchStartThreshold = 1;
  } // Bind events


  if (options.supportPointer) {
    on(el, 'pointerdown', this._onTapStart);
  } else {
    on(el, 'mousedown', this._onTapStart);
    on(el, 'touchstart', this._onTapStart);
  }

  if (this.nativeDraggable) {
    on(el, 'dragover', this);
    on(el, 'dragenter', this);
  }

  sortables.push(this.el); // Restore sorting

  options.store && options.store.get && this.sort(options.store.get(this) || []); // Add animation state manager

  _extends(this, AnimationStateManager());
}

Sortable.prototype =
/** @lends Sortable.prototype */
{
  constructor: Sortable,
  _isOutsideThisEl: function _isOutsideThisEl(target) {
    if (!this.el.contains(target) && target !== this.el) {
      lastTarget = null;
    }
  },
  _getDirection: function _getDirection(evt, target) {
    return typeof this.options.direction === 'function' ? this.options.direction.call(this, evt, target, dragEl) : this.options.direction;
  },
  _onTapStart: function _onTapStart(
  /** Event|TouchEvent */
  evt) {
    if (!evt.cancelable) return;

    var _this = this,
        el = this.el,
        options = this.options,
        preventOnFilter = options.preventOnFilter,
        type = evt.type,
        touch = evt.touches && evt.touches[0] || evt.pointerType && evt.pointerType === 'touch' && evt,
        target = (touch || evt).target,
        originalTarget = evt.target.shadowRoot && (evt.path && evt.path[0] || evt.composedPath && evt.composedPath()[0]) || target,
        filter = options.filter;

    _saveInputCheckedState(el); // Don't trigger start event when an element is been dragged, otherwise the evt.oldindex always wrong when set option.group.


    if (dragEl) {
      return;
    }

    if (/mousedown|pointerdown/.test(type) && evt.button !== 0 || options.disabled) {
      return; // only left button and enabled
    } // cancel dnd if original target is content editable


    if (originalTarget.isContentEditable) {
      return;
    } // Safari ignores further event handling after mousedown


    if (!this.nativeDraggable && Safari && target && target.tagName.toUpperCase() === 'SELECT') {
      return;
    }

    target = closest(target, options.draggable, el, false);

    if (target && target.animated) {
      return;
    }

    if (lastDownEl === target) {
      // Ignoring duplicate `down`
      return;
    } // Get the index of the dragged element within its parent


    oldIndex = index(target);
    oldDraggableIndex = index(target, options.draggable); // Check filter

    if (typeof filter === 'function') {
      if (filter.call(this, evt, target, this)) {
        _dispatchEvent({
          sortable: _this,
          rootEl: originalTarget,
          name: 'filter',
          targetEl: target,
          toEl: el,
          fromEl: el
        });

        pluginEvent('filter', _this, {
          evt: evt
        });
        preventOnFilter && evt.cancelable && evt.preventDefault();
        return; // cancel dnd
      }
    } else if (filter) {
      filter = filter.split(',').some(function (criteria) {
        criteria = closest(originalTarget, criteria.trim(), el, false);

        if (criteria) {
          _dispatchEvent({
            sortable: _this,
            rootEl: criteria,
            name: 'filter',
            targetEl: target,
            fromEl: el,
            toEl: el
          });

          pluginEvent('filter', _this, {
            evt: evt
          });
          return true;
        }
      });

      if (filter) {
        preventOnFilter && evt.cancelable && evt.preventDefault();
        return; // cancel dnd
      }
    }

    if (options.handle && !closest(originalTarget, options.handle, el, false)) {
      return;
    } // Prepare `dragstart`


    this._prepareDragStart(evt, touch, target);
  },
  _prepareDragStart: function _prepareDragStart(
  /** Event */
  evt,
  /** Touch */
  touch,
  /** HTMLElement */
  target) {
    var _this = this,
        el = _this.el,
        options = _this.options,
        ownerDocument = el.ownerDocument,
        dragStartFn;

    if (target && !dragEl && target.parentNode === el) {
      var dragRect = getRect(target);
      rootEl = el;
      dragEl = target;
      parentEl = dragEl.parentNode;
      nextEl = dragEl.nextSibling;
      lastDownEl = target;
      activeGroup = options.group;
      Sortable.dragged = dragEl;
      tapEvt = {
        target: dragEl,
        clientX: (touch || evt).clientX,
        clientY: (touch || evt).clientY
      };
      tapDistanceLeft = tapEvt.clientX - dragRect.left;
      tapDistanceTop = tapEvt.clientY - dragRect.top;
      this._lastX = (touch || evt).clientX;
      this._lastY = (touch || evt).clientY;
      dragEl.style['will-change'] = 'all';

      dragStartFn = function dragStartFn() {
        pluginEvent('delayEnded', _this, {
          evt: evt
        });

        if (Sortable.eventCanceled) {
          _this._onDrop();

          return;
        } // Delayed drag has been triggered
        // we can re-enable the events: touchmove/mousemove


        _this._disableDelayedDragEvents();

        if (!FireFox && _this.nativeDraggable) {
          dragEl.draggable = true;
        } // Bind the events: dragstart/dragend


        _this._triggerDragStart(evt, touch); // Drag start event


        _dispatchEvent({
          sortable: _this,
          name: 'choose',
          originalEvent: evt
        }); // Chosen item


        toggleClass(dragEl, options.chosenClass, true);
      }; // Disable "draggable"


      options.ignore.split(',').forEach(function (criteria) {
        find(dragEl, criteria.trim(), _disableDraggable);
      });
      on(ownerDocument, 'dragover', nearestEmptyInsertDetectEvent);
      on(ownerDocument, 'mousemove', nearestEmptyInsertDetectEvent);
      on(ownerDocument, 'touchmove', nearestEmptyInsertDetectEvent);
      on(ownerDocument, 'mouseup', _this._onDrop);
      on(ownerDocument, 'touchend', _this._onDrop);
      on(ownerDocument, 'touchcancel', _this._onDrop); // Make dragEl draggable (must be before delay for FireFox)

      if (FireFox && this.nativeDraggable) {
        this.options.touchStartThreshold = 4;
        dragEl.draggable = true;
      }

      pluginEvent('delayStart', this, {
        evt: evt
      }); // Delay is impossible for native DnD in Edge or IE

      if (options.delay && (!options.delayOnTouchOnly || touch) && (!this.nativeDraggable || !(Edge || IE11OrLess))) {
        if (Sortable.eventCanceled) {
          this._onDrop();

          return;
        } // If the user moves the pointer or let go the click or touch
        // before the delay has been reached:
        // disable the delayed drag


        on(ownerDocument, 'mouseup', _this._disableDelayedDrag);
        on(ownerDocument, 'touchend', _this._disableDelayedDrag);
        on(ownerDocument, 'touchcancel', _this._disableDelayedDrag);
        on(ownerDocument, 'mousemove', _this._delayedDragTouchMoveHandler);
        on(ownerDocument, 'touchmove', _this._delayedDragTouchMoveHandler);
        options.supportPointer && on(ownerDocument, 'pointermove', _this._delayedDragTouchMoveHandler);
        _this._dragStartTimer = setTimeout(dragStartFn, options.delay);
      } else {
        dragStartFn();
      }
    }
  },
  _delayedDragTouchMoveHandler: function _delayedDragTouchMoveHandler(
  /** TouchEvent|PointerEvent **/
  e) {
    var touch = e.touches ? e.touches[0] : e;

    if (Math.max(Math.abs(touch.clientX - this._lastX), Math.abs(touch.clientY - this._lastY)) >= Math.floor(this.options.touchStartThreshold / (this.nativeDraggable && window.devicePixelRatio || 1))) {
      this._disableDelayedDrag();
    }
  },
  _disableDelayedDrag: function _disableDelayedDrag() {
    dragEl && _disableDraggable(dragEl);
    clearTimeout(this._dragStartTimer);

    this._disableDelayedDragEvents();
  },
  _disableDelayedDragEvents: function _disableDelayedDragEvents() {
    var ownerDocument = this.el.ownerDocument;
    off(ownerDocument, 'mouseup', this._disableDelayedDrag);
    off(ownerDocument, 'touchend', this._disableDelayedDrag);
    off(ownerDocument, 'touchcancel', this._disableDelayedDrag);
    off(ownerDocument, 'mousemove', this._delayedDragTouchMoveHandler);
    off(ownerDocument, 'touchmove', this._delayedDragTouchMoveHandler);
    off(ownerDocument, 'pointermove', this._delayedDragTouchMoveHandler);
  },
  _triggerDragStart: function _triggerDragStart(
  /** Event */
  evt,
  /** Touch */
  touch) {
    touch = touch || evt.pointerType == 'touch' && evt;

    if (!this.nativeDraggable || touch) {
      if (this.options.supportPointer) {
        on(document, 'pointermove', this._onTouchMove);
      } else if (touch) {
        on(document, 'touchmove', this._onTouchMove);
      } else {
        on(document, 'mousemove', this._onTouchMove);
      }
    } else {
      on(dragEl, 'dragend', this);
      on(rootEl, 'dragstart', this._onDragStart);
    }

    try {
      if (document.selection) {
        // Timeout neccessary for IE9
        _nextTick(function () {
          document.selection.empty();
        });
      } else {
        window.getSelection().removeAllRanges();
      }
    } catch (err) {}
  },
  _dragStarted: function _dragStarted(fallback, evt) {

    awaitingDragStarted = false;

    if (rootEl && dragEl) {
      pluginEvent('dragStarted', this, {
        evt: evt
      });

      if (this.nativeDraggable) {
        on(document, 'dragover', _checkOutsideTargetEl);
      }

      var options = this.options; // Apply effect

      !fallback && toggleClass(dragEl, options.dragClass, false);
      toggleClass(dragEl, options.ghostClass, true);
      Sortable.active = this;
      fallback && this._appendGhost(); // Drag start event

      _dispatchEvent({
        sortable: this,
        name: 'start',
        originalEvent: evt
      });
    } else {
      this._nulling();
    }
  },
  _emulateDragOver: function _emulateDragOver() {
    if (touchEvt) {
      this._lastX = touchEvt.clientX;
      this._lastY = touchEvt.clientY;

      _hideGhostForTarget();

      var target = document.elementFromPoint(touchEvt.clientX, touchEvt.clientY);
      var parent = target;

      while (target && target.shadowRoot) {
        target = target.shadowRoot.elementFromPoint(touchEvt.clientX, touchEvt.clientY);
        if (target === parent) break;
        parent = target;
      }

      dragEl.parentNode[expando]._isOutsideThisEl(target);

      if (parent) {
        do {
          if (parent[expando]) {
            var inserted = void 0;
            inserted = parent[expando]._onDragOver({
              clientX: touchEvt.clientX,
              clientY: touchEvt.clientY,
              target: target,
              rootEl: parent
            });

            if (inserted && !this.options.dragoverBubble) {
              break;
            }
          }

          target = parent; // store last element
        }
        /* jshint boss:true */
        while (parent = parent.parentNode);
      }

      _unhideGhostForTarget();
    }
  },
  _onTouchMove: function _onTouchMove(
  /**TouchEvent*/
  evt) {
    if (tapEvt) {
      var options = this.options,
          fallbackTolerance = options.fallbackTolerance,
          fallbackOffset = options.fallbackOffset,
          touch = evt.touches ? evt.touches[0] : evt,
          ghostMatrix = ghostEl && matrix(ghostEl, true),
          scaleX = ghostEl && ghostMatrix && ghostMatrix.a,
          scaleY = ghostEl && ghostMatrix && ghostMatrix.d,
          relativeScrollOffset = PositionGhostAbsolutely && ghostRelativeParent && getRelativeScrollOffset(ghostRelativeParent),
          dx = (touch.clientX - tapEvt.clientX + fallbackOffset.x) / (scaleX || 1) + (relativeScrollOffset ? relativeScrollOffset[0] - ghostRelativeParentInitialScroll[0] : 0) / (scaleX || 1),
          dy = (touch.clientY - tapEvt.clientY + fallbackOffset.y) / (scaleY || 1) + (relativeScrollOffset ? relativeScrollOffset[1] - ghostRelativeParentInitialScroll[1] : 0) / (scaleY || 1); // only set the status to dragging, when we are actually dragging

      if (!Sortable.active && !awaitingDragStarted) {
        if (fallbackTolerance && Math.max(Math.abs(touch.clientX - this._lastX), Math.abs(touch.clientY - this._lastY)) < fallbackTolerance) {
          return;
        }

        this._onDragStart(evt, true);
      }

      if (ghostEl) {
        if (ghostMatrix) {
          ghostMatrix.e += dx - (lastDx || 0);
          ghostMatrix.f += dy - (lastDy || 0);
        } else {
          ghostMatrix = {
            a: 1,
            b: 0,
            c: 0,
            d: 1,
            e: dx,
            f: dy
          };
        }

        var cssMatrix = "matrix(".concat(ghostMatrix.a, ",").concat(ghostMatrix.b, ",").concat(ghostMatrix.c, ",").concat(ghostMatrix.d, ",").concat(ghostMatrix.e, ",").concat(ghostMatrix.f, ")");
        css(ghostEl, 'webkitTransform', cssMatrix);
        css(ghostEl, 'mozTransform', cssMatrix);
        css(ghostEl, 'msTransform', cssMatrix);
        css(ghostEl, 'transform', cssMatrix);
        lastDx = dx;
        lastDy = dy;
        touchEvt = touch;
      }

      evt.cancelable && evt.preventDefault();
    }
  },
  _appendGhost: function _appendGhost() {
    // Bug if using scale(): https://stackoverflow.com/questions/2637058
    // Not being adjusted for
    if (!ghostEl) {
      var container = this.options.fallbackOnBody ? document.body : rootEl,
          rect = getRect(dragEl, true, PositionGhostAbsolutely, true, container),
          options = this.options; // Position absolutely

      if (PositionGhostAbsolutely) {
        // Get relatively positioned parent
        ghostRelativeParent = container;

        while (css(ghostRelativeParent, 'position') === 'static' && css(ghostRelativeParent, 'transform') === 'none' && ghostRelativeParent !== document) {
          ghostRelativeParent = ghostRelativeParent.parentNode;
        }

        if (ghostRelativeParent !== document.body && ghostRelativeParent !== document.documentElement) {
          if (ghostRelativeParent === document) ghostRelativeParent = getWindowScrollingElement();
          rect.top += ghostRelativeParent.scrollTop;
          rect.left += ghostRelativeParent.scrollLeft;
        } else {
          ghostRelativeParent = getWindowScrollingElement();
        }

        ghostRelativeParentInitialScroll = getRelativeScrollOffset(ghostRelativeParent);
      }

      ghostEl = dragEl.cloneNode(true);
      toggleClass(ghostEl, options.ghostClass, false);
      toggleClass(ghostEl, options.fallbackClass, true);
      toggleClass(ghostEl, options.dragClass, true);
      css(ghostEl, 'transition', '');
      css(ghostEl, 'transform', '');
      css(ghostEl, 'box-sizing', 'border-box');
      css(ghostEl, 'margin', 0);
      css(ghostEl, 'top', rect.top);
      css(ghostEl, 'left', rect.left);
      css(ghostEl, 'width', rect.width);
      css(ghostEl, 'height', rect.height);
      css(ghostEl, 'opacity', '0.8');
      css(ghostEl, 'position', PositionGhostAbsolutely ? 'absolute' : 'fixed');
      css(ghostEl, 'zIndex', '100000');
      css(ghostEl, 'pointerEvents', 'none');
      Sortable.ghost = ghostEl;
      container.appendChild(ghostEl); // Set transform-origin

      css(ghostEl, 'transform-origin', tapDistanceLeft / parseInt(ghostEl.style.width) * 100 + '% ' + tapDistanceTop / parseInt(ghostEl.style.height) * 100 + '%');
    }
  },
  _onDragStart: function _onDragStart(
  /**Event*/
  evt,
  /**boolean*/
  fallback) {
    var _this = this;

    var dataTransfer = evt.dataTransfer;
    var options = _this.options;
    pluginEvent('dragStart', this, {
      evt: evt
    });

    if (Sortable.eventCanceled) {
      this._onDrop();

      return;
    }

    pluginEvent('setupClone', this);

    if (!Sortable.eventCanceled) {
      cloneEl = clone(dragEl);
      cloneEl.draggable = false;
      cloneEl.style['will-change'] = '';

      this._hideClone();

      toggleClass(cloneEl, this.options.chosenClass, false);
      Sortable.clone = cloneEl;
    } // #1143: IFrame support workaround


    _this.cloneId = _nextTick(function () {
      pluginEvent('clone', _this);
      if (Sortable.eventCanceled) return;

      if (!_this.options.removeCloneOnHide) {
        rootEl.insertBefore(cloneEl, dragEl);
      }

      _this._hideClone();

      _dispatchEvent({
        sortable: _this,
        name: 'clone'
      });
    });
    !fallback && toggleClass(dragEl, options.dragClass, true); // Set proper drop events

    if (fallback) {
      ignoreNextClick = true;
      _this._loopId = setInterval(_this._emulateDragOver, 50);
    } else {
      // Undo what was set in _prepareDragStart before drag started
      off(document, 'mouseup', _this._onDrop);
      off(document, 'touchend', _this._onDrop);
      off(document, 'touchcancel', _this._onDrop);

      if (dataTransfer) {
        dataTransfer.effectAllowed = 'move';
        options.setData && options.setData.call(_this, dataTransfer, dragEl);
      }

      on(document, 'drop', _this); // #1276 fix:

      css(dragEl, 'transform', 'translateZ(0)');
    }

    awaitingDragStarted = true;
    _this._dragStartId = _nextTick(_this._dragStarted.bind(_this, fallback, evt));
    on(document, 'selectstart', _this);
    moved = true;

    if (Safari) {
      css(document.body, 'user-select', 'none');
    }
  },
  // Returns true - if no further action is needed (either inserted or another condition)
  _onDragOver: function _onDragOver(
  /**Event*/
  evt) {
    var el = this.el,
        target = evt.target,
        dragRect,
        targetRect,
        revert,
        options = this.options,
        group = options.group,
        activeSortable = Sortable.active,
        isOwner = activeGroup === group,
        canSort = options.sort,
        fromSortable = putSortable || activeSortable,
        vertical,
        _this = this,
        completedFired = false;

    if (_silent) return;

    function dragOverEvent(name, extra) {
      pluginEvent(name, _this, _objectSpread2({
        evt: evt,
        isOwner: isOwner,
        axis: vertical ? 'vertical' : 'horizontal',
        revert: revert,
        dragRect: dragRect,
        targetRect: targetRect,
        canSort: canSort,
        fromSortable: fromSortable,
        target: target,
        completed: completed,
        onMove: function onMove(target, after) {
          return _onMove(rootEl, el, dragEl, dragRect, target, getRect(target), evt, after);
        },
        changed: changed
      }, extra));
    } // Capture animation state


    function capture() {
      dragOverEvent('dragOverAnimationCapture');

      _this.captureAnimationState();

      if (_this !== fromSortable) {
        fromSortable.captureAnimationState();
      }
    } // Return invocation when dragEl is inserted (or completed)


    function completed(insertion) {
      dragOverEvent('dragOverCompleted', {
        insertion: insertion
      });

      if (insertion) {
        // Clones must be hidden before folding animation to capture dragRectAbsolute properly
        if (isOwner) {
          activeSortable._hideClone();
        } else {
          activeSortable._showClone(_this);
        }

        if (_this !== fromSortable) {
          // Set ghost class to new sortable's ghost class
          toggleClass(dragEl, putSortable ? putSortable.options.ghostClass : activeSortable.options.ghostClass, false);
          toggleClass(dragEl, options.ghostClass, true);
        }

        if (putSortable !== _this && _this !== Sortable.active) {
          putSortable = _this;
        } else if (_this === Sortable.active && putSortable) {
          putSortable = null;
        } // Animation


        if (fromSortable === _this) {
          _this._ignoreWhileAnimating = target;
        }

        _this.animateAll(function () {
          dragOverEvent('dragOverAnimationComplete');
          _this._ignoreWhileAnimating = null;
        });

        if (_this !== fromSortable) {
          fromSortable.animateAll();
          fromSortable._ignoreWhileAnimating = null;
        }
      } // Null lastTarget if it is not inside a previously swapped element


      if (target === dragEl && !dragEl.animated || target === el && !target.animated) {
        lastTarget = null;
      } // no bubbling and not fallback


      if (!options.dragoverBubble && !evt.rootEl && target !== document) {
        dragEl.parentNode[expando]._isOutsideThisEl(evt.target); // Do not detect for empty insert if already inserted


        !insertion && nearestEmptyInsertDetectEvent(evt);
      }

      !options.dragoverBubble && evt.stopPropagation && evt.stopPropagation();
      return completedFired = true;
    } // Call when dragEl has been inserted


    function changed() {
      newIndex = index(dragEl);
      newDraggableIndex = index(dragEl, options.draggable);

      _dispatchEvent({
        sortable: _this,
        name: 'change',
        toEl: el,
        newIndex: newIndex,
        newDraggableIndex: newDraggableIndex,
        originalEvent: evt
      });
    }

    if (evt.preventDefault !== void 0) {
      evt.cancelable && evt.preventDefault();
    }

    target = closest(target, options.draggable, el, true);
    dragOverEvent('dragOver');
    if (Sortable.eventCanceled) return completedFired;

    if (dragEl.contains(evt.target) || target.animated && target.animatingX && target.animatingY || _this._ignoreWhileAnimating === target) {
      return completed(false);
    }

    ignoreNextClick = false;

    if (activeSortable && !options.disabled && (isOwner ? canSort || (revert = parentEl !== rootEl) // Reverting item into the original list
    : putSortable === this || (this.lastPutMode = activeGroup.checkPull(this, activeSortable, dragEl, evt)) && group.checkPut(this, activeSortable, dragEl, evt))) {
      vertical = this._getDirection(evt, target) === 'vertical';
      dragRect = getRect(dragEl);
      dragOverEvent('dragOverValid');
      if (Sortable.eventCanceled) return completedFired;

      if (revert) {
        parentEl = rootEl; // actualization

        capture();

        this._hideClone();

        dragOverEvent('revert');

        if (!Sortable.eventCanceled) {
          if (nextEl) {
            rootEl.insertBefore(dragEl, nextEl);
          } else {
            rootEl.appendChild(dragEl);
          }
        }

        return completed(true);
      }

      var elLastChild = lastChild(el, options.draggable);

      if (!elLastChild || _ghostIsLast(evt, vertical, this) && !elLastChild.animated) {
        // Insert to end of list
        // If already at end of list: Do not insert
        if (elLastChild === dragEl) {
          return completed(false);
        } // if there is a last element, it is the target


        if (elLastChild && el === evt.target) {
          target = elLastChild;
        }

        if (target) {
          targetRect = getRect(target);
        }

        if (_onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, !!target) !== false) {
          capture();
          el.appendChild(dragEl);
          parentEl = el; // actualization

          changed();
          return completed(true);
        }
      } else if (elLastChild && _ghostIsFirst(evt, vertical, this)) {
        // Insert to start of list
        var firstChild = getChild(el, 0, options, true);

        if (firstChild === dragEl) {
          return completed(false);
        }

        target = firstChild;
        targetRect = getRect(target);

        if (_onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, false) !== false) {
          capture();
          el.insertBefore(dragEl, firstChild);
          parentEl = el; // actualization

          changed();
          return completed(true);
        }
      } else if (target.parentNode === el) {
        targetRect = getRect(target);
        var direction = 0,
            targetBeforeFirstSwap,
            differentLevel = dragEl.parentNode !== el,
            differentRowCol = !_dragElInRowColumn(dragEl.animated && dragEl.toRect || dragRect, target.animated && target.toRect || targetRect, vertical),
            side1 = vertical ? 'top' : 'left',
            scrolledPastTop = isScrolledPast(target, 'top', 'top') || isScrolledPast(dragEl, 'top', 'top'),
            scrollBefore = scrolledPastTop ? scrolledPastTop.scrollTop : void 0;

        if (lastTarget !== target) {
          targetBeforeFirstSwap = targetRect[side1];
          pastFirstInvertThresh = false;
          isCircumstantialInvert = !differentRowCol && options.invertSwap || differentLevel;
        }

        direction = _getSwapDirection(evt, target, targetRect, vertical, differentRowCol ? 1 : options.swapThreshold, options.invertedSwapThreshold == null ? options.swapThreshold : options.invertedSwapThreshold, isCircumstantialInvert, lastTarget === target);
        var sibling;

        if (direction !== 0) {
          // Check if target is beside dragEl in respective direction (ignoring hidden elements)
          var dragIndex = index(dragEl);

          do {
            dragIndex -= direction;
            sibling = parentEl.children[dragIndex];
          } while (sibling && (css(sibling, 'display') === 'none' || sibling === ghostEl));
        } // If dragEl is already beside target: Do not insert


        if (direction === 0 || sibling === target) {
          return completed(false);
        }

        lastTarget = target;
        lastDirection = direction;
        var nextSibling = target.nextElementSibling,
            after = false;
        after = direction === 1;

        var moveVector = _onMove(rootEl, el, dragEl, dragRect, target, targetRect, evt, after);

        if (moveVector !== false) {
          if (moveVector === 1 || moveVector === -1) {
            after = moveVector === 1;
          }

          _silent = true;
          setTimeout(_unsilent, 30);
          capture();

          if (after && !nextSibling) {
            el.appendChild(dragEl);
          } else {
            target.parentNode.insertBefore(dragEl, after ? nextSibling : target);
          } // Undo chrome's scroll adjustment (has no effect on other browsers)


          if (scrolledPastTop) {
            scrollBy(scrolledPastTop, 0, scrollBefore - scrolledPastTop.scrollTop);
          }

          parentEl = dragEl.parentNode; // actualization
          // must be done before animation

          if (targetBeforeFirstSwap !== undefined && !isCircumstantialInvert) {
            targetMoveDistance = Math.abs(targetBeforeFirstSwap - getRect(target)[side1]);
          }

          changed();
          return completed(true);
        }
      }

      if (el.contains(dragEl)) {
        return completed(false);
      }
    }

    return false;
  },
  _ignoreWhileAnimating: null,
  _offMoveEvents: function _offMoveEvents() {
    off(document, 'mousemove', this._onTouchMove);
    off(document, 'touchmove', this._onTouchMove);
    off(document, 'pointermove', this._onTouchMove);
    off(document, 'dragover', nearestEmptyInsertDetectEvent);
    off(document, 'mousemove', nearestEmptyInsertDetectEvent);
    off(document, 'touchmove', nearestEmptyInsertDetectEvent);
  },
  _offUpEvents: function _offUpEvents() {
    var ownerDocument = this.el.ownerDocument;
    off(ownerDocument, 'mouseup', this._onDrop);
    off(ownerDocument, 'touchend', this._onDrop);
    off(ownerDocument, 'pointerup', this._onDrop);
    off(ownerDocument, 'touchcancel', this._onDrop);
    off(document, 'selectstart', this);
  },
  _onDrop: function _onDrop(
  /**Event*/
  evt) {
    var el = this.el,
        options = this.options; // Get the index of the dragged element within its parent

    newIndex = index(dragEl);
    newDraggableIndex = index(dragEl, options.draggable);
    pluginEvent('drop', this, {
      evt: evt
    });
    parentEl = dragEl && dragEl.parentNode; // Get again after plugin event

    newIndex = index(dragEl);
    newDraggableIndex = index(dragEl, options.draggable);

    if (Sortable.eventCanceled) {
      this._nulling();

      return;
    }

    awaitingDragStarted = false;
    isCircumstantialInvert = false;
    pastFirstInvertThresh = false;
    clearInterval(this._loopId);
    clearTimeout(this._dragStartTimer);

    _cancelNextTick(this.cloneId);

    _cancelNextTick(this._dragStartId); // Unbind events


    if (this.nativeDraggable) {
      off(document, 'drop', this);
      off(el, 'dragstart', this._onDragStart);
    }

    this._offMoveEvents();

    this._offUpEvents();

    if (Safari) {
      css(document.body, 'user-select', '');
    }

    css(dragEl, 'transform', '');

    if (evt) {
      if (moved) {
        evt.cancelable && evt.preventDefault();
        !options.dropBubble && evt.stopPropagation();
      }

      ghostEl && ghostEl.parentNode && ghostEl.parentNode.removeChild(ghostEl);

      if (rootEl === parentEl || putSortable && putSortable.lastPutMode !== 'clone') {
        // Remove clone(s)
        cloneEl && cloneEl.parentNode && cloneEl.parentNode.removeChild(cloneEl);
      }

      if (dragEl) {
        if (this.nativeDraggable) {
          off(dragEl, 'dragend', this);
        }

        _disableDraggable(dragEl);

        dragEl.style['will-change'] = ''; // Remove classes
        // ghostClass is added in dragStarted

        if (moved && !awaitingDragStarted) {
          toggleClass(dragEl, putSortable ? putSortable.options.ghostClass : this.options.ghostClass, false);
        }

        toggleClass(dragEl, this.options.chosenClass, false); // Drag stop event

        _dispatchEvent({
          sortable: this,
          name: 'unchoose',
          toEl: parentEl,
          newIndex: null,
          newDraggableIndex: null,
          originalEvent: evt
        });

        if (rootEl !== parentEl) {
          if (newIndex >= 0) {
            // Add event
            _dispatchEvent({
              rootEl: parentEl,
              name: 'add',
              toEl: parentEl,
              fromEl: rootEl,
              originalEvent: evt
            }); // Remove event


            _dispatchEvent({
              sortable: this,
              name: 'remove',
              toEl: parentEl,
              originalEvent: evt
            }); // drag from one list and drop into another


            _dispatchEvent({
              rootEl: parentEl,
              name: 'sort',
              toEl: parentEl,
              fromEl: rootEl,
              originalEvent: evt
            });

            _dispatchEvent({
              sortable: this,
              name: 'sort',
              toEl: parentEl,
              originalEvent: evt
            });
          }

          putSortable && putSortable.save();
        } else {
          if (newIndex !== oldIndex) {
            if (newIndex >= 0) {
              // drag & drop within the same list
              _dispatchEvent({
                sortable: this,
                name: 'update',
                toEl: parentEl,
                originalEvent: evt
              });

              _dispatchEvent({
                sortable: this,
                name: 'sort',
                toEl: parentEl,
                originalEvent: evt
              });
            }
          }
        }

        if (Sortable.active) {
          /* jshint eqnull:true */
          if (newIndex == null || newIndex === -1) {
            newIndex = oldIndex;
            newDraggableIndex = oldDraggableIndex;
          }

          _dispatchEvent({
            sortable: this,
            name: 'end',
            toEl: parentEl,
            originalEvent: evt
          }); // Save sorting


          this.save();
        }
      }
    }

    this._nulling();
  },
  _nulling: function _nulling() {
    pluginEvent('nulling', this);
    rootEl = dragEl = parentEl = ghostEl = nextEl = cloneEl = lastDownEl = cloneHidden = tapEvt = touchEvt = moved = newIndex = newDraggableIndex = oldIndex = oldDraggableIndex = lastTarget = lastDirection = putSortable = activeGroup = Sortable.dragged = Sortable.ghost = Sortable.clone = Sortable.active = null;
    savedInputChecked.forEach(function (el) {
      el.checked = true;
    });
    savedInputChecked.length = lastDx = lastDy = 0;
  },
  handleEvent: function handleEvent(
  /**Event*/
  evt) {
    switch (evt.type) {
      case 'drop':
      case 'dragend':
        this._onDrop(evt);

        break;

      case 'dragenter':
      case 'dragover':
        if (dragEl) {
          this._onDragOver(evt);

          _globalDragOver(evt);
        }

        break;

      case 'selectstart':
        evt.preventDefault();
        break;
    }
  },

  /**
   * Serializes the item into an array of string.
   * @returns {String[]}
   */
  toArray: function toArray() {
    var order = [],
        el,
        children = this.el.children,
        i = 0,
        n = children.length,
        options = this.options;

    for (; i < n; i++) {
      el = children[i];

      if (closest(el, options.draggable, this.el, false)) {
        order.push(el.getAttribute(options.dataIdAttr) || _generateId(el));
      }
    }

    return order;
  },

  /**
   * Sorts the elements according to the array.
   * @param  {String[]}  order  order of the items
   */
  sort: function sort(order, useAnimation) {
    var items = {},
        rootEl = this.el;
    this.toArray().forEach(function (id, i) {
      var el = rootEl.children[i];

      if (closest(el, this.options.draggable, rootEl, false)) {
        items[id] = el;
      }
    }, this);
    useAnimation && this.captureAnimationState();
    order.forEach(function (id) {
      if (items[id]) {
        rootEl.removeChild(items[id]);
        rootEl.appendChild(items[id]);
      }
    });
    useAnimation && this.animateAll();
  },

  /**
   * Save the current sorting
   */
  save: function save() {
    var store = this.options.store;
    store && store.set && store.set(this);
  },

  /**
   * For each element in the set, get the first element that matches the selector by testing the element itself and traversing up through its ancestors in the DOM tree.
   * @param   {HTMLElement}  el
   * @param   {String}       [selector]  default: `options.draggable`
   * @returns {HTMLElement|null}
   */
  closest: function closest$1(el, selector) {
    return closest(el, selector || this.options.draggable, this.el, false);
  },

  /**
   * Set/get option
   * @param   {string} name
   * @param   {*}      [value]
   * @returns {*}
   */
  option: function option(name, value) {
    var options = this.options;

    if (value === void 0) {
      return options[name];
    } else {
      var modifiedValue = PluginManager.modifyOption(this, name, value);

      if (typeof modifiedValue !== 'undefined') {
        options[name] = modifiedValue;
      } else {
        options[name] = value;
      }

      if (name === 'group') {
        _prepareGroup(options);
      }
    }
  },

  /**
   * Destroy
   */
  destroy: function destroy() {
    pluginEvent('destroy', this);
    var el = this.el;
    el[expando] = null;
    off(el, 'mousedown', this._onTapStart);
    off(el, 'touchstart', this._onTapStart);
    off(el, 'pointerdown', this._onTapStart);

    if (this.nativeDraggable) {
      off(el, 'dragover', this);
      off(el, 'dragenter', this);
    } // Remove draggable attributes


    Array.prototype.forEach.call(el.querySelectorAll('[draggable]'), function (el) {
      el.removeAttribute('draggable');
    });

    this._onDrop();

    this._disableDelayedDragEvents();

    sortables.splice(sortables.indexOf(this.el), 1);
    this.el = el = null;
  },
  _hideClone: function _hideClone() {
    if (!cloneHidden) {
      pluginEvent('hideClone', this);
      if (Sortable.eventCanceled) return;
      css(cloneEl, 'display', 'none');

      if (this.options.removeCloneOnHide && cloneEl.parentNode) {
        cloneEl.parentNode.removeChild(cloneEl);
      }

      cloneHidden = true;
    }
  },
  _showClone: function _showClone(putSortable) {
    if (putSortable.lastPutMode !== 'clone') {
      this._hideClone();

      return;
    }

    if (cloneHidden) {
      pluginEvent('showClone', this);
      if (Sortable.eventCanceled) return; // show clone at dragEl or original position

      if (dragEl.parentNode == rootEl && !this.options.group.revertClone) {
        rootEl.insertBefore(cloneEl, dragEl);
      } else if (nextEl) {
        rootEl.insertBefore(cloneEl, nextEl);
      } else {
        rootEl.appendChild(cloneEl);
      }

      if (this.options.group.revertClone) {
        this.animate(dragEl, cloneEl);
      }

      css(cloneEl, 'display', '');
      cloneHidden = false;
    }
  }
};

function _globalDragOver(
/**Event*/
evt) {
  if (evt.dataTransfer) {
    evt.dataTransfer.dropEffect = 'move';
  }

  evt.cancelable && evt.preventDefault();
}

function _onMove(fromEl, toEl, dragEl, dragRect, targetEl, targetRect, originalEvent, willInsertAfter) {
  var evt,
      sortable = fromEl[expando],
      onMoveFn = sortable.options.onMove,
      retVal; // Support for new CustomEvent feature

  if (window.CustomEvent && !IE11OrLess && !Edge) {
    evt = new CustomEvent('move', {
      bubbles: true,
      cancelable: true
    });
  } else {
    evt = document.createEvent('Event');
    evt.initEvent('move', true, true);
  }

  evt.to = toEl;
  evt.from = fromEl;
  evt.dragged = dragEl;
  evt.draggedRect = dragRect;
  evt.related = targetEl || toEl;
  evt.relatedRect = targetRect || getRect(toEl);
  evt.willInsertAfter = willInsertAfter;
  evt.originalEvent = originalEvent;
  fromEl.dispatchEvent(evt);

  if (onMoveFn) {
    retVal = onMoveFn.call(sortable, evt, originalEvent);
  }

  return retVal;
}

function _disableDraggable(el) {
  el.draggable = false;
}

function _unsilent() {
  _silent = false;
}

function _ghostIsFirst(evt, vertical, sortable) {
  var rect = getRect(getChild(sortable.el, 0, sortable.options, true));
  var spacer = 10;
  return vertical ? evt.clientX < rect.left - spacer || evt.clientY < rect.top && evt.clientX < rect.right : evt.clientY < rect.top - spacer || evt.clientY < rect.bottom && evt.clientX < rect.left;
}

function _ghostIsLast(evt, vertical, sortable) {
  var rect = getRect(lastChild(sortable.el, sortable.options.draggable));
  var spacer = 10;
  return vertical ? evt.clientX > rect.right + spacer || evt.clientX <= rect.right && evt.clientY > rect.bottom && evt.clientX >= rect.left : evt.clientX > rect.right && evt.clientY > rect.top || evt.clientX <= rect.right && evt.clientY > rect.bottom + spacer;
}

function _getSwapDirection(evt, target, targetRect, vertical, swapThreshold, invertedSwapThreshold, invertSwap, isLastTarget) {
  var mouseOnAxis = vertical ? evt.clientY : evt.clientX,
      targetLength = vertical ? targetRect.height : targetRect.width,
      targetS1 = vertical ? targetRect.top : targetRect.left,
      targetS2 = vertical ? targetRect.bottom : targetRect.right,
      invert = false;

  if (!invertSwap) {
    // Never invert or create dragEl shadow when target movemenet causes mouse to move past the end of regular swapThreshold
    if (isLastTarget && targetMoveDistance < targetLength * swapThreshold) {
      // multiplied only by swapThreshold because mouse will already be inside target by (1 - threshold) * targetLength / 2
      // check if past first invert threshold on side opposite of lastDirection
      if (!pastFirstInvertThresh && (lastDirection === 1 ? mouseOnAxis > targetS1 + targetLength * invertedSwapThreshold / 2 : mouseOnAxis < targetS2 - targetLength * invertedSwapThreshold / 2)) {
        // past first invert threshold, do not restrict inverted threshold to dragEl shadow
        pastFirstInvertThresh = true;
      }

      if (!pastFirstInvertThresh) {
        // dragEl shadow (target move distance shadow)
        if (lastDirection === 1 ? mouseOnAxis < targetS1 + targetMoveDistance // over dragEl shadow
        : mouseOnAxis > targetS2 - targetMoveDistance) {
          return -lastDirection;
        }
      } else {
        invert = true;
      }
    } else {
      // Regular
      if (mouseOnAxis > targetS1 + targetLength * (1 - swapThreshold) / 2 && mouseOnAxis < targetS2 - targetLength * (1 - swapThreshold) / 2) {
        return _getInsertDirection(target);
      }
    }
  }

  invert = invert || invertSwap;

  if (invert) {
    // Invert of regular
    if (mouseOnAxis < targetS1 + targetLength * invertedSwapThreshold / 2 || mouseOnAxis > targetS2 - targetLength * invertedSwapThreshold / 2) {
      return mouseOnAxis > targetS1 + targetLength / 2 ? 1 : -1;
    }
  }

  return 0;
}
/**
 * Gets the direction dragEl must be swapped relative to target in order to make it
 * seem that dragEl has been "inserted" into that element's position
 * @param  {HTMLElement} target       The target whose position dragEl is being inserted at
 * @return {Number}                   Direction dragEl must be swapped
 */


function _getInsertDirection(target) {
  if (index(dragEl) < index(target)) {
    return 1;
  } else {
    return -1;
  }
}
/**
 * Generate id
 * @param   {HTMLElement} el
 * @returns {String}
 * @private
 */


function _generateId(el) {
  var str = el.tagName + el.className + el.src + el.href + el.textContent,
      i = str.length,
      sum = 0;

  while (i--) {
    sum += str.charCodeAt(i);
  }

  return sum.toString(36);
}

function _saveInputCheckedState(root) {
  savedInputChecked.length = 0;
  var inputs = root.getElementsByTagName('input');
  var idx = inputs.length;

  while (idx--) {
    var el = inputs[idx];
    el.checked && savedInputChecked.push(el);
  }
}

function _nextTick(fn) {
  return setTimeout(fn, 0);
}

function _cancelNextTick(id) {
  return clearTimeout(id);
} // Fixed #973:


if (documentExists) {
  on(document, 'touchmove', function (evt) {
    if ((Sortable.active || awaitingDragStarted) && evt.cancelable) {
      evt.preventDefault();
    }
  });
} // Export utils


Sortable.utils = {
  on: on,
  off: off,
  css: css,
  find: find,
  is: function is(el, selector) {
    return !!closest(el, selector, el, false);
  },
  extend: extend,
  throttle: throttle,
  closest: closest,
  toggleClass: toggleClass,
  clone: clone,
  index: index,
  nextTick: _nextTick,
  cancelNextTick: _cancelNextTick,
  detectDirection: _detectDirection,
  getChild: getChild
};
/**
 * Get the Sortable instance of an element
 * @param  {HTMLElement} element The element
 * @return {Sortable|undefined}         The instance of Sortable
 */

Sortable.get = function (element) {
  return element[expando];
};
/**
 * Mount a plugin to Sortable
 * @param  {...SortablePlugin|SortablePlugin[]} plugins       Plugins being mounted
 */


Sortable.mount = function () {
  for (var _len = arguments.length, plugins = new Array(_len), _key = 0; _key < _len; _key++) {
    plugins[_key] = arguments[_key];
  }

  if (plugins[0].constructor === Array) plugins = plugins[0];
  plugins.forEach(function (plugin) {
    if (!plugin.prototype || !plugin.prototype.constructor) {
      throw "Sortable: Mounted plugin must be a constructor function, not ".concat({}.toString.call(plugin));
    }

    if (plugin.utils) Sortable.utils = _objectSpread2(_objectSpread2({}, Sortable.utils), plugin.utils);
    PluginManager.mount(plugin);
  });
};
/**
 * Create sortable instance
 * @param {HTMLElement}  el
 * @param {Object}      [options]
 */


Sortable.create = function (el, options) {
  return new Sortable(el, options);
}; // Export


Sortable.version = version;

var autoScrolls = [],
    scrollEl,
    scrollRootEl,
    scrolling = false,
    lastAutoScrollX,
    lastAutoScrollY,
    touchEvt$1,
    pointerElemChangedInterval;

function AutoScrollPlugin() {
  function AutoScroll() {
    this.defaults = {
      scroll: true,
      forceAutoScrollFallback: false,
      scrollSensitivity: 30,
      scrollSpeed: 10,
      bubbleScroll: true
    }; // Bind all private methods

    for (var fn in this) {
      if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
        this[fn] = this[fn].bind(this);
      }
    }
  }

  AutoScroll.prototype = {
    dragStarted: function dragStarted(_ref) {
      var originalEvent = _ref.originalEvent;

      if (this.sortable.nativeDraggable) {
        on(document, 'dragover', this._handleAutoScroll);
      } else {
        if (this.options.supportPointer) {
          on(document, 'pointermove', this._handleFallbackAutoScroll);
        } else if (originalEvent.touches) {
          on(document, 'touchmove', this._handleFallbackAutoScroll);
        } else {
          on(document, 'mousemove', this._handleFallbackAutoScroll);
        }
      }
    },
    dragOverCompleted: function dragOverCompleted(_ref2) {
      var originalEvent = _ref2.originalEvent;

      // For when bubbling is canceled and using fallback (fallback 'touchmove' always reached)
      if (!this.options.dragOverBubble && !originalEvent.rootEl) {
        this._handleAutoScroll(originalEvent);
      }
    },
    drop: function drop() {
      if (this.sortable.nativeDraggable) {
        off(document, 'dragover', this._handleAutoScroll);
      } else {
        off(document, 'pointermove', this._handleFallbackAutoScroll);
        off(document, 'touchmove', this._handleFallbackAutoScroll);
        off(document, 'mousemove', this._handleFallbackAutoScroll);
      }

      clearPointerElemChangedInterval();
      clearAutoScrolls();
      cancelThrottle();
    },
    nulling: function nulling() {
      touchEvt$1 = scrollRootEl = scrollEl = scrolling = pointerElemChangedInterval = lastAutoScrollX = lastAutoScrollY = null;
      autoScrolls.length = 0;
    },
    _handleFallbackAutoScroll: function _handleFallbackAutoScroll(evt) {
      this._handleAutoScroll(evt, true);
    },
    _handleAutoScroll: function _handleAutoScroll(evt, fallback) {
      var _this = this;

      var x = (evt.touches ? evt.touches[0] : evt).clientX,
          y = (evt.touches ? evt.touches[0] : evt).clientY,
          elem = document.elementFromPoint(x, y);
      touchEvt$1 = evt; // IE does not seem to have native autoscroll,
      // Edge's autoscroll seems too conditional,
      // MACOS Safari does not have autoscroll,
      // Firefox and Chrome are good

      if (fallback || this.options.forceAutoScrollFallback || Edge || IE11OrLess || Safari) {
        autoScroll(evt, this.options, elem, fallback); // Listener for pointer element change

        var ogElemScroller = getParentAutoScrollElement(elem, true);

        if (scrolling && (!pointerElemChangedInterval || x !== lastAutoScrollX || y !== lastAutoScrollY)) {
          pointerElemChangedInterval && clearPointerElemChangedInterval(); // Detect for pointer elem change, emulating native DnD behaviour

          pointerElemChangedInterval = setInterval(function () {
            var newElem = getParentAutoScrollElement(document.elementFromPoint(x, y), true);

            if (newElem !== ogElemScroller) {
              ogElemScroller = newElem;
              clearAutoScrolls();
            }

            autoScroll(evt, _this.options, newElem, fallback);
          }, 10);
          lastAutoScrollX = x;
          lastAutoScrollY = y;
        }
      } else {
        // if DnD is enabled (and browser has good autoscrolling), first autoscroll will already scroll, so get parent autoscroll of first autoscroll
        if (!this.options.bubbleScroll || getParentAutoScrollElement(elem, true) === getWindowScrollingElement()) {
          clearAutoScrolls();
          return;
        }

        autoScroll(evt, this.options, getParentAutoScrollElement(elem, false), false);
      }
    }
  };
  return _extends(AutoScroll, {
    pluginName: 'scroll',
    initializeByDefault: true
  });
}

function clearAutoScrolls() {
  autoScrolls.forEach(function (autoScroll) {
    clearInterval(autoScroll.pid);
  });
  autoScrolls = [];
}

function clearPointerElemChangedInterval() {
  clearInterval(pointerElemChangedInterval);
}

var autoScroll = throttle(function (evt, options, rootEl, isFallback) {
  // Bug: https://bugzilla.mozilla.org/show_bug.cgi?id=505521
  if (!options.scroll) return;
  var x = (evt.touches ? evt.touches[0] : evt).clientX,
      y = (evt.touches ? evt.touches[0] : evt).clientY,
      sens = options.scrollSensitivity,
      speed = options.scrollSpeed,
      winScroller = getWindowScrollingElement();
  var scrollThisInstance = false,
      scrollCustomFn; // New scroll root, set scrollEl

  if (scrollRootEl !== rootEl) {
    scrollRootEl = rootEl;
    clearAutoScrolls();
    scrollEl = options.scroll;
    scrollCustomFn = options.scrollFn;

    if (scrollEl === true) {
      scrollEl = getParentAutoScrollElement(rootEl, true);
    }
  }

  var layersOut = 0;
  var currentParent = scrollEl;

  do {
    var el = currentParent,
        rect = getRect(el),
        top = rect.top,
        bottom = rect.bottom,
        left = rect.left,
        right = rect.right,
        width = rect.width,
        height = rect.height,
        canScrollX = void 0,
        canScrollY = void 0,
        scrollWidth = el.scrollWidth,
        scrollHeight = el.scrollHeight,
        elCSS = css(el),
        scrollPosX = el.scrollLeft,
        scrollPosY = el.scrollTop;

    if (el === winScroller) {
      canScrollX = width < scrollWidth && (elCSS.overflowX === 'auto' || elCSS.overflowX === 'scroll' || elCSS.overflowX === 'visible');
      canScrollY = height < scrollHeight && (elCSS.overflowY === 'auto' || elCSS.overflowY === 'scroll' || elCSS.overflowY === 'visible');
    } else {
      canScrollX = width < scrollWidth && (elCSS.overflowX === 'auto' || elCSS.overflowX === 'scroll');
      canScrollY = height < scrollHeight && (elCSS.overflowY === 'auto' || elCSS.overflowY === 'scroll');
    }

    var vx = canScrollX && (Math.abs(right - x) <= sens && scrollPosX + width < scrollWidth) - (Math.abs(left - x) <= sens && !!scrollPosX);
    var vy = canScrollY && (Math.abs(bottom - y) <= sens && scrollPosY + height < scrollHeight) - (Math.abs(top - y) <= sens && !!scrollPosY);

    if (!autoScrolls[layersOut]) {
      for (var i = 0; i <= layersOut; i++) {
        if (!autoScrolls[i]) {
          autoScrolls[i] = {};
        }
      }
    }

    if (autoScrolls[layersOut].vx != vx || autoScrolls[layersOut].vy != vy || autoScrolls[layersOut].el !== el) {
      autoScrolls[layersOut].el = el;
      autoScrolls[layersOut].vx = vx;
      autoScrolls[layersOut].vy = vy;
      clearInterval(autoScrolls[layersOut].pid);

      if (vx != 0 || vy != 0) {
        scrollThisInstance = true;
        /* jshint loopfunc:true */

        autoScrolls[layersOut].pid = setInterval(function () {
          // emulate drag over during autoscroll (fallback), emulating native DnD behaviour
          if (isFallback && this.layer === 0) {
            Sortable.active._onTouchMove(touchEvt$1); // To move ghost if it is positioned absolutely

          }

          var scrollOffsetY = autoScrolls[this.layer].vy ? autoScrolls[this.layer].vy * speed : 0;
          var scrollOffsetX = autoScrolls[this.layer].vx ? autoScrolls[this.layer].vx * speed : 0;

          if (typeof scrollCustomFn === 'function') {
            if (scrollCustomFn.call(Sortable.dragged.parentNode[expando], scrollOffsetX, scrollOffsetY, evt, touchEvt$1, autoScrolls[this.layer].el) !== 'continue') {
              return;
            }
          }

          scrollBy(autoScrolls[this.layer].el, scrollOffsetX, scrollOffsetY);
        }.bind({
          layer: layersOut
        }), 24);
      }
    }

    layersOut++;
  } while (options.bubbleScroll && currentParent !== winScroller && (currentParent = getParentAutoScrollElement(currentParent, false)));

  scrolling = scrollThisInstance; // in case another function catches scrolling as false in between when it is not
}, 30);

var drop = function drop(_ref) {
  var originalEvent = _ref.originalEvent,
      putSortable = _ref.putSortable,
      dragEl = _ref.dragEl,
      activeSortable = _ref.activeSortable,
      dispatchSortableEvent = _ref.dispatchSortableEvent,
      hideGhostForTarget = _ref.hideGhostForTarget,
      unhideGhostForTarget = _ref.unhideGhostForTarget;
  if (!originalEvent) return;
  var toSortable = putSortable || activeSortable;
  hideGhostForTarget();
  var touch = originalEvent.changedTouches && originalEvent.changedTouches.length ? originalEvent.changedTouches[0] : originalEvent;
  var target = document.elementFromPoint(touch.clientX, touch.clientY);
  unhideGhostForTarget();

  if (toSortable && !toSortable.el.contains(target)) {
    dispatchSortableEvent('spill');
    this.onSpill({
      dragEl: dragEl,
      putSortable: putSortable
    });
  }
};

function Revert() {}

Revert.prototype = {
  startIndex: null,
  dragStart: function dragStart(_ref2) {
    var oldDraggableIndex = _ref2.oldDraggableIndex;
    this.startIndex = oldDraggableIndex;
  },
  onSpill: function onSpill(_ref3) {
    var dragEl = _ref3.dragEl,
        putSortable = _ref3.putSortable;
    this.sortable.captureAnimationState();

    if (putSortable) {
      putSortable.captureAnimationState();
    }

    var nextSibling = getChild(this.sortable.el, this.startIndex, this.options);

    if (nextSibling) {
      this.sortable.el.insertBefore(dragEl, nextSibling);
    } else {
      this.sortable.el.appendChild(dragEl);
    }

    this.sortable.animateAll();

    if (putSortable) {
      putSortable.animateAll();
    }
  },
  drop: drop
};

_extends(Revert, {
  pluginName: 'revertOnSpill'
});

function Remove() {}

Remove.prototype = {
  onSpill: function onSpill(_ref4) {
    var dragEl = _ref4.dragEl,
        putSortable = _ref4.putSortable;
    var parentSortable = putSortable || this.sortable;
    parentSortable.captureAnimationState();
    dragEl.parentNode && dragEl.parentNode.removeChild(dragEl);
    parentSortable.animateAll();
  },
  drop: drop
};

_extends(Remove, {
  pluginName: 'removeOnSpill'
});

var lastSwapEl;

function SwapPlugin() {
  function Swap() {
    this.defaults = {
      swapClass: 'sortable-swap-highlight'
    };
  }

  Swap.prototype = {
    dragStart: function dragStart(_ref) {
      var dragEl = _ref.dragEl;
      lastSwapEl = dragEl;
    },
    dragOverValid: function dragOverValid(_ref2) {
      var completed = _ref2.completed,
          target = _ref2.target,
          onMove = _ref2.onMove,
          activeSortable = _ref2.activeSortable,
          changed = _ref2.changed,
          cancel = _ref2.cancel;
      if (!activeSortable.options.swap) return;
      var el = this.sortable.el,
          options = this.options;

      if (target && target !== el) {
        var prevSwapEl = lastSwapEl;

        if (onMove(target) !== false) {
          toggleClass(target, options.swapClass, true);
          lastSwapEl = target;
        } else {
          lastSwapEl = null;
        }

        if (prevSwapEl && prevSwapEl !== lastSwapEl) {
          toggleClass(prevSwapEl, options.swapClass, false);
        }
      }

      changed();
      completed(true);
      cancel();
    },
    drop: function drop(_ref3) {
      var activeSortable = _ref3.activeSortable,
          putSortable = _ref3.putSortable,
          dragEl = _ref3.dragEl;
      var toSortable = putSortable || this.sortable;
      var options = this.options;
      lastSwapEl && toggleClass(lastSwapEl, options.swapClass, false);

      if (lastSwapEl && (options.swap || putSortable && putSortable.options.swap)) {
        if (dragEl !== lastSwapEl) {
          toSortable.captureAnimationState();
          if (toSortable !== activeSortable) activeSortable.captureAnimationState();
          swapNodes(dragEl, lastSwapEl);
          toSortable.animateAll();
          if (toSortable !== activeSortable) activeSortable.animateAll();
        }
      }
    },
    nulling: function nulling() {
      lastSwapEl = null;
    }
  };
  return _extends(Swap, {
    pluginName: 'swap',
    eventProperties: function eventProperties() {
      return {
        swapItem: lastSwapEl
      };
    }
  });
}

function swapNodes(n1, n2) {
  var p1 = n1.parentNode,
      p2 = n2.parentNode,
      i1,
      i2;
  if (!p1 || !p2 || p1.isEqualNode(n2) || p2.isEqualNode(n1)) return;
  i1 = index(n1);
  i2 = index(n2);

  if (p1.isEqualNode(p2) && i1 < i2) {
    i2++;
  }

  p1.insertBefore(n2, p1.children[i1]);
  p2.insertBefore(n1, p2.children[i2]);
}

var multiDragElements = [],
    multiDragClones = [],
    lastMultiDragSelect,
    // for selection with modifier key down (SHIFT)
multiDragSortable,
    initialFolding = false,
    // Initial multi-drag fold when drag started
folding = false,
    // Folding any other time
dragStarted = false,
    dragEl$1,
    clonesFromRect,
    clonesHidden;

function MultiDragPlugin() {
  function MultiDrag(sortable) {
    // Bind all private methods
    for (var fn in this) {
      if (fn.charAt(0) === '_' && typeof this[fn] === 'function') {
        this[fn] = this[fn].bind(this);
      }
    }

    if (sortable.options.supportPointer) {
      on(document, 'pointerup', this._deselectMultiDrag);
    } else {
      on(document, 'mouseup', this._deselectMultiDrag);
      on(document, 'touchend', this._deselectMultiDrag);
    }

    on(document, 'keydown', this._checkKeyDown);
    on(document, 'keyup', this._checkKeyUp);
    this.defaults = {
      selectedClass: 'sortable-selected',
      multiDragKey: null,
      setData: function setData(dataTransfer, dragEl) {
        var data = '';

        if (multiDragElements.length && multiDragSortable === sortable) {
          multiDragElements.forEach(function (multiDragElement, i) {
            data += (!i ? '' : ', ') + multiDragElement.textContent;
          });
        } else {
          data = dragEl.textContent;
        }

        dataTransfer.setData('Text', data);
      }
    };
  }

  MultiDrag.prototype = {
    multiDragKeyDown: false,
    isMultiDrag: false,
    delayStartGlobal: function delayStartGlobal(_ref) {
      var dragged = _ref.dragEl;
      dragEl$1 = dragged;
    },
    delayEnded: function delayEnded() {
      this.isMultiDrag = ~multiDragElements.indexOf(dragEl$1);
    },
    setupClone: function setupClone(_ref2) {
      var sortable = _ref2.sortable,
          cancel = _ref2.cancel;
      if (!this.isMultiDrag) return;

      for (var i = 0; i < multiDragElements.length; i++) {
        multiDragClones.push(clone(multiDragElements[i]));
        multiDragClones[i].sortableIndex = multiDragElements[i].sortableIndex;
        multiDragClones[i].draggable = false;
        multiDragClones[i].style['will-change'] = '';
        toggleClass(multiDragClones[i], this.options.selectedClass, false);
        multiDragElements[i] === dragEl$1 && toggleClass(multiDragClones[i], this.options.chosenClass, false);
      }

      sortable._hideClone();

      cancel();
    },
    clone: function clone(_ref3) {
      var sortable = _ref3.sortable,
          rootEl = _ref3.rootEl,
          dispatchSortableEvent = _ref3.dispatchSortableEvent,
          cancel = _ref3.cancel;
      if (!this.isMultiDrag) return;

      if (!this.options.removeCloneOnHide) {
        if (multiDragElements.length && multiDragSortable === sortable) {
          insertMultiDragClones(true, rootEl);
          dispatchSortableEvent('clone');
          cancel();
        }
      }
    },
    showClone: function showClone(_ref4) {
      var cloneNowShown = _ref4.cloneNowShown,
          rootEl = _ref4.rootEl,
          cancel = _ref4.cancel;
      if (!this.isMultiDrag) return;
      insertMultiDragClones(false, rootEl);
      multiDragClones.forEach(function (clone) {
        css(clone, 'display', '');
      });
      cloneNowShown();
      clonesHidden = false;
      cancel();
    },
    hideClone: function hideClone(_ref5) {
      var _this = this;

      var sortable = _ref5.sortable,
          cloneNowHidden = _ref5.cloneNowHidden,
          cancel = _ref5.cancel;
      if (!this.isMultiDrag) return;
      multiDragClones.forEach(function (clone) {
        css(clone, 'display', 'none');

        if (_this.options.removeCloneOnHide && clone.parentNode) {
          clone.parentNode.removeChild(clone);
        }
      });
      cloneNowHidden();
      clonesHidden = true;
      cancel();
    },
    dragStartGlobal: function dragStartGlobal(_ref6) {
      var sortable = _ref6.sortable;

      if (!this.isMultiDrag && multiDragSortable) {
        multiDragSortable.multiDrag._deselectMultiDrag();
      }

      multiDragElements.forEach(function (multiDragElement) {
        multiDragElement.sortableIndex = index(multiDragElement);
      }); // Sort multi-drag elements

      multiDragElements = multiDragElements.sort(function (a, b) {
        return a.sortableIndex - b.sortableIndex;
      });
      dragStarted = true;
    },
    dragStarted: function dragStarted(_ref7) {
      var _this2 = this;

      var sortable = _ref7.sortable;
      if (!this.isMultiDrag) return;

      if (this.options.sort) {
        // Capture rects,
        // hide multi drag elements (by positioning them absolute),
        // set multi drag elements rects to dragRect,
        // show multi drag elements,
        // animate to rects,
        // unset rects & remove from DOM
        sortable.captureAnimationState();

        if (this.options.animation) {
          multiDragElements.forEach(function (multiDragElement) {
            if (multiDragElement === dragEl$1) return;
            css(multiDragElement, 'position', 'absolute');
          });
          var dragRect = getRect(dragEl$1, false, true, true);
          multiDragElements.forEach(function (multiDragElement) {
            if (multiDragElement === dragEl$1) return;
            setRect(multiDragElement, dragRect);
          });
          folding = true;
          initialFolding = true;
        }
      }

      sortable.animateAll(function () {
        folding = false;
        initialFolding = false;

        if (_this2.options.animation) {
          multiDragElements.forEach(function (multiDragElement) {
            unsetRect(multiDragElement);
          });
        } // Remove all auxiliary multidrag items from el, if sorting enabled


        if (_this2.options.sort) {
          removeMultiDragElements();
        }
      });
    },
    dragOver: function dragOver(_ref8) {
      var target = _ref8.target,
          completed = _ref8.completed,
          cancel = _ref8.cancel;

      if (folding && ~multiDragElements.indexOf(target)) {
        completed(false);
        cancel();
      }
    },
    revert: function revert(_ref9) {
      var fromSortable = _ref9.fromSortable,
          rootEl = _ref9.rootEl,
          sortable = _ref9.sortable,
          dragRect = _ref9.dragRect;

      if (multiDragElements.length > 1) {
        // Setup unfold animation
        multiDragElements.forEach(function (multiDragElement) {
          sortable.addAnimationState({
            target: multiDragElement,
            rect: folding ? getRect(multiDragElement) : dragRect
          });
          unsetRect(multiDragElement);
          multiDragElement.fromRect = dragRect;
          fromSortable.removeAnimationState(multiDragElement);
        });
        folding = false;
        insertMultiDragElements(!this.options.removeCloneOnHide, rootEl);
      }
    },
    dragOverCompleted: function dragOverCompleted(_ref10) {
      var sortable = _ref10.sortable,
          isOwner = _ref10.isOwner,
          insertion = _ref10.insertion,
          activeSortable = _ref10.activeSortable,
          parentEl = _ref10.parentEl,
          putSortable = _ref10.putSortable;
      var options = this.options;

      if (insertion) {
        // Clones must be hidden before folding animation to capture dragRectAbsolute properly
        if (isOwner) {
          activeSortable._hideClone();
        }

        initialFolding = false; // If leaving sort:false root, or already folding - Fold to new location

        if (options.animation && multiDragElements.length > 1 && (folding || !isOwner && !activeSortable.options.sort && !putSortable)) {
          // Fold: Set all multi drag elements's rects to dragEl's rect when multi-drag elements are invisible
          var dragRectAbsolute = getRect(dragEl$1, false, true, true);
          multiDragElements.forEach(function (multiDragElement) {
            if (multiDragElement === dragEl$1) return;
            setRect(multiDragElement, dragRectAbsolute); // Move element(s) to end of parentEl so that it does not interfere with multi-drag clones insertion if they are inserted
            // while folding, and so that we can capture them again because old sortable will no longer be fromSortable

            parentEl.appendChild(multiDragElement);
          });
          folding = true;
        } // Clones must be shown (and check to remove multi drags) after folding when interfering multiDragElements are moved out


        if (!isOwner) {
          // Only remove if not folding (folding will remove them anyways)
          if (!folding) {
            removeMultiDragElements();
          }

          if (multiDragElements.length > 1) {
            var clonesHiddenBefore = clonesHidden;

            activeSortable._showClone(sortable); // Unfold animation for clones if showing from hidden


            if (activeSortable.options.animation && !clonesHidden && clonesHiddenBefore) {
              multiDragClones.forEach(function (clone) {
                activeSortable.addAnimationState({
                  target: clone,
                  rect: clonesFromRect
                });
                clone.fromRect = clonesFromRect;
                clone.thisAnimationDuration = null;
              });
            }
          } else {
            activeSortable._showClone(sortable);
          }
        }
      }
    },
    dragOverAnimationCapture: function dragOverAnimationCapture(_ref11) {
      var dragRect = _ref11.dragRect,
          isOwner = _ref11.isOwner,
          activeSortable = _ref11.activeSortable;
      multiDragElements.forEach(function (multiDragElement) {
        multiDragElement.thisAnimationDuration = null;
      });

      if (activeSortable.options.animation && !isOwner && activeSortable.multiDrag.isMultiDrag) {
        clonesFromRect = _extends({}, dragRect);
        var dragMatrix = matrix(dragEl$1, true);
        clonesFromRect.top -= dragMatrix.f;
        clonesFromRect.left -= dragMatrix.e;
      }
    },
    dragOverAnimationComplete: function dragOverAnimationComplete() {
      if (folding) {
        folding = false;
        removeMultiDragElements();
      }
    },
    drop: function drop(_ref12) {
      var evt = _ref12.originalEvent,
          rootEl = _ref12.rootEl,
          parentEl = _ref12.parentEl,
          sortable = _ref12.sortable,
          dispatchSortableEvent = _ref12.dispatchSortableEvent,
          oldIndex = _ref12.oldIndex,
          putSortable = _ref12.putSortable;
      var toSortable = putSortable || this.sortable;
      if (!evt) return;
      var options = this.options,
          children = parentEl.children; // Multi-drag selection

      if (!dragStarted) {
        if (options.multiDragKey && !this.multiDragKeyDown) {
          this._deselectMultiDrag();
        }

        toggleClass(dragEl$1, options.selectedClass, !~multiDragElements.indexOf(dragEl$1));

        if (!~multiDragElements.indexOf(dragEl$1)) {
          multiDragElements.push(dragEl$1);
          dispatchEvent({
            sortable: sortable,
            rootEl: rootEl,
            name: 'select',
            targetEl: dragEl$1,
            originalEvt: evt
          }); // Modifier activated, select from last to dragEl

          if (evt.shiftKey && lastMultiDragSelect && sortable.el.contains(lastMultiDragSelect)) {
            var lastIndex = index(lastMultiDragSelect),
                currentIndex = index(dragEl$1);

            if (~lastIndex && ~currentIndex && lastIndex !== currentIndex) {
              // Must include lastMultiDragSelect (select it), in case modified selection from no selection
              // (but previous selection existed)
              var n, i;

              if (currentIndex > lastIndex) {
                i = lastIndex;
                n = currentIndex;
              } else {
                i = currentIndex;
                n = lastIndex + 1;
              }

              for (; i < n; i++) {
                if (~multiDragElements.indexOf(children[i])) continue;
                toggleClass(children[i], options.selectedClass, true);
                multiDragElements.push(children[i]);
                dispatchEvent({
                  sortable: sortable,
                  rootEl: rootEl,
                  name: 'select',
                  targetEl: children[i],
                  originalEvt: evt
                });
              }
            }
          } else {
            lastMultiDragSelect = dragEl$1;
          }

          multiDragSortable = toSortable;
        } else {
          multiDragElements.splice(multiDragElements.indexOf(dragEl$1), 1);
          lastMultiDragSelect = null;
          dispatchEvent({
            sortable: sortable,
            rootEl: rootEl,
            name: 'deselect',
            targetEl: dragEl$1,
            originalEvt: evt
          });
        }
      } // Multi-drag drop


      if (dragStarted && this.isMultiDrag) {
        folding = false; // Do not "unfold" after around dragEl if reverted

        if ((parentEl[expando].options.sort || parentEl !== rootEl) && multiDragElements.length > 1) {
          var dragRect = getRect(dragEl$1),
              multiDragIndex = index(dragEl$1, ':not(.' + this.options.selectedClass + ')');
          if (!initialFolding && options.animation) dragEl$1.thisAnimationDuration = null;
          toSortable.captureAnimationState();

          if (!initialFolding) {
            if (options.animation) {
              dragEl$1.fromRect = dragRect;
              multiDragElements.forEach(function (multiDragElement) {
                multiDragElement.thisAnimationDuration = null;

                if (multiDragElement !== dragEl$1) {
                  var rect = folding ? getRect(multiDragElement) : dragRect;
                  multiDragElement.fromRect = rect; // Prepare unfold animation

                  toSortable.addAnimationState({
                    target: multiDragElement,
                    rect: rect
                  });
                }
              });
            } // Multi drag elements are not necessarily removed from the DOM on drop, so to reinsert
            // properly they must all be removed


            removeMultiDragElements();
            multiDragElements.forEach(function (multiDragElement) {
              if (children[multiDragIndex]) {
                parentEl.insertBefore(multiDragElement, children[multiDragIndex]);
              } else {
                parentEl.appendChild(multiDragElement);
              }

              multiDragIndex++;
            }); // If initial folding is done, the elements may have changed position because they are now
            // unfolding around dragEl, even though dragEl may not have his index changed, so update event
            // must be fired here as Sortable will not.

            if (oldIndex === index(dragEl$1)) {
              var update = false;
              multiDragElements.forEach(function (multiDragElement) {
                if (multiDragElement.sortableIndex !== index(multiDragElement)) {
                  update = true;
                  return;
                }
              });

              if (update) {
                dispatchSortableEvent('update');
              }
            }
          } // Must be done after capturing individual rects (scroll bar)


          multiDragElements.forEach(function (multiDragElement) {
            unsetRect(multiDragElement);
          });
          toSortable.animateAll();
        }

        multiDragSortable = toSortable;
      } // Remove clones if necessary


      if (rootEl === parentEl || putSortable && putSortable.lastPutMode !== 'clone') {
        multiDragClones.forEach(function (clone) {
          clone.parentNode && clone.parentNode.removeChild(clone);
        });
      }
    },
    nullingGlobal: function nullingGlobal() {
      this.isMultiDrag = dragStarted = false;
      multiDragClones.length = 0;
    },
    destroyGlobal: function destroyGlobal() {
      this._deselectMultiDrag();

      off(document, 'pointerup', this._deselectMultiDrag);
      off(document, 'mouseup', this._deselectMultiDrag);
      off(document, 'touchend', this._deselectMultiDrag);
      off(document, 'keydown', this._checkKeyDown);
      off(document, 'keyup', this._checkKeyUp);
    },
    _deselectMultiDrag: function _deselectMultiDrag(evt) {
      if (typeof dragStarted !== "undefined" && dragStarted) return; // Only deselect if selection is in this sortable

      if (multiDragSortable !== this.sortable) return; // Only deselect if target is not item in this sortable

      if (evt && closest(evt.target, this.options.draggable, this.sortable.el, false)) return; // Only deselect if left click

      if (evt && evt.button !== 0) return;

      while (multiDragElements.length) {
        var el = multiDragElements[0];
        toggleClass(el, this.options.selectedClass, false);
        multiDragElements.shift();
        dispatchEvent({
          sortable: this.sortable,
          rootEl: this.sortable.el,
          name: 'deselect',
          targetEl: el,
          originalEvt: evt
        });
      }
    },
    _checkKeyDown: function _checkKeyDown(evt) {
      if (evt.key === this.options.multiDragKey) {
        this.multiDragKeyDown = true;
      }
    },
    _checkKeyUp: function _checkKeyUp(evt) {
      if (evt.key === this.options.multiDragKey) {
        this.multiDragKeyDown = false;
      }
    }
  };
  return _extends(MultiDrag, {
    // Static methods & properties
    pluginName: 'multiDrag',
    utils: {
      /**
       * Selects the provided multi-drag item
       * @param  {HTMLElement} el    The element to be selected
       */
      select: function select(el) {
        var sortable = el.parentNode[expando];
        if (!sortable || !sortable.options.multiDrag || ~multiDragElements.indexOf(el)) return;

        if (multiDragSortable && multiDragSortable !== sortable) {
          multiDragSortable.multiDrag._deselectMultiDrag();

          multiDragSortable = sortable;
        }

        toggleClass(el, sortable.options.selectedClass, true);
        multiDragElements.push(el);
      },

      /**
       * Deselects the provided multi-drag item
       * @param  {HTMLElement} el    The element to be deselected
       */
      deselect: function deselect(el) {
        var sortable = el.parentNode[expando],
            index = multiDragElements.indexOf(el);
        if (!sortable || !sortable.options.multiDrag || !~index) return;
        toggleClass(el, sortable.options.selectedClass, false);
        multiDragElements.splice(index, 1);
      }
    },
    eventProperties: function eventProperties() {
      var _this3 = this;

      var oldIndicies = [],
          newIndicies = [];
      multiDragElements.forEach(function (multiDragElement) {
        oldIndicies.push({
          multiDragElement: multiDragElement,
          index: multiDragElement.sortableIndex
        }); // multiDragElements will already be sorted if folding

        var newIndex;

        if (folding && multiDragElement !== dragEl$1) {
          newIndex = -1;
        } else if (folding) {
          newIndex = index(multiDragElement, ':not(.' + _this3.options.selectedClass + ')');
        } else {
          newIndex = index(multiDragElement);
        }

        newIndicies.push({
          multiDragElement: multiDragElement,
          index: newIndex
        });
      });
      return {
        items: _toConsumableArray(multiDragElements),
        clones: [].concat(multiDragClones),
        oldIndicies: oldIndicies,
        newIndicies: newIndicies
      };
    },
    optionListeners: {
      multiDragKey: function multiDragKey(key) {
        key = key.toLowerCase();

        if (key === 'ctrl') {
          key = 'Control';
        } else if (key.length > 1) {
          key = key.charAt(0).toUpperCase() + key.substr(1);
        }

        return key;
      }
    }
  });
}

function insertMultiDragElements(clonesInserted, rootEl) {
  multiDragElements.forEach(function (multiDragElement, i) {
    var target = rootEl.children[multiDragElement.sortableIndex + (clonesInserted ? Number(i) : 0)];

    if (target) {
      rootEl.insertBefore(multiDragElement, target);
    } else {
      rootEl.appendChild(multiDragElement);
    }
  });
}
/**
 * Insert multi-drag clones
 * @param  {[Boolean]} elementsInserted  Whether the multi-drag elements are inserted
 * @param  {HTMLElement} rootEl
 */


function insertMultiDragClones(elementsInserted, rootEl) {
  multiDragClones.forEach(function (clone, i) {
    var target = rootEl.children[clone.sortableIndex + (elementsInserted ? Number(i) : 0)];

    if (target) {
      rootEl.insertBefore(clone, target);
    } else {
      rootEl.appendChild(clone);
    }
  });
}

function removeMultiDragElements() {
  multiDragElements.forEach(function (multiDragElement) {
    if (multiDragElement === dragEl$1) return;
    multiDragElement.parentNode && multiDragElement.parentNode.removeChild(multiDragElement);
  });
}

Sortable.mount(new AutoScrollPlugin());
Sortable.mount(Remove, Revert);

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Sortable);



/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			id: moduleId,
/******/ 			loaded: false,
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/node module decorator */
/******/ 	(() => {
/******/ 		__webpack_require__.nmd = (module) => {
/******/ 			module.paths = [];
/******/ 			if (!module.children) module.children = [];
/******/ 			return module;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/assets/admin/js/scripts": 0,
/******/ 			"assets/index/css/style": 0,
/******/ 			"assets/admin/css/styles": 0,
/******/ 			"assets/index/css/bootstrap": 0,
/******/ 			"assets/driver/css/styles": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["assets/index/css/style","assets/admin/css/styles","assets/index/css/bootstrap","assets/driver/css/styles"], () => (__webpack_require__("./resources/assets/admin/js/index.js")))
/******/ 	__webpack_require__.O(undefined, ["assets/index/css/style","assets/admin/css/styles","assets/index/css/bootstrap","assets/driver/css/styles"], () => (__webpack_require__("./resources/assets/admin/less/styles.less")))
/******/ 	__webpack_require__.O(undefined, ["assets/index/css/style","assets/admin/css/styles","assets/index/css/bootstrap","assets/driver/css/styles"], () => (__webpack_require__("./node_modules/toastr/toastr.less")))
/******/ 	__webpack_require__.O(undefined, ["assets/index/css/style","assets/admin/css/styles","assets/index/css/bootstrap","assets/driver/css/styles"], () => (__webpack_require__("./resources/assets/index/css/bootstrap.scss")))
/******/ 	__webpack_require__.O(undefined, ["assets/index/css/style","assets/admin/css/styles","assets/index/css/bootstrap","assets/driver/css/styles"], () => (__webpack_require__("./resources/assets/admin/less/mainStyles.css")))
/******/ 	__webpack_require__.O(undefined, ["assets/index/css/style","assets/admin/css/styles","assets/index/css/bootstrap","assets/driver/css/styles"], () => (__webpack_require__("./resources/assets/admin/less/forBusScale.css")))
/******/ 	__webpack_require__.O(undefined, ["assets/index/css/style","assets/admin/css/styles","assets/index/css/bootstrap","assets/driver/css/styles"], () => (__webpack_require__("./resources/assets/driver/css/main.css")))
/******/ 	__webpack_require__.O(undefined, ["assets/index/css/style","assets/admin/css/styles","assets/index/css/bootstrap","assets/driver/css/styles"], () => (__webpack_require__("./resources/assets/index/css/plugins/froala.css")))
/******/ 	__webpack_require__.O(undefined, ["assets/index/css/style","assets/admin/css/styles","assets/index/css/bootstrap","assets/driver/css/styles"], () => (__webpack_require__("./resources/assets/index/plugins/datepicker3.css")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["assets/index/css/style","assets/admin/css/styles","assets/index/css/bootstrap","assets/driver/css/styles"], () => (__webpack_require__("./resources/assets/index/css/main.css")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;