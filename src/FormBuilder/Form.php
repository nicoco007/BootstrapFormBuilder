<?php
/**
 * Copyright © 2018  Nicolas Gnyra
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace FormBuilder;


class Form
{
    /** @var string */
    private $id;

    /** @var string */
    private $method;

    /** @var bool */
    private $init;

    /** @var Response */
    private $response;

    /** @var Controls\FormControl[] */
    private $controls;

    /** @var FormSection[] */
    private $sections;

    /** @var Button[] */
    private $buttons;

    /** @var string */
    private $success_message;

    /** @var bool */
    private $has_submit_button;

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
        if (!isset($this->has_submit_button))
            array_unshift($this->buttons, new SubmitButton());

        foreach ($this->getControls() as $control)
            $control->init();

        if ($this->isSubmitted()) {
            /** @var SubmitButton $submit_button */
            $submit_button = $this->buttons[$_POST['submit']];
            $error = null;

            if (!$this->hasError()) {
                $controls = $this->getControls();

                try {
                    $this->response = $submit_button->submitCallback($controls);
                    $submit_button->successCallback();
                } catch (\Exception $ex) {
                    $error = $ex;

                    $submit_button->errorCallback($ex);
                }
            }

            if ($this->isAjaxSubmit()) {
                $this->printJsonData($error);
            } else {
                if ($this->response->getRedirectUrl() !== null)
                    header('Location: ' . $this->response->getRedirectUrl());
            }
        }

        $this->init = true;
    }

    public function render()
    {
        if (!$this->init)
            throw new \RuntimeException('Form::init must be called before rendering');

        printf('<form method="%s" class="bsfb-form">', $this->method);

        if ($this->response !== null)
            printf('<div class="alert alert-%s">%s</div>', $this->response->getClass(), $this->response->getMessage());

        printf('<input type="hidden" name="submitted" value="%s"/>', !Util::stringIsNullOrEmpty($this->id) ? $this->id : 'true');

        if (count($this->sections) > 0) {
            foreach ($this->sections as $section) {
                $section->render();
            }
        } else {
            print('<fieldset>');

            foreach ($this->controls as $control) {
                $control->render();
            }

            print('</fieldset>');
        }

        print('<div class="form-group">');

        foreach ($this->buttons as $button) {
            $button->render();
            print(' ');
        }

        print('</div>');

        print('</form>');
    }

    /**
     * @param Controls\FormControl $control
     */
    public function addControl($control)
    {
        if (!($control instanceof Controls\FormControl))
            throw new \InvalidArgumentException('Expected $control to be instance of FormControl, got ' . Util::getType($control));

        if (count($this->sections) > 0)
            throw new InvalidOperationException('Cannot add controls to form with sections');

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
        if (count($this->sections) > 0) {
            $controls = [];

            foreach ($this->sections as $section)
                $controls += $section->getControls();

            return $controls;
        } else {
            return $this->controls;
        }
    }

    /**
     * @param FormSection $section
     */
    public function addSection($section)
    {
        if (!($section instanceof FormSection))
            throw new \InvalidArgumentException('Expected $section to be instance of FormSection, got ' . Util::getType($section));

        if (count($this->controls) > 0)
            throw new InvalidOperationException('Cannot add sections to form with independent controls');

        $section->setParent($this);

        $this->sections[] = $section;
    }

    /**
     * @return FormSection[]
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param Button $button
     */
    public function addButton($button)
    {
        if (!($button instanceof Button))
            throw new \InvalidArgumentException('Expected $button to be instance of Button, got ' . Util::getType($button));

        if (isset($this->buttons[$button->getId()]))
            throw new \InvalidArgumentException(sprintf('A button with the ID "%s" was already added', $button->getId()));

        if ($button instanceof SubmitButton)
            $this->has_submit_button = true;

        $this->buttons[$button->getId()] = $button;
    }

    /**
     * @return bool
     */
    public function isSubmitted()
    {
        return isset($_POST['submitted'])
            && ((isset($this->id) && $_POST['submitted'] === $this->id) || $_POST['submitted'] === "true")
            && isset($_POST['submit']) && in_array($_POST['submit'], array_keys($this->buttons));
    }

    /**
     * @return bool
     */
    public function isAjaxSubmit()
    {
        return isset($_POST['ajax_submit']) && boolval($_POST['ajax_submit']) === true;
    }

    /**
     * @return boolean
     */
    public function hasError()
    {
        if (count($this->sections) > 0) {
            foreach ($this->sections as $section)
                foreach ($section->getControls() as $control)
                    if ($control->hasError())
                        return true;
        } else {
            foreach ($this->controls as $control)
                if ($control->hasError())
                    return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $success_message
     */
    public function setSuccessMessage($success_message)
    {
        $this->success_message = $success_message;
    }

    /**
     * @param \Exception $ex
     */
    private function printJsonData($ex)
    {
        $data = [];

        $data['received'] = $this->isSubmitted();
        $data['success'] = $this->response instanceof SuccessResponse;

        if ($this->response !== null) {
            $data['response'] = [
                'message' => $this->response->getMessage(),
                'class' => $this->response->getClass(),
                'redirect' => $this->response->getRedirectUrl()
            ];
        }

        if ($ex !== null) {
            $data['error'] = [
                'type' => get_class($ex),
                'message' => $ex->getMessage(),
                'code' => $ex->getCode(),
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'trace' => $ex->getTrace()
            ];
        }

        if (count($this->sections) > 0) {
            foreach ($this->sections as $section) {
                foreach ($section->getControls() as $control) {
                    if ($control->hasError())
                        $data['controlErrors'][$control->getName()] = $control->getErrorMessage();
                }
            }
        } else {
            foreach ($this->controls as $control) {
                if ($control->hasError())
                    $data['controlErrors'][$control->getName()] = $control->getErrorMessage();
            }
        }

        header('Content-Type: application/json');
        print(json_encode($data));
        exit(0);
    }
}