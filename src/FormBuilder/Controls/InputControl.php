<?php

namespace FormBuilder\Controls;


use FormBuilder\Util;

/**
 * Class representing most form control that uses the <input> tag.
 * @package FormBuilder\Controls
 */
abstract class InputControl extends FormControl
{
    /** @var string */
    private $placeholder;

    abstract function renderContents();

    public final function render()
    {
        print('<div class="form-group">');

        printf('<label for="%s">%s</label>', $this->getName(), $this->getLabel());

        $this->renderContents();

        if ($this->hasError())
            printf('<div class="invalid-feedback">%s</div>', $this->getErrorMessage());

        if (!Util::stringIsNullOrEmpty($this->getHint()))
            printf('<small class="form-text text-muted">%s</small>', $this->getHint());

        print('</div>');
    }

    protected function getClasses()
    {
        $classes = ['form-control'];

        if ($this->hasError())
            $classes[] = 'is-invalid';

        return implode(' ', $classes);
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     */
    public function setPlaceholder($placeholder)
    {
        if (!is_string($placeholder))
            throw new \InvalidArgumentException('Expected $placeholder to be string, got ' . Util::getType($placeholder));

        $this->placeholder = $placeholder;
    }
}