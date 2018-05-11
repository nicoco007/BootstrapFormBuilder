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
use FormBuilder\Util;

class CheckboxControl extends FormControl
{
    public function render()
    {
        print('<div class="form-group"><div class="custom-control custom-checkbox">');

        $input_tag = new HtmlTag('input', true);

        $input_tag->addAttribute('type', 'checkbox');
        $input_tag->addAttribute('class', $this->getClasses());
        $input_tag->addAttribute('id', $this->getName());
        $input_tag->addAttribute('name', $this->getName());

        if ($this->getValue() === true)
            $input_tag->addAttribute('checked');

        $input_tag->render();

        printf('<label class="custom-control-label" for="%s">%s</label>', $this->getName(), $this->getLabel());

        if ($this->hasError())
            printf('<div class="invalid-feedback d-block">%s</div>', $this->getErrorMessage());

        if (!Util::stringIsNullOrEmpty($this->getHint()))
            printf('<small class="form-text text-muted">%s</small>', $this->getHint());

        print('</div></div>');
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
        if (isset($_POST[$this->getName()]) && $_POST[$this->getName()] == 'on')
            return true;

        return false;
    }
}