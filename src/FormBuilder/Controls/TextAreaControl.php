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

class TextAreaControl extends FormControl
{
    private $placeholder;

    function render()
    {
        print('<div class="form-group">');

        printf('<label for="%s">%s</label>', $this->getName(), $this->getLabel());

        printf('<textarea class="%1$s" id="%2$s" name="%2$s" placeholder="%3$s">%4$s</textarea>', $this->getClasses(), $this->getName(), $this->getPlaceholder(), $this->getValue());

        if ($this->hasError())
            printf('<div class="invalid-feedback d-block">%s</div>', $this->getErrorMessage());

        if (!Util::stringIsNullOrEmpty($this->getHint()))
            printf('<small class="form-text text-muted">%s</small>', $this->getHint());

        print('</div>');
    }

    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
    }

    public function getType()
    {
        return 'textarea';
    }

    private function getClasses()
    {
        $classes = ['form-control'];

        if ($this->hasError())
            $classes[] = 'is-invalid';

        return implode(' ', $classes);
    }
}