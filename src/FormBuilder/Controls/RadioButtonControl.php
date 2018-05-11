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

class RadioButtonControl extends FormControl
{
    /**
     * @var RadioOption[]
     */
    private $options = [];

    public function render()
    {
        print('<div class="form-group">');

        printf('<label>%s</label>', $this->getLabel());

        foreach ($this->options as $option) {
            print('<div class="custom-control custom-radio">');

            $input = new HtmlTag('input', true);

            $value = $this->getName() . '_' . $option->getKey();

            $input->addAttribute('type', 'radio');
            $input->addAttribute('id', $value);
            $input->addAttribute('name', $this->getName());
            $input->addAttribute('value', $value);
            $input->addAttribute('class', $this->getClasses());

            if ($option->getKey() === $this->getSubmittedKey())
                $input->addAttribute('checked');

            $input->render();

            printf('<label class="custom-control-label" for="%s">%s</label>', $value, $option->getLabel());

            print('</div>');
        }

        if ($this->hasError())
            printf('<div class="invalid-feedback d-block">%s</div>', $this->getErrorMessage());

        if (!Util::stringIsNullOrEmpty($this->getHint()))
            printf('<small class="form-text text-muted">%s</small>', $this->getHint());

        print('</div>');
    }

    /**
     * @return null|string
     */
    private function getSubmittedKey()
    {
        if (!$this->getParent()->isSubmitted())
            return null;

        if (!isset($_POST[$this->getName()]) || Util::stringIsNullOrEmpty($_POST[$this->getName()]) || strlen($_POST[$this->getName()]) <= strlen($this->getName()))
            return null;

        $key = substr($_POST[$this->getName()], strlen($this->getName()) + 1);

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
     * @param string $label
     * @param string $key
     * @param mixed $value
     */
    public function addOption($label, $key, $value)
    {
        $this->options[$key] = new RadioOption($label, $key, $value);
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