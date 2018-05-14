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

abstract class MultiOptionControl extends FormControl
{
    /**
     * @var ControlOption[]
     */
    private $options = [];

    /**
     * @var ControlOption[]
     */
    private $hashes = [];

    /** @var bool */
    private $has_default;

    /**
     * @param string $label
     * @param string $key
     * @param mixed $value
     * @param bool $is_default
     */
    public function addOption($label, $key, $value = null, $is_default = false)
    {
        if (!is_string($label))
            throw new \InvalidArgumentException('Expected $label to be string, got ' . Util::getType($label));

        if (!is_string($key))
            throw new \InvalidArgumentException('Expected $key to be string, got ' . Util::getType($key));

        if (!is_bool($is_default))
            throw new \InvalidArgumentException('Expected $is_default to be boolean, got ' . Util::getType($is_default));

        if ($is_default) {
            if ($this->has_default)
                throw new \RuntimeException('RadioButtonControl already has a default value');
            else
                $this->has_default = true;
        }

        $co = new ControlOption($label, $key, $value, $is_default);
        $this->options[$key] = $co;
        $this->hashes[sha1(serialize($value))] = $co;
    }

    /**
     * @return null|string
     */
    protected function getSubmittedKey()
    {
        if (!isset($_POST[$this->getName()]) || Util::stringIsNullOrEmpty($_POST[$this->getName()]))
            return null;

        $key = $_POST[$this->getName()];

        if (!isset($this->options[$key]))
            return null;

        return $key;
    }

    /**
     * @return null|string
     */
    protected function parseValueFromPost()
    {
        $key = $this->getSubmittedKey();

        if ($key === null)
            return null;

        return $this->options[$key]->getValue();
    }

    /**
     * @return ControlOption[]
     */
    protected function getOptions()
    {
        return $this->options;
    }

    /**
     * @return bool
     */
    protected function hasDefault()
    {
        return $this->has_default;
    }

    protected function getValueKey($value)
    {
        return $this->hashes[sha1(serialize($value))]->getKey();
    }

    protected function getValueLabel($value)
    {
        return $this->hashes[sha1(serialize($value))]->getLabel();
    }
}