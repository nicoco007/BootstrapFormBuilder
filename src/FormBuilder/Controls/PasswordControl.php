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

class PasswordControl extends InputControl
{
    /** @var bool */
    private $showPasswordStrength;

    /** @var int */
    private $minLength = 8;

    public function renderContents()
    {
        $input = new HtmlTag('input', true);
        $input->addAttribute('type', 'password');
        $input->addAttribute('class', $this->getClasses());
        $input->addAttribute('id', $this->getName());
        $input->addAttribute('name', $this->getName());
        $input->addAttribute('placeholder', $this->getPlaceholder());

        if ($this->showPasswordStrength === true)
            $input->addAttribute('data-show-strength', 'true');

        if ($this->minLength > 0)
            $input->addAttribute('minlength', strval($this->minLength));

        $input->render();
    }

    /**
     * @param bool $showPasswordStrength
     */
    public function setShowPasswordStrength($showPasswordStrength)
    {
        $this->showPasswordStrength = $showPasswordStrength;
    }

    /**
     * @param int $minLength
     */
    public function setMinLength($minLength)
    {
        $this->minLength = $minLength;
    }

    public function getType()
    {
        return 'password';
    }
}