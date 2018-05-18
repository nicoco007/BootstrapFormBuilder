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
use FormBuilder\HtmlTag;
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
    private $rawValue;

    /** @var FormControl */
    private $parent;

    /** @var bool */
    private $required;

    /** @var string */
    private $hint;

    /** @var FormControl[] */
    private $children = [];

    /** @var mixed */
    private $requiredParentValue;

    /** @var Form */
    private $parentForm;

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

    public function render()
    {
        print('<div class="control-group">');

        $tag = new HtmlTag('div', true);
        $tag->addAttribute('class', 'form-group');

        if ($this->requiredParentValue) {
            $tag->addAttribute('data-parent', $this->parent->getName());
            $tag->addAttribute('data-parent-value', $this->parent->getValueKey($this->getRequiredParentValue()));
        }

        $tag->render();

        if ($this->requiredParentValue) {
            print('<div class="child-message">');

            if ($this->parent instanceof CheckboxControl && $this->requiredParentValue == true)
                print(Translations::translate('If you checked the box above:'));
            elseif ($this->parent instanceof CheckboxControl && $this->requiredParentValue == false)
                print(Translations::translate('If you did not check the box above:'));
            elseif ($this->parent instanceof RadioButtonControl)
                printf(Translations::translate('If you selected "%s" above:'), $this->parent->getValueLabel($this->getRequiredParentValue()));
            else
                printf(Translations::translate('If you entered "%s" above:'), $this->parent->getValueLabel($this->getRequiredParentValue()));

            print('</div>');
        }

        $this->renderControl();

        if ($this->hasError())
            printf('<div class="invalid-feedback d-block">%s</div>', $this->getErrorMessage());

        if (!Util::stringIsNullOrEmpty($this->getHint()))
            printf('<small class="form-text text-muted">%s</small>', $this->getHint());

        print('</div>');

        print('<div class="children">');

        foreach ($this->children as $child)
            $child->render();

        print('</div>');
        print('</div>');
    }

    public abstract function renderControl();

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
        return $this->parentForm->isSubmitted() && ($this->parent === null || $this->requiredParentValue === null || $this->parent->getValue() === $this->requiredParentValue) && $this->getErrorMessage() !== null;
    }

    public function getErrorMessage()
    {
        if ($this->isRequired() && ($this->getValue() === null || $this->getValue() === false))
            return Translations::translate('This field is required.');

        return null;
    }

    /**
     * @param FormControl $parent
     */
    public final function setParent($parent)
    {
        if (!($parent instanceof FormControl))
            throw new \InvalidArgumentException('Expected $parent to be instance of FormControl, got ' . Util::getType($parent));

        $this->parent = $parent;
    }

    public final function init()
    {
        if ($this->parentForm->isSubmitted()) {
            if (isset($_POST[$this->getName()]))
                $this->rawValue = $_POST[$this->getName()];

            $this->value = $this->parseValueFromPost();
        }

        foreach ($this->children as $child)
            $child->init();
    }

    /**
     * @return FormControl
     */
    public final function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Form $parentForm
     */
    public function setParentForm($parentForm)
    {
        if (!($parentForm instanceof Form))
            throw new \InvalidArgumentException('Expected $parentForm to be instance of Form, got ' . Util::getType($parentForm));

        foreach ($this->children as $child)
            $child->setParentForm($parentForm);

        $this->parentForm = $parentForm;
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
     * @param FormControl $child
     * @param mixed $requiredValue
     */
    public function addChild($child, $requiredValue = null)
    {
        if (!($child instanceof FormControl))
            throw new \InvalidArgumentException('Expected $child to be instance of FormControl, got ' . Util::getType($child));

        $child->setParent($this);
        $child->setRequiredParentValue($requiredValue);

        if (count($intersect = array_intersect_key($this->getChildren(true), $child->getChildren(true))) > 0)
            throw new \RuntimeException('Control with name ' . array_keys($intersect)[0] . ' was already added.');

        $this->children[$child->getName()] = $child;
    }

    /**
     * @param bool $deep
     * @return FormControl[]
     */
    public function getChildren($deep = false)
    {
        $controls = $this->children;

        if ($deep)
            foreach ($this->children as $child)
                $controls += $child->getChildren();

        return $controls;
    }

    /**
     * @param mixed $requiredParentValue
     */
    protected function setRequiredParentValue($requiredParentValue)
    {
        $this->requiredParentValue = $requiredParentValue;
    }

    /**
     * @param $value
     * @return string
     */
    protected function getValueKey($value) {
        return strval($value);
    }

    /**
     * @param $value
     * @return string
     */
    protected function getValueLabel($value) {
        return strval($value);
    }

    /**
     * @return string
     */
    public function getRequiredParentValue()
    {
        return $this->requiredParentValue;
    }

    /**
     * @return string
     */
    protected final function getRawValue()
    {
        return $this->rawValue;
    }
}