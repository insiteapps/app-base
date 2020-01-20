/**
 * Javascript-Template, needs to be evaluated by Requirements::javascriptTemplate
 */
$.fn.getInputType = function () {
    return this[0].tagName == "INPUT" ? this[0].type.toLowerCase() : this[0].tagName.toLowerCase();
}

var AjaxValidator = function () {
    return {
        IsValid: function (form, aRequiredFields, prefix) {

            if (typeof prefix === 'undefined') {
                prefix = '';
            }

            if (aRequiredFields.length && (typeof aRequiredFields !== 'undefined')) {
                //Validate required fields
                for (var i = 0; i < aRequiredFields.length; i++) {
                    var input = $('#' + prefix + aRequiredFields[i]);
                    var isSelect = (input.getInputType() === 'select');
                    var inputVal = input.val();
                    var inputLabel = input.closest('div.field').find('label').text();
                    var inputName = (input.attr('placeholder')) ? input.attr('placeholder') : input.attr('name');
                    var inputTitle = inputLabel ? inputLabel : inputName;
                    var fieldErrorMsg = '"' + $.trim(inputTitle) + '" is required';
                    if ((!inputVal) || (inputVal === fieldErrorMsg)) {
                        input.addClass("needsfilled");
                        if (isSelect) {
                            console.log(aRequiredFields[i] + " - " + isSelect);
                            var errSpan = '<span class="errSpan message required">' + fieldErrorMsg + '</span>';
                            var SelectHolder = input.closest('div.field');
                            if (!SelectHolder.hasClass("needsfilled")) {
                                //SelectHolder.addClass("needsfilled");
                                //SelectHolder.append(errSpan);

                            }
                            input.append('<option class="needsfilled" value="" selected="selected">'+fieldErrorMsg+'</option>');
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

                var input = $(this);

                if (input.hasClass("needsfilled")) {
                    if (input.getInputType() === 'select') {
                        input.find('option.needsfilled').remove();
                        input.closest('div.field').addClass('needsfilled-done');
                    } else {
                        input.val("");

                    }
                    input.removeClass("needsfilled");
                }


            });
            //if any inputs on the page have the class 'needsfilled' the form will not submit
            if ($(":input").hasClass("needsfilled")) {
                $('html, body').animate({
                    scrollTop: $(".needsfilled").offset().top - 76
                }, 1000);
                return false;
            }


            return true;
        }
    }
}();
