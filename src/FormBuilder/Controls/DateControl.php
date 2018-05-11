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


use FormBuilder\Translations;
use FormBuilder\Util;

class DateControl extends FormControl
{
    /** @var string */
    private $placeholder;

    /** @var \DateTime */
    private $minDate;

    /** @var \DateTime */
    private $maxDate;

    public function render()
    {
        $value = $this->getValue() !== null ? $this->getValue()->format(Translations::translate('m/d/Y', 'date control DateTime format')) : $this->getRawValue();

        print('<div class="form-group">');

        printf('<label for="%s">%s</label>', $this->getName(), $this->getLabel());

        printf('<div class="input-group datetimepicker-date" id="%s-wrapper" data-target-input="nearest" data-locale="%s">', $this->getName(), Util::getIETFLocale(LC_TIME));

        printf(
            '<input type="text" class="%1$s" id="%2$s" name="%2$s" data-target="#%1$s-wrapper" placeholder="%3$s" value="%4$s">',
            $this->getClasses(), $this->getName(), Translations::translate('MM/DD/YYYY', 'date control string format'), $value
        );

        print('</div>');

        if ($this->hasError())
            printf('<div class="invalid-feedback d-block">%s</div>', $this->getErrorMessage());

        if (!Util::stringIsNullOrEmpty($this->getHint()))
            printf('<small class="form-text text-muted">%s</small>', $this->getHint());

        print('</div>');
    }

    public function getType()
    {
        return 'date';
    }

    protected function parseValueFromPost()
    {
        if (isset($_POST[$this->getName()]) && !Util::stringIsNullOrEmpty($_POST[$this->getName()]) && date_create($_POST[$this->getName()]) !== false)
            return new \DateTime($_POST[$this->getName()]);

        return null;
    }

    public function getErrorMessage()
    {
        if (isset($_POST[$this->getName()]) && !Util::stringIsNullOrEmpty($_POST[$this->getName()]) && date_create($_POST[$this->getName()]) === false)
            return Translations::translate('Please enter a valid date.');

        return parent::getErrorMessage();
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     */
    public function setPlaceholder($placeholder)
    {
        if (!is_string($placeholder))
            throw new \InvalidArgumentException('Expected $placeholder to be string, got ' . Util::getType($placeholder));

        $this->placeholder = $placeholder;
    }

    private function getClasses()
    {
        $classes = ['form-control', 'datetimepicker-input', 'rounded-right'];

        if ($this->hasError())
            $classes[] = 'is-invalid';

        return implode(' ', $classes);
    }

    /**
     * @param \DateTime $minDate
     */
    public function setMinDate($minDate)
    {
        $this->minDate = $minDate;
    }

    /**
     * @param \DateTime $maxDate
     */
    public function setMaxDate($maxDate)
    {
        $this->maxDate = $maxDate;
    }
}