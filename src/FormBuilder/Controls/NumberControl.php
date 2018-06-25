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

namespace FormBuilder\Controls;


use FormBuilder\Util;

class NumberControl extends InputControl
{
    private $icon;

    public function renderContents()
    {
        print('<div class="input-group">');

        if ($this->icon !== null)
            printf('<span class="input-group-prepend"><span class="input-group-text"><i class="fa fa-%s"></i></span></span>', $this->icon);

        printf('<input type="number" class="%1$s" id="%2$s" name="%2$s" placeholder="%3$s" value="%4$d"/>', $this->getClasses(), $this->getName(), $this->getPlaceholder(), $this->getValue());
        print('</div>');
    }

    public function getType()
    {
        return 'number';
    }

    protected function parseValueFromPost()
    {
        if (isset($_POST[$this->getName()]) && !Util::stringIsNullOrEmpty($_POST[$this->getName()]))
            return intval($_POST[$this->getName()]);

        return null;
    }

    public function setIcon(string $icon) {
        $this->icon = $icon;
    }
}