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


class SubmitButton extends Button
{
    /** @var callable */
    private $submitCallback;

    /** @var callable */
    private $successCallback;

    /** @var callable */
    private $errorCallback;

    public function __construct($text = 'Submit', $name = 'submit', $icon = 'save', $class = BootstrapClass::SUCCESS)
    {
        parent::__construct($name, $text, $class, $icon);
    }

    public function render()
    {
        if (!Util::stringIsNullOrEmpty($this->getIcon()))
            printf('<button class="btn btn-%1$s" type="submit" id="%2$s" name="submit" value="%2$s"><i class="fa fa-%3$s"></i>&nbsp;%4$s</button>', $this->getClass(), $this->getId(), $this->getIcon(), $this->getText());
        else
            printf('<button class="btn btn-%1$s" type="submit" id="%2$s" name="submit" value="%2$s">%3$s</button>', $this->getClass(), $this->getId(), $this->getText());
    }

    /**
     * @param callable $submitCallback
     */
    public function setSubmitCallback($submitCallback)
    {
        $this->submitCallback = $submitCallback;
    }

    /**
     * @param callable $successCallback
     */
    public function setSuccessCallback($successCallback)
    {
        $this->successCallback = $successCallback;
    }

    /**
     * @param callable $errorCallback
     */
    public function setErrorCallback($errorCallback)
    {
        $this->errorCallback = $errorCallback;
    }

    /**
     * @param Controls\FormControl[] $controls
     * @return Response
     */
    public function submitCallback($controls)
    {
        $response = null;

        if ($this->submitCallback !== null)
            $response = call_user_func($this->submitCallback, $controls);

        return $response !== null ? $response : new SuccessResponse($this->translate('Form submitted successfully.'));
    }

    public function successCallback()
    {
        if ($this->successCallback !== null)
            call_user_func($this->successCallback);
    }

    /**
     * @param \Exception $ex
     */
    public function errorCallback($ex)
    {
        if ($this->errorCallback !== null)
            call_user_func($this->errorCallback, $ex);
    }
}