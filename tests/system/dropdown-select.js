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

jQuery.fn.dropdownSelect = function() {
    var $input = $(this);
    var $options = $input.find('option');
    var id = $input.attr('id') || $input.attr('name');

    var $dropdown = $('<div class="dropdown dropdown-select">');
    var $button = $('<button class="btn btn-light dropdown-toggle form-control" type="button" id="' + id + '-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-ref="' + id + '">');
    var $buttonText = $('<span class="select-text"></span>');
    var $buttonTextInner = $('<span class="select-text-inner"></span>');
    var $menu = $('<div class="dropdown-menu" aria-labelledby="' + id + '-dropdown">');

    $input.css('display', 'none');

    $options.each(function () {
        var $option = $(this);
        var $element = $('<span class="dropdown-item" tabindex="0" data-value="' + $option.val() + '">' + $option.text() + '</span>');

        $element.on('click', function () {
            $input.val($element.data('value'));
            $buttonTextInner.text($element.text());
            $input.trigger('change');
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
    $dropdown.after($input);
};