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
                var $element = $(this);

                var id = $element.find('input').attr('id');
                $element.prepend('<div class="input-group-prepend"><button type="button" class="btn btn-primary" data-target="#' + id + '-wrapper" data-toggle="datetimepicker"><i class="fa fa-calendar"></i></button></div>');

                $element.datetimepicker({
                    format: 'L',
                    locale: $element.data('locale') || 'en'
                })
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

            $form.find('input, textarea').each(function () {
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
                if (json['submitted'] !== true) {
                    $form.prepend('<div class="alert alert-danger">An unexpected server-side error occured. Please try again later.</div>');
                    return;
                }

                var hasErrors;

                for (var key in json['errors']) {
                    hasErrors = true;

                    // noinspection JSUnfilteredForInLoop
                    var error = json['errors'][key];

                    var $element = $form.find('[name="' + key + '"]');
                    var $group = $element.parents('.form-group');

                    $element.addClass('is-invalid');

                    $group.append('<div class="invalid-feedback d-block">' + error + '</div>');
                }

                if (!hasErrors) {
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

                $('html, body').animate({ scrollTop: scroll }, 100);

                $form.find('fieldset, button').prop('disabled', false);
                $form.find('a').removeClass('disabled');
            }
        }
    }
};

$(function () {
    BootstrapFormBuilder.init();
});