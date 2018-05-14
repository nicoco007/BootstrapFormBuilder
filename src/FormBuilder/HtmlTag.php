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

    /** @var string */
    private $inner;

    public function __construct($name, $self_enclosed = false)
    {
        if (!is_string($name))
            throw new \InvalidArgumentException('Expected $name to be string, got ' . Util::getType($name));

        if (!is_bool($self_enclosed))
            throw new \InvalidArgumentException('Expected $self_enclosed to be string, got ' . Util::getType($self_enclosed));

        $this->name = $name;
        $this->self_enclosed = $self_enclosed;
    }

    public function addAttribute($name, $value = null)
    {
        if (!is_string($name))
            throw new \InvalidArgumentException('Expected $name to be string, got ' . Util::getType($name));

        if (!Util::stringIsNullOrEmpty($value) && !is_string($value))
            throw new \InvalidArgumentException('Expected $value to be string, got ' . Util::getType($value));

        $this->attributes[] = ['name' => $name, 'value' => $value];
    }

    public function setInnerText($text) {
        if ($this->self_enclosed)
            throw new \RuntimeException('Cannot set inner text on self enclosed tag');

        if (!is_string($text))
            throw new \InvalidArgumentException('Expected $text to be string, got ' . Util::getType($text));

        $this->inner = $text;
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

        if ($this->inner !== null)
            print($this->inner);

        if (!$this->self_enclosed)
            printf('</%s>', $this->name);
    }
}