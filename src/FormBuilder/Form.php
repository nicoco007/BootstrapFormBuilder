<?php

namespace FormBuilder;


class Form
{
    /** @var string */
    private $id;

    /** @var string */
    private $method;

    /** @var bool */
    private $init;

    /** @var Controls\FormControl[] */
    private $controls;

    /** @var Button[] */
    private $buttons;

    /**
     * Form constructor.
     * @param string $method GET or POST
     * @param string $id Form ID (useful when there are multiple forms on a single page)
     */
    public function __construct($method = 'GET', $id = null)
    {
        $this->method = $method;
        $this->controls = [];
        $this->buttons = [];
    }

    public function init()
    {
        if (!isset($this->buttons['submit']))
            array_unshift($this->buttons, new SubmitButton());

        if ($this->isSubmitted() && !$this->hasError())
            $this->buttons['submit']->doAction();

        $this->init = true;
    }

    public function render()
    {
        if (!$this->init)
            throw new \RuntimeException('Form::init must be called before rendering');

        printf('<form method="%s">', $this->method);

        print('<fieldset>');

        printf('<input type="hidden" name="submitted" value="%s"/>', !Util::stringIsNullOrEmpty($this->id) ? $this->id : 'true');

        foreach ($this->controls as $control) {
            $control->render();
        }

        print('</fieldset>');

        foreach ($this->buttons as $button) {
            $button->render();
            print(' ');
        }

        print('</form>');
    }

    /**
     * @param Controls\FormControl $control
     */
    public function addControl($control)
    {
        if (!($control instanceof Controls\FormControl))
            throw new \InvalidArgumentException('Expected $control to be instance of FormControl, got ' . Util::getType($control));

        if (isset($this->controls[$control->getName()]))
            throw new \InvalidArgumentException(sprintf('A button with the name "%s" was already added', $control->getName()));

        $control->setParent($this);

        $this->controls[$control->getName()] = $control;
    }

    /**
     * @return Controls\FormControl[]
     */
    public function getControls()
    {
        return $this->controls;
    }

    /**
     * @param Button $button
     */
    public function addButton($button)
    {
        if (!($button instanceof Button))
            throw new \InvalidArgumentException('Expected $button to be instance of Button, got ' . Util::getType($button));

        if (isset($this->buttons[$button->getName()]))
            throw new \InvalidArgumentException(sprintf('A button with the name "%s" was already added', $button->getName()));

        $this->buttons[$button->getName()] = $button;
    }

    /**
     * @return bool
     */
    public function isSubmitted()
    {
        return isset($_POST['submitted']) && ((isset($this->id) && $_POST['submitted'] === $this->id) || $_POST['submitted'] === "true") && isset($_POST['submit']) && $_POST['submit'] === 'submit';
    }

    /**
     * @return boolean
     */
    public function hasError()
    {
        foreach ($this->controls as $control)
            if ($control->hasError())
                return true;

        return false;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}