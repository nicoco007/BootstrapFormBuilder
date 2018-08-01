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

    var liveSearch = $input.data('live-search') === true;

    var addOption = function ($option) {
        var $element = $('<button type="button" class="dropdown-item" data-value="' + $option.val() + '">' + $option.text() + '</button>');

        $element.on('click', function () {
            $input.val($option.val());
            $buttonTextInner.text($option.text());
            $input.trigger('change');
        });

        $menu.append($element);
    };

    if (liveSearch) {
        var $searchInput = $('<input type="text" class="form-control"/>');
        var $searchContainer = $('<div class="search-box"></div>');

        $searchInput.on('input propertychange', function () {
            $menu.find('.dropdown-item').remove();

            var searchTerms = $searchInput.val().toLocaleLowerCase().trim().split(' ');

            $options.each(function () {
                var $option = $(this);
                var text = $option.text().toLocaleLowerCase();

                for (var i = 0; i < searchTerms.length; i++) {
                    if (searchTerms[i] === '' || text.indexOf(searchTerms[i]) > -1) {
                        addOption($option);
                        break;
                    }
                }
            });

            $button.dropdown('update');
        });

        $searchContainer.append($searchInput);
        $menu.append($searchContainer);
    }

    $options.each(function () {
        addOption($(this));
    });

    var $selectedOption = $options.filter('[value="' + $input.val() + '"]');

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

    $button.dropdown();

    $dropdown.on('shown.bs.dropdown', function () {
        if (liveSearch && $input.val() === '')
            $searchInput.focus();
        else
            $dropdown.find('.dropdown-item').filter('[data-value="' + $input.val() + '"]').first().focus();
    });
};