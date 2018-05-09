<?php

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

    public function render() {
        if (!Util::stringIsNullOrEmpty($this->getIcon()))
            printf('<a href="%s" class="btn btn-%s"><i class="fa fa-%s"></i>&nbsp;%s</a>', $this->url, $this->getClass(), $this->getIcon(), $this->getText());
        else
            printf('<a href="%s" class="btn btn-%s">%s</a>', $this->url, $this->getClass(), $this->getText());
    }

    public function doAction() { }
}