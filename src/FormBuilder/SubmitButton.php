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
    private $submit_callback;

    /** @var callable */
    private $success_callback;

    /** @var callable */
    private $error_callback;

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
     * @param callable $submit_callback
     */
    public function setSubmitCallback($submit_callback)
    {
        $this->submit_callback = $submit_callback;
    }

    /**
     * @param callable $success_callback
     */
    public function setSuccessCallback($success_callback)
    {
        $this->success_callback = $success_callback;
    }

    /**
     * @param callable $error_callback
     */
    public function setErrorCallback($error_callback)
    {
        $this->error_callback = $error_callback;
    }

    /**
     * @param Controls\FormControl[] $controls
     * @return Response
     */
    public function submitCallback($controls)
    {
        $response = null;

        if ($this->submit_callback !== null)
            $response = call_user_func($this->submit_callback, $controls);

        return $response !== null ? $response : new SuccessResponse(Translations::translate('Form submitted successfully.'));
    }

    public function successCallback()
    {
        if ($this->success_callback !== null)
            call_user_func($this->success_callback);
    }

    /**
     * @param \Exception $ex
     */
    public function errorCallback($ex)
    {
        if ($this->error_callback !== null)
            call_user_func($this->error_callback, $ex);
    }
}