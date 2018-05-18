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
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberControl extends InputControl
{
    private $initialCountry;
    private $preferredCountries;

    function renderContents()
    {
        print('<div class="input-group">'); // to avoid issues with intl-tel-input

        $input = new HtmlTag('input', true);

        $input->addAttribute('class', $this->getClasses());
        $input->addAttribute('type', 'tel');
        $input->addAttribute('id', $this->getName());
        $input->addAttribute('name', $this->getName());
        $input->addAttribute('value', $this->getValue());

        if ($this->initialCountry !== null)
            $input->addAttribute('data-initial-country', $this->initialCountry);

        if (count($this->preferredCountries) > 0)
            $input->addAttribute('data-pref-countries', implode(',', $this->preferredCountries));

        $input->render();

        print('</div>');
    }

    public function getType()
    {
        return 'tel';
    }

    public function getErrorMessage()
    {
        if (parent::getErrorMessage())
            return parent::getErrorMessage();

        if ($this->getValue() !== null) {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $value = $this->getValue();

            try {
                $proto = $phoneUtil->parse($value, 'CA');

                if (!$phoneUtil->isValidNumber($proto))
                    return Translations::translate('Please enter a valid phone number.');
            } catch (NumberParseException $ex) {
                return Translations::translate('Please enter a valid phone number.');
            }
        }

        return null;
    }

    /**
     * @param mixed $initialCountry
     */
    public function setInitialCountry($initialCountry)
    {
        if (!is_string($initialCountry))
            throw new \InvalidArgumentException('Expected $initialCountry to be an array, got ' . Util::getType($initialCountry));

        $this->initialCountry = $initialCountry;
    }

    /**
     * @param mixed $preferredCountries
     */
    public function setPreferredCountries($preferredCountries)
    {
        if (!is_array($preferredCountries))
            throw new \InvalidArgumentException('Expected $preferredCountries to be an array, got ' . Util::getType($preferredCountries));

        $this->preferredCountries = $preferredCountries;
    }
}