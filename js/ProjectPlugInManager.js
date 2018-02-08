var AjaxLoading = function () {
    return {
        start: function (id, reverse, dark) {
            AjaxLoading = this.AjaxLoading(dark);
            var $container = jQuery"#" + id);
            $container.append(AjaxLoading);
            var AjaxLoading = $container.find(".AjaxLoading");
            if (reverse) {
                AjaxLoading.remove();
            } else {
                AjaxLoading.show();
            }
        },
        end: function (id) {
            var $container = jQuery("#" + id);
            var AjaxLoading = $container.find(".AjaxLoading");
            AjaxLoading.remove();
        },
        AjaxLoading: function (dark) {
            var dark_class = "";
            if (typeof dark != "undefined") {
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
        root.root.console.log("This script was already loaded %c: " + scriptName, debugStyle_warning);

    jsArray[scriptName].then(function () {
        if (typeof callback === 'function')
            callback();
    });
}