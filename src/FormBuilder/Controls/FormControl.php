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


use FormBuilder\Form;
use FormBuilder\Translations;
use FormBuilder\Util;

abstract class FormControl
{
    /** @var string */
    private $name;

    /** @var string */
    private $label;

    /** @var mixed */
    private $value;

    /** @var string */
    private $raw_value;

    /** @var Form */
    private $parent;

    /** @var bool */
    private $required;

    /** @var string */
    private $hint;

    /**
     * FormControl constructor.
     * @param string $label
     * @param string $name
     */
    public function __construct($label, $name)
    {
        if (!is_string($label))
            throw new \InvalidArgumentException('Expected $label to be string, got ' . Util::getType($label));

        if (!is_string($name))
            throw new \InvalidArgumentException('Expected $name to be string, got ' . Util::getType($name));

        $this->label = trim($label);
        $this->name = trim($name);
    }

    public abstract function render();

    public abstract function getType();

    /**
     * @return mixed
     */
    protected function parseValueFromPost()
    {
        if (isset($_POST[$this->getName()]) && !Util::stringIsNullOrEmpty($_POST[$this->getName()]))
            return trim($_POST[$this->getName()]);

        return null;
    }

    /**
     * @return mixed
     */
    public final function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @param bool $override
     */
    public final function setValue($value, $override = false)
    {
        if (!is_bool($override))
            throw new \InvalidArgumentException('Expected $override to be string, got ' . Util::getType($override));

        if ($this->getValue() == null || $override)
            $this->value = $value;
    }

    /**
     * @return string
     */
    public final function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public final function getLabel()
    {
        return $this->label;
    }

    /**
     * @param bool $required
     */
    public final function setRequired($required)
    {
        if (!is_bool($required))
            throw new \InvalidArgumentException('Expected $required to be bool, got ' . Util::getType($required));

        $this->required = $required;
    }

    /**
     * @return bool
     */
    public final function isRequired()
    {
        return $this->required;
    }

    public final function hasError()
    {
        return $this->getParent()->isSubmitted() && $this->getErrorMessage() !== null;
    }

    public function getErrorMessage()
    {
        if ($this->parent->isSubmitted() && $this->isRequired() && ($this->getValue() === null || $this->getValue() === false))
            return Translations::translate('This field is required.');

        return null;
    }

    /**
     * @param Form $parent
     */
    public final function setParent($parent)
    {
        if (!($parent instanceof Form))
            throw new \InvalidArgumentException('Expected $parent to be instance of Form, got ' . Util::getType($parent));

        $this->parent = $parent;
    }

    public final function init() {
        if ($this->parent->isSubmitted()) {
            if (isset($_POST[$this->getName()]))
                $this->raw_value = $_POST[$this->getName()];

            $this->value = $this->parseValueFromPost();
        }
    }

    /**
     * @return Form
     */
    public final function getParent()
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getHint()
    {
        return $this->hint;
    }

    /**
     * @param string $hint
     */
    public function setHint($hint)
    {
        if (!is_string($hint))
            throw new \InvalidArgumentException('Expected $hint to be string, got ' . Util::getType($hint));

        $this->hint = trim($hint);
    }

    /**
     * @return string
     */
    protected final function getRawValue()
    {
        return $this->raw_value;
    }
}