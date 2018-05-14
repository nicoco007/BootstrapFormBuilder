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

class RadioButtonControl extends MultiOptionControl
{
    public function renderControl()
    {
        printf('<label>%s</label>', $this->getLabel());

        foreach ($this->getOptions() as $option) {
            print('<div class="custom-control custom-radio">');

            $input = new HtmlTag('input', true);

            $id = $this->getName() . '_' . $option->getKey();

            $input->addAttribute('type', 'radio');
            $input->addAttribute('id', $id);
            $input->addAttribute('name', $this->getName());
            $input->addAttribute('value', $option->getKey());
            $input->addAttribute('class', $this->getClasses());

            if (($this->getSubmittedKey() === null && $option->isDefault()) || $option->getKey() === $this->getSubmittedKey())
                $input->addAttribute('checked');

            $input->render();

            printf('<label class="custom-control-label" for="%s">%s</label>', $id, $option->getLabel());

            print('</div>');
        }
    }

    public function getType()
    {
        return 'radio';
    }

    private function getClasses()
    {
        $classes = ['custom-control-input'];

        if ($this->hasError())
            $classes[] = 'is-invalid';

        return implode(' ', $classes);
    }
}