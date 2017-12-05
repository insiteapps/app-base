var AjaxLoading = function () {
    return {
        start: function (id, reverse, dark) {
            AjaxLoading = this.AjaxLoading(dark);
            var $container = $("#" + id);
            $container.append(AjaxLoading);
            var AjaxLoading = $container.find(".AjaxLoading");
            if (reverse) {
                AjaxLoading.remove();
            } else {
                AjaxLoading.show();
            }
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
            $(".datepickerfield").datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $(".timepickerfield").datetimepicker({
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
            $.ajax({
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
