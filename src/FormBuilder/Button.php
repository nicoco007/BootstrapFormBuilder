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


abstract class Button
{
    /** @var string */
    private $id;

    /** @var string */
    private $text;

    /** @var string */
    private $class;

    /** @var string */
    private $icon;

    /**
     * Button constructor.
     * @param string $id
     * @param string $text
     * @param string $class
     * @param string|null $icon
     */
    public function __construct($id, $text, $class, $icon = null)
    {
        if (!is_string($id))
            throw new \InvalidArgumentException('Expected $id to be string, got ' . Util::getType($id));

        if (!is_string($text))
            throw new \InvalidArgumentException('Expected $text to be string, got ' . Util::getType($text));

        if (!is_string($class))
            throw new \InvalidArgumentException('Expected $class to be string, got ' . Util::getType($class));

        if ($icon !== null && !is_string($icon))
            throw new \InvalidArgumentException('Expected $icon to be null or string, got ' . Util::getType($icon));

        $this->id = $id;
        $this->text = $text;
        $this->class = $class;
        $this->icon = $icon;
    }

    public abstract function render();

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    protected function getText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    protected function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    protected function getIcon()
    {
        return $this->icon;
    }
}