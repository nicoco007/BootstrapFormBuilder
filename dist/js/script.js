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
    init: function () {
        var self = this;

        $('.bsfb-form').each(function () {
            var $form = $(this);

            $form.find('[type="submit"]').on('click', function () {
                $(this).attr('clicked', 'true');
            });

            $form.find('input, textarea').on('change', function () {
                window.onbeforeunload = function (event) {
                    event.returnValue = 'no';
                    return 'no';
                };
            });

            $form.on("submit", function (event) {
                event.preventDefault();
                self.form.submit($form);
            });

            // initialize date/time pickers
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
                    locale: $input.data('locale') || 'en'
                };

                if ($input.data('min-date'))
                    options['minDate'] = new Date($input.data('min-date'));

                if ($input.data('max-date'))
                    options['maxDate'] = new Date($input.data('max-date'));

                $inputGroup.datetimepicker(options);
            });

            $form.find('select').each(function () {
                var $input = $(this);
                var $options = $input.find('option');
                var id = $input.attr('id') || $input.attr('name');

                var $dropdown = $('<div class="dropdown bsfb-select">');
                var $button = $('<button class="btn btn-light dropdown-toggle form-control" type="button" id="' + id + '-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-ref="' + id + '">');
                var $buttonText = $('<span class="select-text"></span>');
                var $buttonTextInner = $('<span class="select-text-inner"></span>');
                var $menu = $('<div class="dropdown-menu" aria-labelledby="' + id + '-dropdown">');

                $options.each(function () {
                    var $option = $(this);
                    var $element = $('<span class="dropdown-item" data-value="' + $option.val() + '">' + $option.text() + '</span>');

                    $element.on('click', function () {
                        $input.val($element.data('value'));
                        $buttonTextInner.text($element.text())
                    });

                    $menu.append($element);
                });

                var $selectedOption = $options.filter('[value="' + $input.val() + '"]').first();

                if (!$selectedOption.length)
                    $selectedOption = $options.first();

                $buttonTextInner.text($selectedOption.text());

                $input.after($dropdown);
                $input.remove();

                $buttonText.append($buttonTextInner);
                $button.append($buttonText);
                $dropdown.append($button);
                $dropdown.append($menu);
                $dropdown.append($input);
            });
        });
    },
    form: {
        submit: function ($form) {
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

            $form.find('input, textarea, select, button').each(function () {
                var $input = $(this);
                var $group = $input.parents('.form-group');

                $input.removeClass('is-invalid');
                $group.find('.invalid-feedback').remove();

                if ($input.attr('type') === 'checkbox') {
                    data[$input.attr('name')] = $input.prop('checked') ? "on" : null;
                } else if ($input.attr('type') === 'radio') {
                    if ($input.prop('checked'))
                        data[$input.attr('name')] = $input.val();
                } else {
                    data[$input.attr('name')] = $input.val();
                }
            });

            $.ajax({
                method: $form.attr('method'),
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: data,
                success: function (json) {
                    self.ajax.success($form, json);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    self.ajax.error($form, jqXHR, textStatus, errorThrown);
                },
                complete: function () {
                    self.ajax.complete($form, $button);
                }
            });
        },
        ajax: {
            success: function ($form, json) {
                if (json['received'] !== true) {
                    $form.prepend('<div class="alert alert-danger">An unexpected server-side error occured. Please try again later.</div>');
                    return;
                }

                if (json['controlErrors']) {
                    for (var key in json['controlErrors']) {
                        // noinspection JSUnfilteredForInLoop
                        var error = json['controlErrors'][key];

                        var $element = $form.find('[name="' + key + '"], [data-ref="' + key + '"]');
                        var $group = $element.closest('.form-group');

                        $element.addClass('is-invalid');

                        $group.find('.form-control:first, .custom-control:last').after('<div class="invalid-feedback d-block">' + error + '</div>');
                    }
                } else if (json['error']) {
                    if (json['error']['message']) {
                        $form.prepend('<div class="alert alert-danger">' + json['error']['message'] + '</div>');
                    } else {
                        $form.prepend('<div class="alert alert-danger">An unexpected error occured.</div>');
                    }
                } else if (json['response']) {
                    $form.prepend('<div class="alert alert-' + json['response']['class'] + '">' + json['response']['message'] + '</div>');

                    if (json['success'])
                        window.onbeforeunload = null;

                    if (json['response']['redirect'])
                        window.location = json['response']['redirect'];
                } else {
                    $form.prepend('<div class="alert alert-success">Form submitted successfully.</div>');
                    window.onbeforeunload = null;
                }
            },
            error: function ($form, jqXHR, textStatus, errorThrown) {
                $form.prepend('<div class="alert alert-danger">Unable to submit form: ' + errorThrown + '</div>');
            },
            complete: function ($form, $button) {
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
            }
        }
    }
};

$(function () {
    BootstrapFormBuilder.init();
});