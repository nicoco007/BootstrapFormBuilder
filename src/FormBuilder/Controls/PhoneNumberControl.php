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
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberControl extends InputControl
{
    function renderContents()
    {
        print('<div class="input-group">'); // to avoid issues with intl-tel-input

        printf('<input class="form-control" type="tel" name="%s" value="%s">', $this->getName(), $this->getValue());

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

        $phoneUtil = PhoneNumberUtil::getInstance();
        $value = $this->getValue();

        try {
            $proto = $phoneUtil->parse($value, 'CA');
            if (!$phoneUtil->isValidNumber($proto))
                return Translations::translate('Please enter a valid phone number.');
        } catch (NumberParseException $ex) {
            return Translations::translate('Could not parse phone number. Please try again.');
        }

        return null;
    }
}