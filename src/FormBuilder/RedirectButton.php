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


class RedirectButton extends Button
{
    private $url;

    public function __construct($text, $url, $class = BootstrapClass::LIGHT, $icon = null)
    {
        if (!is_string($url))
            throw new \InvalidArgumentException('Expected $url to be string, got ' . Util::getType($url));

        if (!is_string($class))
            throw new \InvalidArgumentException('Expected $class to be string, got ' . Util::getType($class));

        parent::__construct(hash('sha256', $url), $text, $class, $icon);

        $this->url = $url;
    }

    public function render()
    {
        if (!Util::stringIsNullOrEmpty($this->getIcon()))
            printf('<a href="%s" class="btn btn-%s"><i class="fa fa-%s"></i>&nbsp;%s</a>', $this->url, $this->getClass(), $this->getIcon(), $this->getText());
        else
            printf('<a href="%s" class="btn btn-%s">%s</a>', $this->url, $this->getClass(), $this->getText());
    }
}