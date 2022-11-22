let settingsShow = false;
var Cookie = "Font Size";
var fontsize = $.cookie(Cookie) || 'normal';

$(document).ready(function () {
    $(".glyphicon-chevron-down").css({
        "top": "8px"
    });
    if (fontsize == "normal") {
        $("body, button, span:not(.glyphicon)").css({
            "font-size": "1.25rem"
        });
        $("#normal").prop("checked", true);
    } else if (fontsize == "larger") {
        $("body, button, span:not(.glyphicon)").css({
            "font-size": "1.4rem"
        });
        $("#larger").prop("checked", true);
    } else {
        $("body, button, span:not(.glyphicon)").css({
            "font-size": "1.6rem"
        });
        $("#biggest").prop("checked", true);
    }

    $(document).on('click', '.js_settings_show', function () {
        settingsShow = !settingsShow;
        if (settingsShow) {
            $("#settings").show();
        } else {
            $("#settings").hide();
        }
    });
});

function biggestFont() {
    $("body, button, span").css({
        "font-size": "1.6rem"
    });
    fontsize = 'biggest';
    $.cookie(Cookie, fontsize);
}

function largerFont() {
    $("body, button, span").css({
        "font-size": "1.4rem"
    });
    fontsize = 'larger';
    $.cookie(Cookie, fontsize);
}

function normalFont() {
    $("body, button, span").css({
        "font-size": "1.25rem"
    });
    fontsize = 'normal';
    $.cookie(Cookie, fontsize);
}

