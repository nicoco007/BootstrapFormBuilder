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

class TextAreaControl extends FormControl
{
    /** @var string */
    private $placeholder;

    /** @var int */
    private $maxLength;

    function renderControl()
    {
        printf('<label for="%s">%s</label>', $this->getName(), $this->getLabel());

        $textarea = new HtmlTag('textarea');
        $textarea->addAttribute('class', $this->getClasses());
        $textarea->addAttribute('id', $this->getName());
        $textarea->addAttribute('name', $this->getName());
        $textarea->addAttribute('placeholder', $this->getPlaceholder());
        $textarea->setInnerText($this->getValue());

        $textarea->render();
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

    /**
     * @param int $maxLength
     */
    public function setMaxLength($maxLength)
    {
        if (!is_int($maxLength))
            throw new \InvalidArgumentException('Expected $maxLength to be integer, got ' . Util::getType($maxLength));

        $this->maxLength = $maxLength;
    }

    private function getClasses()
    {
        $classes = ['form-control'];

        if ($this->hasError())
            $classes[] = 'is-invalid';

        return implode(' ', $classes);
    }
}