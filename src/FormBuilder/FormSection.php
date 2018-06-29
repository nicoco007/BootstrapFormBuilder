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


use FormBuilder\Controls\FormControl;

class FormSection
{
    /** @var Form */
    private $parent;

    /** @var string */
    private $label;

    /** @var Controls\FormControl[] */
    private $controls = [];

    /** @var int */
    private $columnCount = 1;

    /** @var int */
    private $order = null;

    /**
     * FormSection constructor.
     * @param string $label
     */
    public function __construct($label)
    {
        $this->label = $label;
    }

    public function render()
    {
        usort($this->controls, function ($a, $b) {
            /** @var $a Controls\FormControl */
            /** @var $b Controls\FormControl */
            return $a->getOrder() > $b->getOrder();
        });

        printf('<div class="form-section-title">%s</div>', $this->label);

        print('<fieldset>');
        print('<div class="row">');

        foreach ($this->controls as $control) {
            $xl = 12 / $this->columnCount * $control->getColumnSpan();
            $md = 12 / ceil($this->columnCount / 2) * $control->getColumnSpan();

            printf('<div class="col-xs-12 col-xl-%d col-md-%d">', min(12, $xl), min(12, $md));

            $control->render();

            print('</div>');
        }

        print('</div>');
        print('</fieldset>');
    }

    /**
     * @param Controls\FormControl $control
     */
    public function addControl($control): void
    {
        if (!($control instanceof Controls\FormControl))
            throw new \InvalidArgumentException('Expected $control to be instance of FormControl, got ' . Util::getType($control));

        if (count($intersect = array_intersect_key($this->getControls(), $control->getChildren(true))) > 0)
            throw new \RuntimeException('Control with name ' . array_keys($intersect)[0] . ' was already added.');

        $this->controls[$control->getName()] = $control;

        if ($control->getOrder() === 0)
            $control->setOrder(count($this->controls));
    }

    public function getControls($deep = false): array
    {
        $controls = $this->controls;

        if ($deep)
            foreach ($this->controls as $control)
                $controls += $control->getChildren(true);

        return $controls;
    }

    /**
     * @param Form $parent
     */
    public function setParent($parent): void
    {
        if (!($parent instanceof Form))
            throw new \InvalidArgumentException('Expected $control to be instance of Form, got ' . Util::getType($parent));

        foreach ($this->controls as $control)
            $control->setParentForm($parent);

        $this->parent = $parent;
    }

    /**
     * @param int $columnCount
     */
    public function setColumnCount($columnCount): void
    {
        if ($columnCount < 1 || $columnCount > 4)
            throw new \InvalidArgumentException('$columnCount must be between 1 and 4');

        $this->columnCount = $columnCount;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder(int $order): void
    {
        $this->order = $order;
    }
}