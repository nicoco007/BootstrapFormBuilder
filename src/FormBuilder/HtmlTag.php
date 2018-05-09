<?php

namespace FormBuilder;


class HtmlTag
{
    /** @var string */
    private $name;

    /** @var bool */
    private $self_enclosed;

    /** @var object[] */
    private $attributes;

    public function __construct($name, $self_enclosed = false)
    {
        $this->name = $name;
        $this->self_enclosed = $self_enclosed;
    }

    public function addAttribute($name, $value = null) {
        $this->attributes[] = ['name' => $name, 'value' => $value];
    }

    public function render() {
        $attributes_str = implode(' ', array_map(function ($attr) {
            if ($attr['value'] !== null)
                return sprintf('%s="%s"', $attr['name'], $attr['value']);
            else
                return $attr['name'];
        }, $this->attributes));

        printf('<%s %s>', $this->name, $attributes_str);

        if (!$this->self_enclosed)
            printf('</%s>', $this->name);
    }
}