(function ($) {
    "use strict";
    /*global jQuery, document, window*/

    $.fn.getInputType = function () {
        return this[0].tagName === "INPUT" ? this[0].type.toLowerCase() : this[0].tagName.toLowerCase();
    }


}(jQuery));

var AjaxValidator = function () {
    return {
        IsValid: function (form, aRequiredFields) {


            if (aRequiredFields.length && (typeof aRequiredFields !== 'undefined')) {
                //Validate required fields
                for (var i = 0; i < aRequiredFields.length; i++) {
                    var input = $('#' + aRequiredFields[i]);

                    var isSelect = (input.getInputType() === 'select');

                    var inputVal = input.val();

                    var inputLabel = input.closest('div.field').find('label').text();
                    var inputName = (input.attr('placeholder')) ? input.attr('placeholder') : input.attr('name');

                    var inputTitle = inputLabel ? inputLabel : inputName;

                    var fieldErrorMsg = '"' + $.trim(inputTitle) + '" is required';

                    if ((!inputVal) || (inputVal === fieldErrorMsg)) {
                        input.addClass("needsfilled");
                        if (isSelect) {
                            //console.log(aRequiredFields[i] + " - " + isSelect);
                            var errSpan = '<span class="message required">' + fieldErrorMsg + '</span>';
                            var SelectHolder = input.closest('div.field');
                            if (!SelectHolder.hasClass("needsfilled")) {
                                SelectHolder.addClass("needsfilled");
                                SelectHolder.append(errSpan);
                            }


                            /*


                             input.closest('div').find('.chosen-container-single').addClass("needsfilled");
                             input.find('option').removeAttr('selected').trigger("chosen:updated");
                             var optionNeedsfilled = "<option class=\"needsfilled\" value=\"\" disabled selected='selected'>" + fieldErrorMsg + "</option>";
                             input.prepend(optionNeedsfilled);
                             input.trigger("chosen:updated");
                             */
                        } else {
                            input.val(fieldErrorMsg);
                        }
                    } else {
                        if (input.length) {
                            input.removeClass("needsfilled");
                            input.closest('div.field.dropdown').find('.needsfilled').each(function () {
                                $(this).removeClass("needsfilled");
                            });
                        }
                    }
                    if (input.attr('type') === 'email') {
                        // Validate the e-mail.
                        var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
                        if (testEmail.test(input.val())) {
                            input.removeClass("needsfilled");
                        } else {
                            input.val('Please enter a valid email.');
                            input.addClass("needsfilled");
                        }
                    }

                }
            }

            // Clears any fields in the form when the user clicks on them
            $(":input").focus(function () {
                if ($(this).hasClass("needsfilled")) {
                    if ($(this).getInputType() === 'select') {
                        $(this).find('option.needsfilled').remove();
                        $(this).closest('div.field').addClass('needsfilled-done');
                    } else {
                        $(this).val("");

                    }
                    $(this).removeClass("needsfilled");
                }
                var id = $(this).id;
            });
            //if any inputs on the page have the class 'needsfilled' the form will not submit
            if ($(":input").hasClass("needsfilled")) {
                $('html, body').animate({
                    scrollTop: $(".needsfilled").offset().top
                }, 1000);
            }


            return true;
        }
    };
}();