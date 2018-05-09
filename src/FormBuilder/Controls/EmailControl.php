<?php

namespace FormBuilder\Controls;


class EmailControl extends InputControl
{
    public function renderContents()
    {
        printf('<input type="email" class="%1$s" id="%2$s" name="%2$s" placeholder="%3$s" value="%4$s">', $this->getClasses(), $this->getName(), $this->getPlaceholder(), $this->getValue());
    }

    public function getType() {
        return 'email';
    }
}