/*
 * Copyright © 2018  Nicolas Gnyra
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

'use strict';

var BootstrapFormBuilder = {
    currentLocale: 'en',
    translations: {},
    init: function () {
        var self = this;

        $('.bsfb-form').each(function () {
            var $form = $(this);

            var locale = $form.data('locale');
            self.setLocale(locale);

            $form.addClass('js');

            $form.find('[type="submit"]').on('click', function () {
                $(this).attr('clicked', 'true');
            });

            $form.on("submit", function (event) {
                event.preventDefault();
                self.submitForm($form);
            });

            $form.find('[data-parent-value]').each(function () {
                var $child = $(this);
                var $target = $('[name="' + $child.data('parent') + '"]');
                var parentValue = $child.data('parent-value');

                $target.on('change', function () {
                    var value = $target.val();

                    if ($target.attr('type') === 'checkbox')
                        value = $target.prop('checked');
                    else if ($target.attr('type') === 'radio')
                        value = $target.filter(':checked').val();

                    if (value === parentValue)
                        $child.addClass('visible');
                    else
                        $child.removeClass('visible');
                }).trigger('change');
            });

            if (typeof $.fn.dropdownSelect === 'function') {
                $form.find('select').each(function () {
                    $(this).dropdownSelect();
                });
            }

            // initialize date/time pickers - requires Tempus Dominus https://tempusdominus.github.io/bootstrap-4/
            if (typeof $.fn.datetimepicker === 'function') {
                $form.find('.datetimepicker-date').each(function () {
                    var $input = $(this);
                    var id = $input.attr('id');
                    var $inputGroup = $('<div class="input-group date" data-target-input="nearest" id="' + id + '-wrapper">');

                    $inputGroup.append('<div class="input-group-prepend"><button type="button" class="btn btn-primary" data-target="#' + id + '-wrapper" data-toggle="datetimepicker"><i class="fa fa-calendar"></i></button></div>');

                    $input.after($inputGroup);
                    $input.remove();

                    $inputGroup.append($input);

                    var options = {
                        format: 'L',
                        locale: locale || 'en'
                    };

                    if ($input.data('min-date'))
                        options['minDate'] = new Date($input.data('min-date'));

                    if ($input.data('max-date'))
                        options['maxDate'] = new Date($input.data('max-date'));

                    $inputGroup.datetimepicker(options);
                });
            }

            // initialize telephone number inputs - requires International Telephone Input https://github.com/jackocnr/intl-tel-input
            if (typeof $.fn.intlTelInput === 'function') {
                $form.find('input[type="tel"]').each(function () {
                    var $input = $(this);

                    var countries = ['US', 'CA'];

                    if ($input.data('pref-countries'))
                        countries = $input.data('pref-countries').split(',');

                    $input.intlTelInput({
                        initialCountry: $input.data('initial-country') || 'US',
                        preferredCountries: countries
                    });
                });
            }

            $form.find('input[type="password"]').each(function () {
                self.showPassword($(this));
            });

            if (typeof zxcvbn === 'function') {
                $form.find('input[type="password"][data-show-strength="true"]').each(function () {
                    self.passwordStrengthMeter($(this));
                });
            }

            $form.find('.form-control, .custom-control-input').on('change keyup input', function () {
                var $group = $(this).parents('.form-group');
                var $elements = $group.find('.form-control, .custom-control-input');

                $elements.removeClass('is-invalid');
                $group.find('.invalid-feedback').remove();

                if ($form.data('prompt-on-leave') === true) {
                    window.onbeforeunload = function (event) {
                        event.returnValue = self.translate('The form has not been saved. Are you sure you want to leave?');
                        return self.translate('The form has not been saved. Are you sure you want to leave?');
                    };
                }
            });
        });
    },
    showPassword: function ($input) {
        var $plaintextInput = $('<input type="text" class="form-control" style="display: none" />');
        var $inputGroup = $('<div class="input-group reveal-password"></div>');
        var $inputGroupAppend = $('<div class="input-group-append"></div>');
        var $button = $('<button class="btn btn-primary" type="button"></button>');

        var copy = ['placeholder', 'minlength'];

        for (var i = 0; i < copy.length; i++) {
            $plaintextInput.attr(copy[i], $input.attr(copy[i]));
        }

        $input.after($inputGroup);
        $input.remove();

        $button.html('<i class="fa fa-eye"></i>');
        $inputGroupAppend.append($button);

        $inputGroup.append($input);
        $inputGroup.append($plaintextInput);
        $inputGroup.append($inputGroupAppend);

        $button.on('click', function () {
            var $fa = $button.find('.fa');
            var visible = $input.css('display') === 'none';

            $input.css('display', visible ? 'block' : 'none');
            $plaintextInput.css('display', visible ? 'none' : 'block');

            $fa.removeClass('fa-eye fa-eye-slash');
            $fa.addClass(visible ? 'fa-eye' : 'fa-eye-slash');
        });

        $input.on('change keyup input', function () { $plaintextInput.val($input.val()) });
        $plaintextInput.on('change keyup input', function () { $input.val($plaintextInput.val()) });
    },
    passwordStrengthMeter: function ($input) {
        var self = this;

        var colors = { 0: 'danger', 1: 'danger', 2: 'warning', 3: 'info', 4: 'success' };
        var descriptions = { 0: 'Very low', 1: 'Low', 2: 'Medium', 3: 'Good', 4: 'Excellent' };

        var $parent = $input.parent();
        var $inputs = $parent.find('input');

        var $inputContainer = $('<div class="input-container"></div>');

        var $progress = $('<div class="progress"></div>');
        var $bar = $('<div class="progress-bar" role="progressbar" style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="5"></div>');

        var $passwordStrength = $('<small class="form-text">' + self.translate('Password strength: ') + '</small>');
        var $passwordStrengthText = $('<span></span>');
        var $warning = $('<small class="form-text"></small>');

        var minLength = $input.attr('minlength') || 0;

        $progress.append($bar);

        $input.after($inputContainer);
        $input.remove();

        $inputContainer.append($inputs);
        $inputContainer.append($progress);

        $passwordStrength.append($passwordStrengthText);

        $parent.addClass('password-strength');
        $parent.after($passwordStrength);
        $parent.after($warning);

        var callback = function ($target) {
            var value = $target.val();

            $inputs.val(value);

            $bar.removeClass('bg-secondary bg-danger bg-warning bg-info bg-success');
            $passwordStrengthText.removeClass('text-secondary text-danger text-warning text-info text-success');
            $warning.removeClass('text-secondary text-danger text-warning text-info text-success');

            if (value.length >= minLength) {
                var result = zxcvbn(value.substr(0, 100));

                $bar.addClass('bg-' + colors[result.score]);
                $passwordStrengthText.addClass('text-' + colors[result.score]);
                $warning.addClass('text-' + colors[result.score]);

                $bar.width((result.score + 1) / 5 * 100 + '%');
                $bar.attr('aria-valuenow', result.score + 1);

                $passwordStrengthText.text(self.translate(descriptions[result.score]));
                $warning.text(self.translate(result.feedback.warning));
            } else if (value.length > 0) {
                $bar.addClass('bg-danger');
                $passwordStrengthText.addClass('text-danger');
                $warning.addClass('text-danger');

                $bar.width('5%');
                $bar.attr('aria-valuenow', 1);

                $passwordStrengthText.text(self.translate('Too short'));
                $warning.text('');
            } else {
                $bar.addClass('bg-secondary');
                $passwordStrengthText.addClass('text-secondary');
                $warning.addClass('text-secondary');

                $bar.width(0);
                $bar.attr('aria-valuenow', 0);

                $passwordStrengthText.text(self.translate('Too short'));
                $warning.text('');
            }
        };

        $inputs.on('change keyup input', function () { callback($(this)); }).trigger('change');
    },
    registerLocale: function (locale, obj) {
        this.translations[locale] = obj;
    },
    setLocale: function (locale) {
        var availableLocales = ['en', 'fr'];
        locale = locale.toLowerCase();

        if (availableLocales.indexOf(locale) > -1)
            this.currentLocale = locale;

        locale = locale.substr(0, 2);

        if (availableLocales.indexOf(locale))
            this.currentLocale = locale;
    },
    translate: function (str) {
        if (this.translations[this.currentLocale]) {
            if (this.translations[this.currentLocale][str])
                return this.translations[this.currentLocale][str];
            else
                console.log('Unable to find localization for string "' + str + '"');
        } else {
            console.log(this.currentLocale + ' not loaded.')
        }

        return str;
    },
    submitForm: function ($form) {
        var self = this;

        // disable everything
        $form.find('fieldset, button').prop('disabled', true);
        $form.find('a').addClass('disabled');
        $form.find('.alert').remove();

        var $button = $form.find('button[type="submit"][clicked=true]');

        $button.removeAttr('clicked');

        var $firstChild = $button.children().first();

        if ($firstChild.is('i.fa'))
            $firstChild.addClass('d-none');

        $button.prepend('<i class="fa fa-spinner fa-spin loader"></i> ');

        var data = {'ajax_submit': true};

        data[$button.attr('name')] = $button.val();

        $form.find('.invalid-feedback').remove();

        $form.find('input, textarea, select, button[type="button"]').each(function () {
            var $input = $(this);

            $input.removeClass('is-invalid');

            if ($input.attr('type') === 'checkbox') {
                data[$input.attr('name')] = $input.prop('checked') ? "on" : null;
            } else if ($input.attr('type') === 'radio') {
                if ($input.prop('checked'))
                    data[$input.attr('name')] = $input.val();
            } else {
                data[$input.attr('name')] = $input.val();
            }
        });

        // noinspection JSCheckFunctionSignatures
        $.ajax({
            method: $form.attr('method'),
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            data: data
        }).done(function (json) {
            self.ajaxSuccess($form, json);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            self.ajaxError($form, jqXHR, textStatus, errorThrown);
        }).always(function (data) {
            self.ajaxComplete($form, data, $button);
        });
    },
    ajaxSuccess: function ($form, json) {
        if (json['received'] !== true) {
            this.showAlert($form, this.translate('An unexpected server-side error occured. Please try again later.'), 'danger');
            return;
        }

        if (json['controlErrors']) {
            for (var key in json['controlErrors']) {
                // noinspection JSUnfilteredForInLoop
                var error = json['controlErrors'][key];

                var $element = $form.find('[name="' + key + '"], [data-ref="' + key + '"]');
                var $group = $element.closest('.form-group');

                $group.find('.form-control, .custom-control-input').addClass('is-invalid');

                // put outside input group if it exists, then try after the form control, then after the last
                // custom control (checkbox or radio)
                $group.find('.input-group, .password-strength, .form-control, .custom-control:last')
                    .first().after('<div class="invalid-feedback d-block">' + error + '</div>');
            }
        } else if (json['error']) {
            if (json['error']['message']) {
                this.showAlert($form, json['error']['message'], 'danger');
            } else {
                this.showAlert($form, this.translate('An unexpected server-side error occured. Please try again later.'), 'danger')
            }
        } else if (json['recaptcha'] && json['recaptcha']['success'] !== true) {
            $form.find('.g-recaptcha').after('<div class="invalid-feedback d-block">' + this.translate('Please complete the CAPTCHA.') + '</div>');
        } else if (json['response']) {
            if (json['success'])
                window.onbeforeunload = null;

            if (json['response']['redirect'])
                window.location = json['response']['redirect'];
            else
                this.showAlert($form, json['response']['message'], json['response']['class'], json['response']['icon']);
        } else if (json['success'] === true) {
            this.showAlert($form, this.translate('Form submitted successfully.'), 'success');
            window.onbeforeunload = null;
        } else {
            this.showAlert($form, this.translate('Unexpected server response. Please try again later.'), 'danger');
        }
    },
    ajaxError: function ($form, jqXHR, textStatus, errorThrown) {
        this.showAlert($form, this.translate('Unable to submit form: ') + errorThrown, 'danger');
    },
    ajaxComplete: function ($form, data, $button) {
        if (data['success'] === true && data['response'] && data['response']['redirect'])
            return;

        $button.find('i.fa.loader').remove();
        $button.children().first().removeClass('d-none');

        var scroll;
        var $firstError = $form.find('.is-invalid:first').parents('.form-group');

        if ($firstError.length)
            scroll = $firstError.offset().top - 10; // magic number is painfully magic
        else
            scroll = $form.offset().top - 50; // ouch

        $('html, body').animate({scrollTop: scroll}, 100);

        $form.find('fieldset, button').prop('disabled', false);
        $form.find('a').removeClass('disabled');

        if (window.grecaptcha)
            grecaptcha.reset();
    },
    showAlert: function ($form, msg, msgClass, icon) {
        var $title = $form.find('.form-title');
        var html = '<div class="alert alert-' + (msgClass || 'info') + '">';

        if (icon)
            html += '<i class="fa fa-' + icon + '"></i>&nbsp;';

        html += msg + '</div>';

        if ($title.length) {
            $title.after(html);
        } else {
            $form.prepend(html);
        }
    }
};

$(function () {
    BootstrapFormBuilder.init();
});