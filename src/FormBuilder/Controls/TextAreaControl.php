<?php
/**
 * Created by PhpStorm.
 * User: nicolasgnyra
 * Date: 18-05-06
 * Time: 16:30
 */

namespace FormBuilder\Controls;


use FormBuilder\Util;

class TextAreaControl extends FormControl
{
    private $placeholder;

    function render()
    {
        print('<div class="form-group">');

        printf('<label for="%s">%s</label>', $this->getName(), $this->getLabel());

        printf('<textarea class="form-control" id="%1$s" name="%1$s" placeholder="%2$s">%3$s</textarea>', $this->getName(), $this->getPlaceholder(), $this->getValue());

        if ($this->hasError())
            printf('<div class="invalid-feedback">%s</div>', $this->getErrorMessage());

        if (!Util::stringIsNullOrEmpty($this->getHint()))
            printf('<small class="form-text text-muted">%s</small>', $this->getHint());

        print('</div>');
    }

    public function getPlaceholder() {
        return $this->placeholder;
    }

    public function setPlaceholder($placeholder) {
        $this->placeholder = $placeholder;
    }

    public function getType()
    {
        return 'textarea';
    }
}