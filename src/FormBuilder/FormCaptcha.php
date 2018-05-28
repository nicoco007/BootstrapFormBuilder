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


class FormCaptcha
{
    /** @var string */
    private $siteKey;

    /** @var string */
    private $secretKey;

    /** @var bool */
    private $validationCache = null;

    public function __construct($siteKey, $secretKey)
    {
        if (!is_string($siteKey))
            throw new \InvalidArgumentException('Expected $siteKey to be string, got ' . Util::getType($siteKey));

        if (!is_string($secretKey))
            throw new \InvalidArgumentException('Expected $secretKey to be string, got ' . Util::getType($secretKey));

        $this->siteKey = $siteKey;
        $this->secretKey = $secretKey;
    }

    public function render()
    {
        printf('<div class="g-recaptcha" data-sitekey="%s"></div>', $this->siteKey);
    }

    public function validate($value)
    {
        if ($this->validationCache === null) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'secret' => $this->secretKey,
                'response' => $value,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            ]));

            $response = curl_exec($ch);

            $json = json_decode($response);

            $this->validationCache = $json['success'] === true;
        }

        return $this->validationCache;
    }
}