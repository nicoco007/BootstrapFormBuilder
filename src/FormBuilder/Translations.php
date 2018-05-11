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
    private static $mo;
    private static $loaded = false;

    public static function initialize()
    {
        if (self::$mo === null)
            self::$mo = new MO();

        if (self::$loaded)
            return;

        $locale = Util::getLocale();
        $file = realpath('../../i18n/' . $locale . '.mo');

        if (!file_exists($file))
            return;

        self::$mo->import_from_file($file);
    }

    public static function translate($str, $context = null)
    {
        self::initialize();

        return self::$mo->translate($str, $context);
    }
}