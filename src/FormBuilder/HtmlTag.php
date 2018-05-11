<?php
/**
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

namespace FormBuilder;


class HtmlTag
{
    /** @var string */
    private $name;

    /** @var bool */
    private $self_enclosed;

    /** @var object[] */
    private $attributes;

    public function __construct($name, $self_enclosed = false)
    {
        $this->name = $name;
        $this->self_enclosed = $self_enclosed;
    }

    public function addAttribute($name, $value = null)
    {
        $this->attributes[] = ['name' => $name, 'value' => $value];
    }

    public function render()
    {
        $attributes_str = implode(' ', array_map(function ($attr) {
            if ($attr['value'] !== null)
                return sprintf('%s="%s"', $attr['name'], $attr['value']);
            else
                return $attr['name'];
        }, $this->attributes));

        printf('<%s %s>', $this->name, $attributes_str);

        if (!$this->self_enclosed)
            printf('</%s>', $this->name);
    }
}