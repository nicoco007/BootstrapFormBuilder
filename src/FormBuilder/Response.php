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

    /**
     * Response constructor.
     * @param string $message
     * @param string $class
     * @param string $icon
     */
    public function __construct(string $message, string $class, string $icon = 'info-circle')
    {

        $this->message = $message;
        $this->class = $class;
        $this->icon = $icon;
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

    public function jsonSerialize()
    {
        return [
            'message' => $this->message,
            'class' => $this->class,
            'icon' => $this->icon
        ];
    }
}