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

    /** @var string */
    private $size;

    /** @var bool */
    private $validationCache = null;

    public function __construct(string $siteKey, string $secretKey)
    {
        $this->siteKey = $siteKey;
        $this->secretKey = $secretKey;
    }

    public function render()
    {
        $captcha = new HtmlTag('div');
        $captcha->addAttribute('class', 'g-recaptcha');
        $captcha->addAttribute('data-sitekey', $this->siteKey);

        if ($this->size !== null)
            $captcha->addAttribute('data-size', $this->size);

        $captcha->render();
    }

    public function validate(string $value)
    {
        $response = $this->getResponse($value);

        if (!is_array($response))
            return false;

        return $response['success'] === true;
    }

    public function getResponse(string $value)
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

            $this->validationCache = json_decode($response, true);
        }

        return $this->validationCache;
    }

    /**
     * @param string $size
     */
    public function setSize(string $size)
    {
        $this->size = $size;
    }
}