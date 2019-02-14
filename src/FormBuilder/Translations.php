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

namespace FormBuilder;


use POMO\MO;

class Translations
{
    /** @var MO */
    private $mo;

    public function __construct($locale)
    {
        $this->mo = new MO();

        while (!file_exists($file = __DIR__ . '/../../i18n/' . $locale . '.mo') && strlen($locale) > 2)
            $locale = substr($locale, 0, strlen($locale) - 1);

        if (!file_exists($file))
            return;

        $this->mo->import_from_file($file);
    }

    public function translate($str, $context = null)
    {
        return $this->mo->translate($str, $context);
    }
}