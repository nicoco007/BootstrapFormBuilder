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


class SubmitButton extends Button
{
    private $redirect_url;

    public function __construct($text = 'Submit', $icon = 'save', $redirect_url = null)
    {
        if (!Util::stringIsNullOrEmpty($redirect_url) && !is_string($redirect_url))
            throw new \InvalidArgumentException('Expected $redirect_url to be string, got ' . Util::getType($redirect_url));

        parent::__construct('submit', $text, BootstrapClass::SUCCESS, $icon);

        $this->redirect_url = $redirect_url;
    }

    public function doAction()
    {
        if (!Util::stringIsNullOrEmpty($this->redirect_url))
            header('Location: ' . $this->redirect_url);
    }

    public function render()
    {
        if (!Util::stringIsNullOrEmpty($this->getIcon()))
            printf('<button class="btn btn-%s" type="submit" name="submit" value="%s"><i class="fa fa-%s"></i>&nbsp;%s</button>', $this->getClass(), $this->getName(), $this->getIcon(), $this->getText());
        else
            printf('<button class="btn btn-%s" type="submit" name="submit" value="%s">%s</button>', $this->getClass(), $this->getName(), $this->getText());
    }
}