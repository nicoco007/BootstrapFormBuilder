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


use FormBuilder\HtmlTag;

class CheckboxControl extends FormControl
{
    public function __construct($label, $name)
    {
        if ($label === null)
            throw new \InvalidArgumentException('$label cannot be null for RadioButtonControl');

        parent::__construct($label, $name);
    }

    public function renderControl()
    {
        print('<div class="custom-control custom-checkbox">');

        $input_tag = new HtmlTag('input', true);

        $input_tag->addAttribute('type', 'checkbox');
        $input_tag->addAttribute('class', $this->getClasses());
        $input_tag->addAttribute('id', $this->getName());
        $input_tag->addAttribute('name', $this->getName());

        if ($this->getValue() === true)
            $input_tag->addAttribute('checked');

        $input_tag->render();

        printf('<label class="custom-control-label" for="%s"><span class="label-text">%s</span></label>', $this->getName(), $this->getLabel());

        print('</div>');
    }

    protected function getValueKey($value)
    {
        return $value === true ? 'true' : 'false';
    }

    public function getType()
    {
        return 'checkbox';
    }

    private function getClasses()
    {
        $classes = ['custom-control-input'];

        if ($this->hasError())
            $classes[] = 'is-invalid';

        return implode(' ', $classes);
    }

    public function parseValueFromPost()
    {
        if (isset($_POST[$this->getName()]) && $_POST[$this->getName()] === 'on')
            return true;

        return false;
    }
}