var $window = $(window),
    $document = $(document),
    $html = $('html'), jsArray = [],
    $body = $('body');

jQuery.browser = {};
(function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1;
    }
})();

var AjaxLoading = function () {
    return {
        start: function (id, reverse, dark) {

            var $container = jQuery("#" + id);
            $container.append(this._AJL(dark));
            var elem = $container.find(".AjaxLoading");
            if (reverse) {
                elem.remove();
            } else {
                elem.show();
            }
        },
        end: function (id) {
            var $container = jQuery("#" + id);
            var elem = $container.find(".AjaxLoading");
            elem.remove();
        },
        _AJL: function (dark) {
            var dark_class = "";
            if (typeof dark !== "undefined") {
                dark_class = "dark";
            }
            return "<div class=\"AjaxLoading " + dark_class + " \">Processing ...</div>";
        },
        AjaxLoadingGears: function () {
            return "<div class=\"AjaxLoadingGears\"></div>";
        }
    };
}();

var BootstrapDateTimePicker = function () {
    return {
        init: function () {
            jQuery(".datepickerfield").datetimepicker({
                format: 'YYYY-MM-DD'
            });
            jQuery(".timepickerfield").datetimepicker({
                format: 'HH:mm',
            });
        }
    };
}();

var NotyManager = function () {
    return {
        init: function (text, type, title) {

            new Noty({
                text: text,
                type: type ? type : 'success',
                title: title ? title : 'Success!',
                theme: 'metroui',
                maxVisible: 3,
                timeout: 2000,
                force: true,
                layout: 'center'
            }).show();

        },
        metroui: function (text, type, title) {
            this.init(text, type, title);
        }
    };
}();

var _aj = function () {
    return {
        i: function (u, d, s, e, t, dt) {
            jQuery.ajax({
                url: u,
                type: t ? t : 'post',
                dataType: dt ? dt : 'json',
                data: d,
                success: s,
                error: e
            });
        },
        errorHandler: function () {
            alert('Sorry there has been an error');
        }
    };


    var s = function (data) {

    };

    var e = function () {
        alert('Sorry there has been an error');
    };
}();

let _ajx = () => {
    let _c = (xhr, textStatus) => {
        console.log(xhr.status);
        console.log(textStatus);

    }, _e = (xhr, status, thrownError, error) => {

        if (status === "timeout") {
            console.log("got timeout");
        } else {
            console.log(status);
        }

        //alert('Sorry there has been an error');
        //console.log(status);
        //console.log(xhr.status);
        //console.log(thrownError);

    }, _bs = () => {

    }


    return {
        i: (u, d, s, c, e, b, t, dt) => {
            $.ajax({
                url: u,
                type: t ? t : 'POST',
                dataType: dt ? dt : 'json',
                data: d,
                success: s,
                complete: c ? c : _c,
                beforeSend: b ? b : _bs,
                error: e ? e : _e,
                cache: true,
                async: true,
                timeout: 10000
            });
        },

    };

}

function loadScript(scriptName, callback) {

    if (!jsArray[scriptName]) {
        var promise = jQuery.Deferred();

        // adding the script tag to the head as suggested before
        var body = document.getElementsByTagName('body')[0],
            script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = scriptName;

        // then bind the event to the callback function
        // there are several events for cross browser compatibility
        script.onload = function () {
            promise.resolve();
        };

        // fire the loading
        body.appendChild(script);

        // clear DOM reference
        //body = null;
        //script = null;

        jsArray[scriptName] = promise.promise();

    } else if (debugState)
        console.log("This script was already loaded %c: " + scriptName);

    jsArray[scriptName].then(function () {
        if (typeof callback === 'function')
            callback();
    });
}

var ismobile = (/iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));

var PlugInManager = function () {
    return {
        initSpectrum: function () {

            jQuery("input.spectrum-colour,input.colorpicker").spectrum({
                clickoutFiresChange: true,
                showInitial: true,
                flat: false,
                showSelectionPalette: true,
                showInput: true,
                allowEmpty: true,
                showAlpha: true,
                preferredFormat: "rgb",
                showPalette: true,
                palette: [
                    ["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
                        "rgb(204, 204, 204)", "rgb(217, 217, 217)", "rgb(255, 255, 255)"],
                    ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
                        "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
                    ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
                        "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
                        "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
                        "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
                        "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
                        "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
                        "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
                        "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
                        "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
                        "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
                ]

            });
        }
    }
}();

PlugInManager.niceScrollInit = function () {

    var niceScrollOptions = {
        zindex: 5000,
        styler: "fb",
        cursorcolor: "#fff",
        cursordragontouch: false,
        scrollspeed: 10,
        mousescrollstep: 300,
        touchbehavior: true,
        smoothscroll: false // because it interferes with the hor to ver scroll script
    }

    if (window.isWindows) {
        var mScrollBar = $(".mScrollBar");
        mScrollBar.niceScroll(niceScrollOptions);
        mScrollBar.getNiceScroll().hide();


        $html.niceScroll(niceScrollOptions);
        $html.getNiceScroll().hide();

        $html.addClass('has--nicescroll');

        $(document).on('jp_carousel.afterClose', function () {
            $html.getNiceScroll().resize();
        });
    }
}