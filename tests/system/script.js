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

        /*$form.on("submit", function (event) {
            event.preventDefault();
        });*/

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