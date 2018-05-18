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


class Util
{
    private $DEFAULT_DIACRITICS_MAP = array(
        array('base' => 'A', 'letters' => '\x{0041}\x{24B6}\x{FF21}\x{00C0}\x{00C1}\x{00C2}\x{1EA6}\x{1EA4}\x{1EAA}\x{1EA8}\x{00C3}\x{0100}\x{0102}\x{1EB0}\x{1EAE}\x{1EB4}\x{1EB2}\x{0226}\x{01E0}\x{00C4}\x{01DE}\x{1EA2}\x{00C5}\x{01FA}\x{01CD}\x{0200}\x{0202}\x{1EA0}\x{1EAC}\x{1EB6}\x{1E00}\x{0104}\x{023A}\x{2C6F}'),
        array('base' => 'AA', 'letters' => '\x{A732}'),
        array('base' => 'AE', 'letters' => '\x{00C6}\x{01FC}\x{01E2}'),
        array('base' => 'AO', 'letters' => '\x{A734}'),
        array('base' => 'AU', 'letters' => '\x{A736}'),
        array('base' => 'AV', 'letters' => '\x{A738}\x{A73A}'),
        array('base' => 'AY', 'letters' => '\x{A73C}'),
        array('base' => 'B', 'letters' => '\x{0042}\x{24B7}\x{FF22}\x{1E02}\x{1E04}\x{1E06}\x{0243}\x{0182}\x{0181}'),
        array('base' => 'C', 'letters' => '\x{0043}\x{24B8}\x{FF23}\x{0106}\x{0108}\x{010A}\x{010C}\x{00C7}\x{1E08}\x{0187}\x{023B}\x{A73E}'),
        array('base' => 'D', 'letters' => '\x{0044}\x{24B9}\x{FF24}\x{1E0A}\x{010E}\x{1E0C}\x{1E10}\x{1E12}\x{1E0E}\x{0110}\x{018B}\x{018A}\x{0189}\x{A779}'),
        array('base' => 'DZ', 'letters' => '\x{01F1}\x{01C4}'),
        array('base' => 'Dz', 'letters' => '\x{01F2}\x{01C5}'),
        array('base' => 'E', 'letters' => '\x{0045}\x{24BA}\x{FF25}\x{00C8}\x{00C9}\x{00CA}\x{1EC0}\x{1EBE}\x{1EC4}\x{1EC2}\x{1EBC}\x{0112}\x{1E14}\x{1E16}\x{0114}\x{0116}\x{00CB}\x{1EBA}\x{011A}\x{0204}\x{0206}\x{1EB8}\x{1EC6}\x{0228}\x{1E1C}\x{0118}\x{1E18}\x{1E1A}\x{0190}\x{018E}'),
        array('base' => 'F', 'letters' => '\x{0046}\x{24BB}\x{FF26}\x{1E1E}\x{0191}\x{A77B}'),
        array('base' => 'G', 'letters' => '\x{0047}\x{24BC}\x{FF27}\x{01F4}\x{011C}\x{1E20}\x{011E}\x{0120}\x{01E6}\x{0122}\x{01E4}\x{0193}\x{A7A0}\x{A77D}\x{A77E}'),
        array('base' => 'H', 'letters' => '\x{0048}\x{24BD}\x{FF28}\x{0124}\x{1E22}\x{1E26}\x{021E}\x{1E24}\x{1E28}\x{1E2A}\x{0126}\x{2C67}\x{2C75}\x{A78D}'),
        array('base' => 'I', 'letters' => '\x{0049}\x{24BE}\x{FF29}\x{00CC}\x{00CD}\x{00CE}\x{0128}\x{012A}\x{012C}\x{0130}\x{00CF}\x{1E2E}\x{1EC8}\x{01CF}\x{0208}\x{020A}\x{1ECA}\x{012E}\x{1E2C}\x{0197}'),
        array('base' => 'J', 'letters' => '\x{004A}\x{24BF}\x{FF2A}\x{0134}\x{0248}'),
        array('base' => 'K', 'letters' => '\x{004B}\x{24C0}\x{FF2B}\x{1E30}\x{01E8}\x{1E32}\x{0136}\x{1E34}\x{0198}\x{2C69}\x{A740}\x{A742}\x{A744}\x{A7A2}'),
        array('base' => 'L', 'letters' => '\x{004C}\x{24C1}\x{FF2C}\x{013F}\x{0139}\x{013D}\x{1E36}\x{1E38}\x{013B}\x{1E3C}\x{1E3A}\x{0141}\x{023D}\x{2C62}\x{2C60}\x{A748}\x{A746}\x{A780}'),
        array('base' => 'LJ', 'letters' => '\x{01C7}'),
        array('base' => 'Lj', 'letters' => '\x{01C8}'),
        array('base' => 'M', 'letters' => '\x{004D}\x{24C2}\x{FF2D}\x{1E3E}\x{1E40}\x{1E42}\x{2C6E}\x{019C}'),
        array('base' => 'N', 'letters' => '\x{004E}\x{24C3}\x{FF2E}\x{01F8}\x{0143}\x{00D1}\x{1E44}\x{0147}\x{1E46}\x{0145}\x{1E4A}\x{1E48}\x{0220}\x{019D}\x{A790}\x{A7A4}'),
        array('base' => 'NJ', 'letters' => '\x{01CA}'),
        array('base' => 'Nj', 'letters' => '\x{01CB}'),
        array('base' => 'O', 'letters' => '\x{004F}\x{24C4}\x{FF2F}\x{00D2}\x{00D3}\x{00D4}\x{1ED2}\x{1ED0}\x{1ED6}\x{1ED4}\x{00D5}\x{1E4C}\x{022C}\x{1E4E}\x{014C}\x{1E50}\x{1E52}\x{014E}\x{022E}\x{0230}\x{00D6}\x{022A}\x{1ECE}\x{0150}\x{01D1}\x{020C}\x{020E}\x{01A0}\x{1EDC}\x{1EDA}\x{1EE0}\x{1EDE}\x{1EE2}\x{1ECC}\x{1ED8}\x{01EA}\x{01EC}\x{00D8}\x{01FE}\x{0186}\x{019F}\x{A74A}\x{A74C}'),
        array('base' => 'OI', 'letters' => '\x{01A2}'),
        array('base' => 'OO', 'letters' => '\x{A74E}'),
        array('base' => 'OU', 'letters' => '\x{0222}'),
        array('base' => 'OE', 'letters' => '\x{008C}\x{0152}'),
        array('base' => 'oe', 'letters' => '\x{009C}\x{0153}'),
        array('base' => 'P', 'letters' => '\x{0050}\x{24C5}\x{FF30}\x{1E54}\x{1E56}\x{01A4}\x{2C63}\x{A750}\x{A752}\x{A754}'),
        array('base' => 'Q', 'letters' => '\x{0051}\x{24C6}\x{FF31}\x{A756}\x{A758}\x{024A}'),
        array('base' => 'R', 'letters' => '\x{0052}\x{24C7}\x{FF32}\x{0154}\x{1E58}\x{0158}\x{0210}\x{0212}\x{1E5A}\x{1E5C}\x{0156}\x{1E5E}\x{024C}\x{2C64}\x{A75A}\x{A7A6}\x{A782}'),
        array('base' => 'S', 'letters' => '\x{0053}\x{24C8}\x{FF33}\x{1E9E}\x{015A}\x{1E64}\x{015C}\x{1E60}\x{0160}\x{1E66}\x{1E62}\x{1E68}\x{0218}\x{015E}\x{2C7E}\x{A7A8}\x{A784}'),
        array('base' => 'T', 'letters' => '\x{0054}\x{24C9}\x{FF34}\x{1E6A}\x{0164}\x{1E6C}\x{021A}\x{0162}\x{1E70}\x{1E6E}\x{0166}\x{01AC}\x{01AE}\x{023E}\x{A786}'),
        array('base' => 'TZ', 'letters' => '\x{A728}'),
        array('base' => 'U', 'letters' => '\x{0055}\x{24CA}\x{FF35}\x{00D9}\x{00DA}\x{00DB}\x{0168}\x{1E78}\x{016A}\x{1E7A}\x{016C}\x{00DC}\x{01DB}\x{01D7}\x{01D5}\x{01D9}\x{1EE6}\x{016E}\x{0170}\x{01D3}\x{0214}\x{0216}\x{01AF}\x{1EEA}\x{1EE8}\x{1EEE}\x{1EEC}\x{1EF0}\x{1EE4}\x{1E72}\x{0172}\x{1E76}\x{1E74}\x{0244}'),
        array('base' => 'V', 'letters' => '\x{0056}\x{24CB}\x{FF36}\x{1E7C}\x{1E7E}\x{01B2}\x{A75E}\x{0245}'),
        array('base' => 'VY', 'letters' => '\x{A760}'),
        array('base' => 'W', 'letters' => '\x{0057}\x{24CC}\x{FF37}\x{1E80}\x{1E82}\x{0174}\x{1E86}\x{1E84}\x{1E88}\x{2C72}'),
        array('base' => 'X', 'letters' => '\x{0058}\x{24CD}\x{FF38}\x{1E8A}\x{1E8C}'),
        array('base' => 'Y', 'letters' => '\x{0059}\x{24CE}\x{FF39}\x{1EF2}\x{00DD}\x{0176}\x{1EF8}\x{0232}\x{1E8E}\x{0178}\x{1EF6}\x{1EF4}\x{01B3}\x{024E}\x{1EFE}'),
        array('base' => 'Z', 'letters' => '\x{005A}\x{24CF}\x{FF3A}\x{0179}\x{1E90}\x{017B}\x{017D}\x{1E92}\x{1E94}\x{01B5}\x{0224}\x{2C7F}\x{2C6B}\x{A762}'),
        array('base' => 'a', 'letters' => '\x{0061}\x{24D0}\x{FF41}\x{1E9A}\x{00E0}\x{00E1}\x{00E2}\x{1EA7}\x{1EA5}\x{1EAB}\x{1EA9}\x{00E3}\x{0101}\x{0103}\x{1EB1}\x{1EAF}\x{1EB5}\x{1EB3}\x{0227}\x{01E1}\x{00E4}\x{01DF}\x{1EA3}\x{00E5}\x{01FB}\x{01CE}\x{0201}\x{0203}\x{1EA1}\x{1EAD}\x{1EB7}\x{1E01}\x{0105}\x{2C65}\x{0250}'),
        array('base' => 'aa', 'letters' => '\x{A733}'),
        array('base' => 'ae', 'letters' => '\x{00E6}\x{01FD}\x{01E3}'),
        array('base' => 'ao', 'letters' => '\x{A735}'),
        array('base' => 'au', 'letters' => '\x{A737}'),
        array('base' => 'av', 'letters' => '\x{A739}\x{A73B}'),
        array('base' => 'ay', 'letters' => '\x{A73D}'),
        array('base' => 'b', 'letters' => '\x{0062}\x{24D1}\x{FF42}\x{1E03}\x{1E05}\x{1E07}\x{0180}\x{0183}\x{0253}'),
        array('base' => 'c', 'letters' => '\x{0063}\x{24D2}\x{FF43}\x{0107}\x{0109}\x{010B}\x{010D}\x{00E7}\x{1E09}\x{0188}\x{023C}\x{A73F}\x{2184}'),
        array('base' => 'd', 'letters' => '\x{0064}\x{24D3}\x{FF44}\x{1E0B}\x{010F}\x{1E0D}\x{1E11}\x{1E13}\x{1E0F}\x{0111}\x{018C}\x{0256}\x{0257}\x{A77A}'),
        array('base' => 'dz', 'letters' => '\x{01F3}\x{01C6}'),
        array('base' => 'e', 'letters' => '\x{0065}\x{24D4}\x{FF45}\x{00E8}\x{00E9}\x{00EA}\x{1EC1}\x{1EBF}\x{1EC5}\x{1EC3}\x{1EBD}\x{0113}\x{1E15}\x{1E17}\x{0115}\x{0117}\x{00EB}\x{1EBB}\x{011B}\x{0205}\x{0207}\x{1EB9}\x{1EC7}\x{0229}\x{1E1D}\x{0119}\x{1E19}\x{1E1B}\x{0247}\x{025B}\x{01DD}'),
        array('base' => 'f', 'letters' => '\x{0066}\x{24D5}\x{FF46}\x{1E1F}\x{0192}\x{A77C}'),
        array('base' => 'g', 'letters' => '\x{0067}\x{24D6}\x{FF47}\x{01F5}\x{011D}\x{1E21}\x{011F}\x{0121}\x{01E7}\x{0123}\x{01E5}\x{0260}\x{A7A1}\x{1D79}\x{A77F}'),
        array('base' => 'h', 'letters' => '\x{0068}\x{24D7}\x{FF48}\x{0125}\x{1E23}\x{1E27}\x{021F}\x{1E25}\x{1E29}\x{1E2B}\x{1E96}\x{0127}\x{2C68}\x{2C76}\x{0265}'),
        array('base' => 'hv', 'letters' => '\x{0195}'),
        array('base' => 'i', 'letters' => '\x{0069}\x{24D8}\x{FF49}\x{00EC}\x{00ED}\x{00EE}\x{0129}\x{012B}\x{012D}\x{00EF}\x{1E2F}\x{1EC9}\x{01D0}\x{0209}\x{020B}\x{1ECB}\x{012F}\x{1E2D}\x{0268}\x{0131}'),
        array('base' => 'j', 'letters' => '\x{006A}\x{24D9}\x{FF4A}\x{0135}\x{01F0}\x{0249}'),
        array('base' => 'k', 'letters' => '\x{006B}\x{24DA}\x{FF4B}\x{1E31}\x{01E9}\x{1E33}\x{0137}\x{1E35}\x{0199}\x{2C6A}\x{A741}\x{A743}\x{A745}\x{A7A3}'),
        array('base' => 'l', 'letters' => '\x{006C}\x{24DB}\x{FF4C}\x{0140}\x{013A}\x{013E}\x{1E37}\x{1E39}\x{013C}\x{1E3D}\x{1E3B}\x{017F}\x{0142}\x{019A}\x{026B}\x{2C61}\x{A749}\x{A781}\x{A747}'),
        array('base' => 'lj', 'letters' => '\x{01C9}'),
        array('base' => 'm', 'letters' => '\x{006D}\x{24DC}\x{FF4D}\x{1E3F}\x{1E41}\x{1E43}\x{0271}\x{026F}'),
        array('base' => 'n', 'letters' => '\x{006E}\x{24DD}\x{FF4E}\x{01F9}\x{0144}\x{00F1}\x{1E45}\x{0148}\x{1E47}\x{0146}\x{1E4B}\x{1E49}\x{019E}\x{0272}\x{0149}\x{A791}\x{A7A5}'),
        array('base' => 'nj', 'letters' => '\x{01CC}'),
        array('base' => 'o', 'letters' => '\x{006F}\x{24DE}\x{FF4F}\x{00F2}\x{00F3}\x{00F4}\x{1ED3}\x{1ED1}\x{1ED7}\x{1ED5}\x{00F5}\x{1E4D}\x{022D}\x{1E4F}\x{014D}\x{1E51}\x{1E53}\x{014F}\x{022F}\x{0231}\x{00F6}\x{022B}\x{1ECF}\x{0151}\x{01D2}\x{020D}\x{020F}\x{01A1}\x{1EDD}\x{1EDB}\x{1EE1}\x{1EDF}\x{1EE3}\x{1ECD}\x{1ED9}\x{01EB}\x{01ED}\x{00F8}\x{01FF}\x{0254}\x{A74B}\x{A74D}\x{0275}'),
        array('base' => 'oi', 'letters' => '\x{01A3}'),
        array('base' => 'ou', 'letters' => '\x{0223}'),
        array('base' => 'oo', 'letters' => '\x{A74F}'),
        array('base' => 'p', 'letters' => '\x{0070}\x{24DF}\x{FF50}\x{1E55}\x{1E57}\x{01A5}\x{1D7D}\x{A751}\x{A753}\x{A755}'),
        array('base' => 'q', 'letters' => '\x{0071}\x{24E0}\x{FF51}\x{024B}\x{A757}\x{A759}'),
        array('base' => 'r', 'letters' => '\x{0072}\x{24E1}\x{FF52}\x{0155}\x{1E59}\x{0159}\x{0211}\x{0213}\x{1E5B}\x{1E5D}\x{0157}\x{1E5F}\x{024D}\x{027D}\x{A75B}\x{A7A7}\x{A783}'),
        array('base' => 's', 'letters' => '\x{0073}\x{24E2}\x{FF53}\x{00DF}\x{015B}\x{1E65}\x{015D}\x{1E61}\x{0161}\x{1E67}\x{1E63}\x{1E69}\x{0219}\x{015F}\x{023F}\x{A7A9}\x{A785}\x{1E9B}'),
        array('base' => 't', 'letters' => '\x{0074}\x{24E3}\x{FF54}\x{1E6B}\x{1E97}\x{0165}\x{1E6D}\x{021B}\x{0163}\x{1E71}\x{1E6F}\x{0167}\x{01AD}\x{0288}\x{2C66}\x{A787}'),
        array('base' => 'tz', 'letters' => '\x{A729}'),
        array('base' => 'u', 'letters' => '\x{0075}\x{24E4}\x{FF55}\x{00F9}\x{00FA}\x{00FB}\x{0169}\x{1E79}\x{016B}\x{1E7B}\x{016D}\x{00FC}\x{01DC}\x{01D8}\x{01D6}\x{01DA}\x{1EE7}\x{016F}\x{0171}\x{01D4}\x{0215}\x{0217}\x{01B0}\x{1EEB}\x{1EE9}\x{1EEF}\x{1EED}\x{1EF1}\x{1EE5}\x{1E73}\x{0173}\x{1E77}\x{1E75}\x{0289}'),
        array('base' => 'v', 'letters' => '\x{0076}\x{24E5}\x{FF56}\x{1E7D}\x{1E7F}\x{028B}\x{A75F}\x{028C}'),
        array('base' => 'vy', 'letters' => '\x{A761}'),
        array('base' => 'w', 'letters' => '\x{0077}\x{24E6}\x{FF57}\x{1E81}\x{1E83}\x{0175}\x{1E87}\x{1E85}\x{1E98}\x{1E89}\x{2C73}'),
        array('base' => 'x', 'letters' => '\x{0078}\x{24E7}\x{FF58}\x{1E8B}\x{1E8D}'),
        array('base' => 'y', 'letters' => '\x{0079}\x{24E8}\x{FF59}\x{1EF3}\x{00FD}\x{0177}\x{1EF9}\x{0233}\x{1E8F}\x{00FF}\x{1EF7}\x{1E99}\x{1EF5}\x{01B4}\x{024F}\x{1EFF}'),
        array('base' => 'z', 'letters' => '\x{007A}\x{24E9}\x{FF5A}\x{017A}\x{1E91}\x{017C}\x{017E}\x{1E93}\x{1E95}\x{01B6}\x{0225}\x{0240}\x{2C6C}\x{A763}')
    );

    /**
     * Get the class (if it's an object) or the type of the specified variable.
     * @param mixed $var
     * @return string
     */
    public static function getType($var)
    {
        if (is_object($var))
            return get_class($var);
        else
            return gettype($var);
    }

    /**
     * Check if the array elements are instances of the specified class(es).
     * @param array $array
     * @param string|string[] $classes
     * @return bool
     */
    public static function instanceofArray($array, $classes)
    {
        if (!is_array($classes))
            $classes = [$classes];

        foreach ($array as $value) {
            $found = false;

            foreach ($classes as $class)
                if ($value instanceof $class)
                    $found = true;

            if (!$found)
                return false;
        }

        return true;
    }

    /**
     * Get all classes of the elements in an array.
     * @param array $array
     * @return string
     */
    public static function getArrayTypes($array)
    {
        $classes = [];

        foreach ($array as $value)
            if (!in_array(self::getType($value), $classes))
                $classes[] = self::getType($value);

        return sprintf('[%s]', implode(', ', $classes));
    }

    public static function stringIsNullOrEmpty($str)
    {
        if ($str === null)
            return true;

        return trim($str) === "";
    }

    /**
     * @param string $locale
     * @return string Returns the system locale formatted using the IETF standard (e.g. en-us)
     */
    public static function getIETFLocale($locale)
    {
        return str_replace('_', '-', $locale);
    }
}