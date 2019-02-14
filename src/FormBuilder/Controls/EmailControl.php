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


class EmailControl extends InputControl
{
    public function renderContents()
    {
        printf('<input type="email" class="%1$s" id="%2$s" name="%2$s" placeholder="%3$s" value="%4$s">', $this->getClasses(), $this->getName(), $this->getPlaceholder(), $this->getValue());
    }

    public function getErrorMessage()
    {
        if (parent::getErrorMessage() !== null)
            return parent::getErrorMessage();

        if (!filter_var($this->getValue(), FILTER_VALIDATE_EMAIL))
            return $this->translate("Please enter a valid email address.");

        return null;
    }

    public function getType()
    {
        return 'email';
    }
}