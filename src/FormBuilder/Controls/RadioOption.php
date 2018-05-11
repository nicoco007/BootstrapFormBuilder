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

namespace FormBuilder\Controls;


use FormBuilder\Util;

class RadioOption
{
    /** @var string */
    private $label;

    /** @var string */
    private $key;

    /** @var string */
    private $value;

    /**
     * RadioOption constructor.
     * @param string $label
     * @param string $key
     * @param mixed $value
     */
    public function __construct($label, $key, $value)
    {
        if (!is_string($label))
            throw new \InvalidArgumentException('Expected $label to be string, got ' . Util::getType($label));

        if (!is_string($key))
            throw new \InvalidArgumentException('Expected $key to be string, got ' . Util::getType($key));

        $this->label = $label;
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}