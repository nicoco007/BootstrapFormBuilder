<?php

namespace FormBuilder\Controls;


class TextControl extends InputControl
{
    public function renderContents()
    {
        printf('<input type="text" class="%1$s" id="%2$s" name="%2$s" placeholder="%3$s" value="%4$s">', $this->getClasses(), $this->getName(), $this->getPlaceholder(), $this->getValue());
    }

    public function getType() {
        return 'text';
    }
}