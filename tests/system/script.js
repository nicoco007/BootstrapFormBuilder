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

$(function () {
    $('.bsfb-form').each(function () {
        var $form = $(this);

        $form.find('[type="submit"]').on('click', function () {
            $(this).attr('clicked', 'true');
        });

        $form.on("submit", function (event) {
            event.preventDefault();
            submitForm($form);
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
});

function submitForm($form) {
    // disable everything
    $form.find('fieldset, button').prop('disabled', true);
    $form.find('a').addClass('disabled');
    $form.find('.alert').remove();

    var $button = $form.find('button[type="submit"][clicked=true]');
    var $fa = $button.find('i.fa');
    var faTemp;

    $button.removeAttr('clicked');

    if ($fa.length) {
        faTemp = $fa.attr('class');
        $fa.attr('class', 'fa fa-spinner fa-spin');
    } else {
        $button.prepend('<i class="fa fa-spinner fa-spin"></i> ');
    }

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
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $form.prepend('<div class="alert alert-danger">Unable to submit form: ' + errorThrown + '</div>');
        },
        complete: function () {
            if (faTemp)
                $fa.attr('class', faTemp);
            else
                $button.find('i.fa').remove();

            var scroll;
            var $firstError = $form.find('.is-invalid:first').parents('.form-group');

            if ($firstError.length)
                scroll = $firstError.offset().top - 10; // magic number is painfully magic
            else
                scroll = $form.offset().top - 50; // ouch

            $('html, body').animate({
                scrollTop: scroll
            }, 100);

            $form.find('fieldset, button').prop('disabled', false);
            $form.find('a').removeClass('disabled');
        }
    });
}