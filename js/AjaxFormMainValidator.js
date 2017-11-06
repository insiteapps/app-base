/**
 * Javascript-Template, needs to be evaluated by Requirements::javascriptTemplate
 */
$.fn.getInputType = function () {
    return this[0].tagName == "INPUT" ? this[0].type.toLowerCase() : this[0].tagName.toLowerCase();
}

var AjaxFormMainValidator = function () {

    return {
        init: function () {

        },
        validate: function (form, aRequiredFields, prefix, submit) {
            var valid = AjaxValidator.IsValid(form, aRequiredFields, prefix);
            if (valid) {
                AjaxFormMainValidator.submit(form);
            }
        },
        submit: function (form) {

            var messageArea = form.find('.message');
            form.ajaxSubmit({
                dataType: 'json',
                type: 'post',
                success: function (data) {
                    if (data.url) {
                        window.location.href = data.url;
                        return;
                    }

                    if (data.success) {

                        messageArea.html('');
                        messageArea.html(data.message);
                        messageArea.show();
                        form.clearForm();
                        form.resetForm();
                        form.find('fieldset,.Actions').hide();
                    }
                }
            });
        },
        process: function (form, data, messageArea) {
            messageArea.removeClass('alert alert-danger');
            messageArea.html('');
            messageArea.hide();
            messageArea.html(data.message);
            messageArea.show();
            form.clearForm();
            form.resetForm();
            $('html, body').animate({
                scrollTop: $("#Layout").offset().top
            }, 1000);

            if (data.url) {
                window.location.href = data.url;
            } else {
                loadButtonAjaxStart(form, true);
                loadAjaxStart(form.attr('id'), true);
            }
        }
        ,
        loadAjaxStart: function (id, reverse) {
            var AjaxLoading = "<div class=\"AjaxLoading\"></div>";
            var $container = $("#" + id);
            $container.append(AjaxLoading);
            var AjaxLoading = $container.find(".AjaxLoading");
            if (reverse) {
                AjaxLoading.remove();
            } else {
                AjaxLoading.show();
            }
        }
        ,
        loadButtonAjaxStart: function (form, reverse) {
            var $container = form.find('button.action');
            //$container.html('Saving...');
            if (reverse) {
                //$container.html('Submit')
            }
        }
    }
}();
