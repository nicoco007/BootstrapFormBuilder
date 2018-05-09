<?php

namespace FormBuilder\Controls;


use FormBuilder\HtmlTag;
use FormBuilder\Util;

class RadioButtonControl extends FormControl
{
    /**
     * @var RadioOption[]
     */
    private $options = [];

    public function render()
    {
        print('<div class="form-group">');

        printf('<label>%s</label>', $this->getLabel());

        foreach ($this->options as $option) {
            print('<div class="custom-control custom-radio">');

            $input = new HtmlTag('input', true);

            $value = $this->getName() . '_' . $option->getKey();

            $input->addAttribute('type', 'radio');
            $input->addAttribute('id', $value);
            $input->addAttribute('name', $this->getName());
            $input->addAttribute('value', $value);
            $input->addAttribute('class', 'custom-control-input');

            if ($option->getKey() === $this->getSubmittedKey())
                $input->addAttribute('checked');

            $input->render();

            printf('<label class="custom-control-label" for="%s">%s</label>', $value, $option->getLabel());

            print('</div>');
        }

        print('</div>');
    }

    /**
     * @return null|string
     */
    private function getSubmittedKey() {
        if (!$this->getParent()->isSubmitted())
            return null;

        if (!isset($_POST[$this->getName()]) || Util::stringIsNullOrEmpty($_POST[$this->getName()]) || strlen($_POST[$this->getName()]) <= strlen($this->getName()))
            return null;

        $key = substr($_POST[$this->getName()], strlen($this->getName()) + 1);

        if (!isset($this->options[$key]))
            return null;

        return $key;
    }

    /**
     * @return null|string
     */
    protected function parseValueFromPost()
    {
        $key = $this->getSubmittedKey();

        if ($key === null)
            return null;

        return $this->options[$key]->getValue();
    }

    /**
     * @param string $label
     * @param string $key
     * @param mixed $value
     */
    public function addOption($label, $key, $value) {
        $this->options[$key] = new RadioOption($label, $key, $value);
    }

    public function getType()
    {
        return 'radio';
    }
}