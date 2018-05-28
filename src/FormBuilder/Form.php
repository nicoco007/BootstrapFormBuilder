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
    private $title;

    /** @var string */
    private $id;

    /** @var string */
    private $method;

    /** @var bool */
    private $init;

    /** @var Response */
    private $response;

    /** @var Controls\FormControl[] */
    private $controls = [];

    /** @var FormSection[] */
    private $sections = [];

    /** @var Button[] */
    private $buttons = [];

    /** @var bool */
    private $hasSubmitButton;

    /** @var int */
    private $columnCount = 1;

    /** @var string */
    private $locale = 'en';

    /** @var Translations */
    private $translations;

    /** @var string */
    private $buttonStyle = ButtonStyle::HORIZONTAL;

    /** @var FormCaptcha */
    private $captcha;

    /**
     * Form constructor.
     * @param string $title
     * @param string $method GET or POST
     * @param string $id Form ID (important when there are multiple forms on a single page)
     */
    public function __construct(string $id, string $method = 'GET', string $title = null)
    {
        $method = strtolower($method);

        if (!in_array($method, ['get', 'post']))
            throw new \InvalidArgumentException('Expected $method to be either GET or POST, got ' . $method);

        $this->id = $id;
        $this->title = $title;
        $this->method = $method;
    }

    public function init()
    {
        $this->translations = new Translations($this->locale);

        if (!isset($this->hasSubmitButton))
            $this->addButton(new SubmitButton());

        foreach ($this->getControls() as $control)
            $control->init();

        if ($this->isSubmitted()) {
            /** @var SubmitButton $submitButton */
            $submitButton = $this->buttons[$_POST['submit']];
            $error = null;

            if (!$this->hasError()) {
                try {
                    $this->response = $submitButton->submitCallback($this->getControls(true));
                    $submitButton->successCallback();
                } catch (\Exception $ex) {
                    $error = $ex;

                    $submitButton->errorCallback($ex);
                }
            }

            if ($this->isAjaxSubmit()) {
                $this->printJsonData($error);
            } else {
                if ($this->response !== null && $this->response->getRedirectUrl() !== null)
                    header('Location: ' . $this->response->getRedirectUrl());
            }
        }

        $this->init = true;
    }

    public function render()
    {
        if (!$this->init)
            throw new \RuntimeException('Form::init() must be called before rendering');

        printf('<form id="%s" method="%s" class="bsfb-form" data-locale="%s">', $this->id, $this->method, Util::getIETFLocale($this->locale));

        if ($this->title !== null)
            printf('<div class="form-title">%s</div>', $this->title);

        if ($this->response !== null)
            printf('<div class="alert alert-%s">%s</div>', $this->response->getClass(), $this->response->getMessage());

        printf('<input type="hidden" name="submitted" value="%s"/>', $this->id);

        if (count($this->sections) > 0) {
            foreach ($this->sections as $section) {
                $section->render();
            }
        } else {
            print('<fieldset>');
            print('<div class="row">');

            foreach ($this->controls as $control) {
                printf('<div class="col-xs-12 col-md-%d">', 12 / $this->columnCount);

                $control->render();

                print('</div>');
            }

            print('</div>');
            print('</fieldset>');
        }

        if ($this->captcha !== null) {
            print('<div class="form-group">');

            $this->captcha->render();

            if ($this->isSubmitted() && $this->validateCaptcha()) {
                printf('<div class="invalid-feedback d-block">%s</div>', $this->translations->translate('Please complete the CAPTCHA.'));
            }

            print('</div>');
        }

        printf('<div class="form-group form-buttons form-buttons-%s">', $this->buttonStyle);

        foreach ($this->buttons as $button) {
            print('<div class="button-container">');
            $button->render();
            print('</div>');
        }

        print('</div>');

        print('</form>');
    }

    /**
     * @param string $buttonStyle
     */
    public function setButtonStyle(string $buttonStyle)
    {
        $possibilities = [ButtonStyle::HORIZONTAL, ButtonStyle::HORIZONTAL_FULL_WIDTH, ButtonStyle::VERTICAL, ButtonStyle::VERTICAL_FULL_WIDTH];

        if (!is_string($buttonStyle))
            throw new \InvalidArgumentException('Expected $buttonStyle to be string, got ' . Util::getType($buttonStyle));

        if (!in_array($buttonStyle, $possibilities))
            throw new \InvalidArgumentException(sprintf('Expected $buttonStyle to be one of [%s], got %s.', implode(', ', $possibilities), $buttonStyle));

        $this->buttonStyle = $buttonStyle;
    }

    /**
     * @param Controls\FormControl $control
     */
    public function addControl(Controls\FormControl $control)
    {
        if (!($control instanceof Controls\FormControl))
            throw new \InvalidArgumentException('Expected $control to be instance of FormControl, got ' . Util::getType($control));

        if (count($this->sections) > 0)
            throw new InvalidOperationException('Cannot add controls to form with sections');

        if (count($intersect = array_intersect_key($this->getControls(true), $control->getChildren(true))) > 0)
            throw new \RuntimeException('Control with name ' . array_keys($intersect)[0] . ' was already added.');

        $control->setParentForm($this);

        $this->controls[$control->getName()] = $control;
    }

    /**
     * @param bool $deep
     * @return Controls\FormControl[]
     */
    public function getControls(bool $deep = false): array
    {
        /** @var Controls\FormControl[] $controls */
        $controls = [];

        if (count($this->sections) > 0) {
            foreach ($this->sections as $section) {
                $controls += $section->getControls($deep);
            }
        } else {
            $controls = $this->controls;

            if ($deep)
                foreach ($controls as $control)
                    $controls += $control->getChildren(true);
        }

        return $controls;
    }

    /**
     * @param FormSection $section
     */
    public function addSection(FormSection $section)
    {
        if (!($section instanceof FormSection))
            throw new \InvalidArgumentException('Expected $section to be instance of FormSection, got ' . Util::getType($section));

        if (count($this->controls) > 0)
            throw new InvalidOperationException('Cannot add sections to form with independent controls');

        if (count($intersect = array_intersect_key($this->getControls(true), $section->getControls(true))) > 0)
            throw new \RuntimeException('Control with name ' . array_keys($intersect)[0] . ' was already added.');

        $section->setParent($this);

        $this->sections[] = $section;
    }

    /**
     * @return FormSection[]
     */
    public function getSections(): array
    {
        return $this->sections;
    }

    /**
     * @param Button $button
     */
    public function addButton(Button $button)
    {
        if (!($button instanceof Button))
            throw new \InvalidArgumentException('Expected $button to be instance of Button, got ' . Util::getType($button));

        if (isset($this->buttons[$button->getId()]))
            throw new \InvalidArgumentException(sprintf('A button with the ID "%s" was already added', $button->getId()));

        if ($button instanceof SubmitButton)
            $this->hasSubmitButton = true;

        $button->setParentForm($this);

        $this->buttons[$button->getId()] = $button;
    }

    /**
     * @return Button[]
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }

    /**
     * @return bool
     */
    public function isSubmitted(): bool
    {
        return isset($_POST['submitted'])
            && isset($this->id) && $_POST['submitted'] === $this->id
            && isset($_POST['submit']) && in_array($_POST['submit'], array_keys($this->buttons), true);
    }

    /**
     * @return bool
     */
    public function isAjaxSubmit(): bool
    {
        return isset($_POST['ajax_submit']) && boolval($_POST['ajax_submit']) === true;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        if ($this->isSubmitted()) {
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
        }

        if (!$this->validateCaptcha())
            return true;

        return false;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param int $columnCount
     */
    public function setColumnCount(int $columnCount)
    {
        if ($columnCount < 1 || $columnCount > 4)
            throw new \InvalidArgumentException('$columnCount must be between 1 and 4');

        $this->columnCount = $columnCount;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return Translations
     */
    public function getTranslations(): Translations
    {
        return $this->translations;
    }

    /**
     * @param FormCaptcha $captcha
     */
    public function setCaptcha(FormCaptcha $captcha)
    {
        $this->captcha = $captcha;
    }

    /**
     * @param \Exception $ex
     */
    private function printJsonData(\Exception $ex)
    {
        $data = [
            'received' => $this->isSubmitted(),
            'success' => $this->response instanceof SuccessResponse
        ];

        if ($this->captcha !== null)
            $data['recaptcha-validated'] = $this->validateCaptcha();

        if ($this->response !== null)
            $data['response'] = $this->response->jsonSerialize();

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

        foreach ($this->getControls(true) as $control)
            if ($control->hasError())
                $data['controlErrors'][$control->getName()] = $control->getErrorMessage();

        header('Content-Type: application/json');
        print(json_encode($data));
        exit(0);
    }

    private function validateCaptcha(): bool
    {
        if ($this->captcha === null)
            return true;

        if (!isset($_POST['g-recaptcha-response']))
            return false;

        return $this->captcha->validate($_POST['g-recaptcha-response']);
    }
}