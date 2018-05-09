<?php

namespace FormBuilder\Controls;


class PasswordControl extends InputControl
{
    public function renderContents()
    {
        printf('<input type="password" class="%1$s" id="%2$s" name="%2$s" placeholder="%3$s">', $this->getClasses(), $this->getName(), $this->getPlaceholder());
    }

    public function getType() {
        return 'password';
    }
}