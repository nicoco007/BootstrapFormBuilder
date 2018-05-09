<?php
/**
 * Created by PhpStorm.
 * User: nicolasgnyra
 * Date: 18-05-08
 * Time: 22:20
 */

namespace FormBuilder;


use POMO\MO;

class Translations
{
    /** @var MO */
    private static $mo;
    private static $loaded = false;

    public static function initialize() {
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

    public static function translate($str, $context = null) {
        self::initialize();

        return self::$mo->translate($str, $context);
    }
}