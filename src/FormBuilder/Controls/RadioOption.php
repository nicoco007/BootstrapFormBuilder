<?php

namespace FormBuilder\Controls;


use FormBuilder\Util;

class RadioOption
{
    /** @var string */
    private $label;

    /** @var string */
    private $key;

    /** @var string */
    private $value;

    /**
     * RadioOption constructor.
     * @param string $label
     * @param string $key
     * @param mixed $value
     */
    public function __construct($label, $key, $value)
    {
        if (!is_string($label))
            throw new \InvalidArgumentException('Expected $label to be string, got ' . Util::getType($label));

        if (!is_string($key))
            throw new \InvalidArgumentException('Expected $key to be string, got ' . Util::getType($key));

        $this->label = $label;
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}