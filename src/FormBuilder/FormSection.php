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


class FormSection
{
    /** @var Form */
    private $parent;

    /** @var string */
    private $label;

    /** @var Controls\FormControl[] */
    private $controls;

    /**
     * FormSection constructor.
     * @param string $label
     */
    public function __construct($label)
    {
        $this->label = $label;
    }

    public function render()
    {
        printf('<h3>%s</h3>', $this->label);

        print('<fieldset>');

        foreach ($this->controls as $control) {
            $control->render();
        }

        print('</fieldset>');
    }

    public function init()
    {
        foreach ($this->controls as $control)
            $control->init();
    }

    /**
     * @param Controls\FormControl $control
     */
    public function addControl($control)
    {
        if (!($control instanceof Controls\FormControl))
            throw new \InvalidArgumentException('Expected $control to be instance of FormControl, got ' . Util::getType($control));

        $this->controls[$control->getName()] = $control;
    }

    public function getControls()
    {
        return $this->controls;
    }

    /**
     * @param Form $parent
     */
    public function setParent($parent)
    {
        if (!($parent instanceof Form))
            throw new \InvalidArgumentException('Expected $control to be instance of Form, got ' . Util::getType($parent));

        foreach ($this->controls as $control)
            $control->setParent($parent);

        $this->parent = $parent;
    }

    /**
     * @return bool
     */
    public function isSubmitted()
    {
        if ($this->parent !== null)
            return $this->parent->isSubmitted();

        return false;
    }
}