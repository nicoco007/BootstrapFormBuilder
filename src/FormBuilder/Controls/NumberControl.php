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

class NumberControl extends InputControl
{
    private $icon;
    private $step;
    private $min;
    private $max;

    public function renderContents()
    {
        print('<div class="input-group">');

        if ($this->icon !== null)
            printf('<span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-%s"></i></span></span>', $this->icon);

        $input = new HtmlTag('input', true);

        $input->addAttribute('type', 'number');
        $input->addAttribute('class', $this->getClasses());
        $input->addAttribute('id', $this->getName());
        $input->addAttribute('name', $this->getName());
        $input->addAttribute('placeholder', $this->getPlaceholder());
        $input->addAttribute('value', strval($this->getValue()));

        if ($this->step !== null)
            $input->addAttribute('step', strval($this->step));

        if ($this->min !== null)
            $input->addAttribute('min', strval($this->min));

        if ($this->max !== null)
            $input->addAttribute('max', strval($this->max));

        $input->render();

        print('</div>');
    }

    public function getType()
    {
        return 'number';
    }

    protected function parseValueFromPost()
    {
        if (isset($_POST[$this->getName()]) && !Util::stringIsNullOrEmpty($_POST[$this->getName()]))
            return doubleval($_POST[$this->getName()]);

        return null;
    }

    public function getErrorMessage()
    {
        $parent = parent::getErrorMessage();

        if ($parent !== null)
            return $parent;

        if ($this->getValue() !== null && $this->getValue() < $this->min)
            return sprintf($this->translate('Please enter a number that is no less than %d.'), $this->min);

        if ($this->getValue() !== null && $this->getValue() > $this->max)
            return sprintf($this->translate('Please enter a number that is no more than %d.'), $this->max);

        return null;
    }

    public function setIcon(string $icon) {
        $this->icon = $icon;
    }

    public function setStep(float $step) {
        $this->step = $step;
    }

    public function setMin(float $min): void
    {
        $this->min = $min;
    }

    public function setMax(float $max): void
    {
        $this->max = $max;
    }
}