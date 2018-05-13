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

require_once '../../vendor/autoload.php';

use FormBuilder\BootstrapClass;
use FormBuilder\Controls\CheckboxControl;
use FormBuilder\Controls\DateControl;
use FormBuilder\Controls\EmailControl;
use FormBuilder\Controls\PasswordControl;
use FormBuilder\Controls\RadioButtonControl;
use FormBuilder\Controls\TextAreaControl;
use FormBuilder\Controls\TextControl;
use FormBuilder\ErrorResponse;
use FormBuilder\Form;
use FormBuilder\FormSection;
use FormBuilder\RedirectButton;
use FormBuilder\SubmitButton;
use FormBuilder\SuccessResponse;

setlocale(LC_ALL, 'fr_ca');

$form = new Form('post');

$section1 = new FormSection('Section I');
$section2 = new FormSection('Section II');

$text_control = new TextControl('Name', 'name');
$email_control = new EmailControl('Email Address', 'email');
$password_control = new PasswordControl('Password', 'password');
$checkbox = new CheckboxControl('Check this out', 'checkbox');
$date = new DateControl('Pick a date', 'date');
$textarea = new TextAreaControl('Write something!', 'textarea');
$radio = new RadioButtonControl('Choose one', 'radio');

$text_control->setHint('Make sure to enter your full name.');
$text_control->setPlaceholder('Enter name');

$email_control->setPlaceholder('Enter email');
$password_control->setPlaceholder('Enter password');
$textarea->setPlaceholder('Start blabbing in here');

$email_control->setHint("We'll never share your email with anyone else.");
$password_control->setHint('Make sure to use something safe!');
$checkbox->setHint('Check out our terms and conditions!');

$text_control->setRequired(true);
$email_control->setRequired(true);
$password_control->setRequired(true);
$checkbox->setRequired(true);
$date->setRequired(true);
$textarea->setRequired(true);
$radio->setRequired(true);

$radio->addOption('Option 1', 'opt1', ['key' => 'value']);
$radio->addOption('Option 2', 'opt2', ['otherkey' => 'othervalue']);
$radio->addOption('Option 3', 'opt3', ['an', 'array']);

$section1->addControl($text_control);
$section1->addControl($email_control);
$section1->addControl($password_control);
$section1->addControl($checkbox);
$section2->addControl($date);
$section2->addControl($textarea);
$section2->addControl($radio);

$form->addSection($section1);
$form->addSection($section2);

$submit_button = new SubmitButton();

$submit_button->setSubmitCallback(function ($controls) {
    /** @var \FormBuilder\Controls\FormControl[] $controls */
    if ($controls['password']->getValue() === 'dummy')
        return new ErrorResponse('No.');

    return new SuccessResponse('You did it!');
});

$other = new SubmitButton('Do something else', 'other', 'plane', BootstrapClass::SECONDARY);

$other->setSubmitCallback(function ($controls) {
    return new ErrorResponse('This always does absolutely nothing!');
});

$form->addButton($submit_button);
$form->addButton($other);
$form->addButton(new RedirectButton('Cancel', 'https://www.google.com', BootstrapClass::LIGHT, 'ban'));

$form->init();

function parseVal($val)
{
    if ($val === null)
        return '<em>null</em>';
    if ($val === true)
        return '<b>true</b>';
    if ($val === false)
        return '<b>false</b>';
    if ($val instanceof DateTime)
        return $val->format('c');
    if (is_array($val))
        return json_encode($val);

    return $val;
}

?>

<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha256-NJWeQ+bs82iAeoT5Ktmqbi3NXwxcHlfaVejzJI2dklU=" crossorigin="anonymous"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha18/css/tempusdominus-bootstrap-4.min.css"
          integrity="sha256-9wLOlmGnL51taEbgcXqZQUq0taUCQy3UhwDdNJzsNnk=" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
          integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h1>Test</h1>

    <?php if (!empty($_POST)): ?>
        <h2>Raw POST Data</h2>
        <pre><?php var_dump($_POST) ?></pre>

        <h2>Values</h2>
        Form is submitted? <?= $form->isSubmitted() ? 'Yes' : 'No' ?><br/>
        Form has errors? <?= $form->hasError() ? 'Yes' : 'No' ?>
        <ul>
            <?php

            foreach ($form->getControls() as $control) {
                printf('<li>%s: %s</li>', $control->getName(), parseVal($control->getValue()));
            }

            ?>
        </ul>
    <?php endif; ?>

    <h2>Form</h2>
    <?php $form->render(); ?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha256-98vAGjEDGN79TjHkYWVD4s87rvWkdWLHPs5MC3FvFX4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"
        integrity="sha256-L3S3EDEk31HcLA5C6T2ovHvOcD80+fgqaCDt2BAi92o=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/locale/fr-ca.js"
        integrity="sha256-nuG99esmHpjj495vnUBhR7O15dVxTd2cWemlESaRX98=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha256-C8oQVJ33cKtnkARnmeWp6SDChkU+u7KvsNMFUzkkUzk=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha18/js/tempusdominus-bootstrap-4.min.js"
        integrity="sha256-8De73E/55v3s1x7gSEQ4pqpp+YgzggqakxdeXVsIjE0=" crossorigin="anonymous"></script>
<script src="../../dist/js/script.min.js"></script>
</body>
</html>

