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

        $input = new HtmlTag('input', true);

        $input->addAttribute('type', 'text');
        $input->addAttribute('class', $this->getClasses());
        $input->addAttribute('id', $this->getName());
        $input->addAttribute('name', $this->getName());
        $input->addAttribute('data-target', '#' . $this->getName() . '-wrapper');
        $input->addAttribute('placeholder', Translations::translate('MM/DD/YYYY', 'date control string format'));
        $input->addAttribute('value', $value);
        $input->addAttribute('locale', Util::getIETFLocale(LC_TIME)); // TODO: replace this with form language

        if ($this->minDate !== null)
            $input->addAttribute('data-min-date', $this->minDate->format('Y-m-d'));

        if ($this->maxDate !== null)
            $input->addAttribute('data-max-date', $this->maxDate->format('Y-m-d'));

        $input->render();

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
        if (parent::getErrorMessage())
            return parent::getErrorMessage();

        if (isset($_POST[$this->getName()]) && !Util::stringIsNullOrEmpty($_POST[$this->getName()])) {
            if (date_create($_POST[$this->getName()]) === false)
                return Translations::translate('Please enter a valid date.');

            /** @var \DateTime $date */
            $date = $this->getValue();

            if (($this->minDate !== null && $date < $this->minDate) || ($this->maxDate !== null && $date > $this->maxDate)) {
                $minDateStr = $this->minDate->format(Translations::translate('m/d/Y', 'date control DateTime format'));
                $maxDateStr = $this->maxDate->format(Translations::translate('m/d/Y', 'date control DateTime format'));
                return sprintf(Translations::translate('Date must be between %s and %s.'), $minDateStr, $maxDateStr);
            }
        }

        return null;
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
        $classes = ['form-control', 'datetimepicker-input', 'datetimepicker-date'];

        if ($this->hasError())
            $classes[] = 'is-invalid';

        return implode(' ', $classes);
    }

    /**
     * @param \DateTime $minDate
     */
    public function setMinDate($minDate)
    {
        if (!($minDate instanceof \DateTime))
            throw new \InvalidArgumentException('Expected $minDate to be instance of DateTime, got ' . Util::getType($minDate));

        $this->minDate = $minDate;
    }

    /**
     * @param \DateTime $maxDate
     */
    public function setMaxDate($maxDate)
    {
        if (!($maxDate instanceof \DateTime))
            throw new \InvalidArgumentException('Expected $maxDate to be instance of DateTime, got ' . Util::getType($maxDate));

        $this->maxDate = $maxDate;
    }
}