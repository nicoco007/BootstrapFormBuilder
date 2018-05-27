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

class SelectControl extends MultiOptionControl
{
    public function renderControl()
    {
        if (!Util::stringIsNullOrEmpty($this->getLabel()))
            printf('<label>%s</label>', $this->getLabel());

        printf('<select class="%s" name="%s">', $this->getClasses(), $this->getName());

        if (!$this->hasDefault())
            printf('<option value="">%s</option>', $this->translate('Select an option&hellip;'));

        foreach ($this->getOptions() as $option) {
            $tag = new HtmlTag('option');
            $tag->addAttribute('value', $option->getKey());
            $tag->setInnerText($option->getLabel());

            if (($this->getSubmittedKey() === null && $option->isDefault()) || $option->getKey() === $this->getSubmittedKey())
                $tag->addAttribute('selected');

            $tag->render();
        }

        print('</select>');
    }

    public function getType()
    {
        return 'select';
    }

    private function getClasses()
    {
        $classes = ['form-control'];

        if ($this->hasError())
            $classes[] = 'is-invalid';

        return implode(' ', $classes);
    }
}