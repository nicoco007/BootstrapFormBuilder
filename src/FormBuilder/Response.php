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


abstract class Response implements \JsonSerializable
{
    /** @var string */
    private $message;

    /** @var string */
    private $class;

    /** @var string */
    private $icon;

    /** @var string */
    private $redirect_url;

    /**
     * Response constructor.
     * @param string $message
     * @param string $class
     * @param string $icon
     * @param string $redirect_url
     */
    public function __construct($message, $class, $icon = 'info-circle', $redirect_url = null)
    {
        if (!is_string($message))
            throw new \InvalidArgumentException('Expected $message to be string, got ' . Util::getType(($message)));

        if (!is_string($class))
            throw new \InvalidArgumentException('Expected $class to be string, got ' . Util::getType(($class)));

        if (!is_string($icon))
            throw new \InvalidArgumentException('Expected $icon to be string, got ' . Util::getType(($icon)));

        if ($redirect_url !== null && !is_string($redirect_url))
            throw new \InvalidArgumentException('Expected $redirect_url to be string, got ' . Util::getType(($redirect_url)));

        $this->message = $message;
        $this->class = $class;
        $this->icon = $icon;
        $this->redirect_url = $redirect_url;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirect_url;
    }

    public function jsonSerialize()
    {
        return [
            'message' => $this->message,
            'class' => $this->class,
            'icon' => $this->icon,
            'redirect' => $this->redirect_url
        ];
    }
}