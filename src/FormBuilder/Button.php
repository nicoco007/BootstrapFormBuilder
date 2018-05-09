<?php

namespace FormBuilder;


abstract class Button
{
    /** @var string */
    private $name;

    /** @var string */
    private $text;

    /** @var string */
    private $class;

    /** @var string */
    private $icon;

    /**
     * Button constructor.
     * @param string $name
     * @param string $text
     * @param string $class
     * @param string|null $icon
     */
    public function __construct($name, $text, $class, $icon = null)
    {
        if (!is_string($name))
            throw new \InvalidArgumentException('Expected $name to be string, got ' . Util::getType($name));

        if (!is_string($text))
            throw new \InvalidArgumentException('Expected $text to be string, got ' . Util::getType($text));

        if (!is_string($class))
            throw new \InvalidArgumentException('Expected $class to be string, got ' . Util::getType($class));

        if ($icon !== null && !is_string($icon))
            throw new \InvalidArgumentException('Expected $icon to be null or string, got ' . Util::getType($icon));

        $this->name = $name;
        $this->text = $text;
        $this->class = $class;
        $this->icon = $icon;
    }

    public abstract function render();
    public abstract function doAction();

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    protected function getText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    protected function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    protected function getIcon()
    {
        return $this->icon;
    }
}