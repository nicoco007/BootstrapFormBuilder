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
use FormBuilder\Translations;
use FormBuilder\Util;

class TextControl extends InputControl
{
    private $regexString;
    private $maxLength;

    public function renderContents()
    {
        $input = new HtmlTag('input', true);

        $input->addAttribute('type', 'text');
        $input->addAttribute('class', $this->getClasses());
        $input->addAttribute('id', $this->getName());
        $input->addAttribute('name', $this->getName());
        $input->addAttribute('placeholder', $this->getPlaceholder());
        $input->addAttribute('value', $this->getValue());

        if ($this->maxLength !== null)
            $input->addAttribute('maxlength', strval($this->maxLength));

        $input->render();
    }

    public function getType()
    {
        return 'text';
    }

    public function getErrorMessage()
    {
        if (parent::getErrorMessage())
            return parent::getErrorMessage();

        if ($this->getValue() !== null) {
            if ($this->regexString !== null && preg_match($this->regexString, $this->getValue()) !== 1)
                return $this->translate('Please enter a valid value.');

            if ($this->maxLength !== null && mb_strlen($this->getValue()) > $this->maxLength)
                return sprintf($this->translate('Exceeded maximum length of %d characters.'), $this->maxLength);
        }

        return null;
    }

    /**
     * @param mixed $regexString
     */
    public function setRegexString($regexString)
    {
        if (!is_string($regexString))
            throw new \InvalidArgumentException('Expected $regexString to be string, got ' . Util::getType($regexString));

        $this->regexString = $regexString;
    }

    /**
     * @param mixed $maxLength
     */
    public function setMaxLength($maxLength)
    {
        if (!is_int($maxLength))
            throw new \InvalidArgumentException('Expected $maxLength to be integer, got ' . Util::getType($maxLength));

        $this->maxLength = $maxLength;
    }
}