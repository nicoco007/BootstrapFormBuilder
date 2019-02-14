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
use FormBuilder\ButtonStyle;
use FormBuilder\Controls\CheckboxControl;
use FormBuilder\Controls\DateControl;
use FormBuilder\Controls\EmailControl;
use FormBuilder\Controls\HiddenControl;
use FormBuilder\Controls\NumberControl;
use FormBuilder\Controls\PasswordControl;
use FormBuilder\Controls\PhoneNumberControl;
use FormBuilder\Controls\RadioButtonControl;
use FormBuilder\Controls\SelectControl;
use FormBuilder\Controls\TextAreaControl;
use FormBuilder\Controls\TextControl;
use FormBuilder\ErrorResponse;
use FormBuilder\Form;
use FormBuilder\FormSection;
use FormBuilder\RedirectButton;
use FormBuilder\RedirectResponse;
use FormBuilder\SubmitButton;
use FormBuilder\SuccessResponse;

$form = new Form('form', 'post', 'Form');

$form->setLocale('fr-ca');

$section1 = new FormSection('Section I');
$section2 = new FormSection('Section II');

$text_control = new TextControl('Name', 'name');
$text2 = new TextControl('Name', 'name2');
$email_control = new EmailControl('Email Address', 'email');
$password_control = new PasswordControl('Password', 'password');
$checkbox = new CheckboxControl('Check this out', 'checkbox');
$date = new DateControl('Pick a date', 'date');
$date2 = new DateControl('Date with min/max', 'date2');
$textarea = new TextAreaControl('Write something!', 'textarea');
$radio = new RadioButtonControl('Choose one', 'radio');
$select = new SelectControl('Select a thing', 'select');
$radio2 = new RadioButtonControl('Radio with default', 'radio2');
$select2 = new SelectControl('Select with default', 'select2');
$tel = new PhoneNumberControl('Phone number', 'tel');
$number = new NumberControl('Number', 'num');

$number->setIcon('dollar-sign');
$number->setMin(0);
$number->setMax(100);
$number->setStep(0.05);

$text_control->setPlaceholder('Enter name');
$email_control->setPlaceholder('Enter email');
$password_control->setPlaceholder('Enter password');
$textarea->setPlaceholder('Start blabbing in here');

$text_control->setHint('Make sure to enter your full name.');
$email_control->setHint("We'll never share your email with anyone else.");
$password_control->setHint('Make sure to use something safe!');
$checkbox->setHint('Check out our terms and conditions!');
$date->setHint('It can be any date you want!');
$textarea->setHint('Don\'t write a novel, though!');
$radio->setHint('Pick one');
$select->setHint('Pick another one');

$text_control->setRequired(true);
$email_control->setRequired(true);
$password_control->setRequired(true);
$checkbox->setRequired(true);
$date->setRequired(true);
$textarea->setRequired(true);
$radio->setRequired(true);
$select->setRequired(true);

$date2->setMinDate((new DateTime())->sub(new DateInterval("P7D")));
$date2->setMaxDate((new DateTime())->add(new DateInterval("P7D")));

$radio->addOption('Option 1', 'opt1', ['key' => 'value']);
$radio->addOption('Option 2', 'opt2', ['otherkey' => 'othervalue']);
$radio->addOption('Option 3', 'opt3', ['an', 'array']);

$select->setLiveSearch(true);

for ($i = 0; $i < 100; $i++)
    $select->addOption('Option ' . ($i + 1), (string) $i);

$radio2->addOption('Option 1', 'opt1', ['key' => 'value']);
$radio2->addOption('Option 2', 'opt2', ['otherkey' => 'othervalue'], true);
$radio2->addOption('Option 3', 'opt3', ['an', 'array']);

$select2->addOption('Option 1', 'opt1', ['something' => 'something else']);
$select2->addOption('Option 2', 'opt2', ['something else' => 'something']);
$select2->addOption('Option 3', 'opt3', ['not', 'an', 'associative', 'array'], true);

$tel->setInitialCountry('CA');
$tel->setPreferredCountries(['CA', 'US']);

$text2->setMaxLength(6);
$text2->setRegexString('/^[A-Z][0-9][A-Z] ?[0-9][A-Z][0-9]$/i');

$radio->addChild($text_control, ['key' => 'value']);
$radio->addChild($email_control, ['otherkey' => 'othervalue']);
$select->addChild($password_control);
$select2->addChild($checkbox);
$checkbox->addChild($date, true);
$section1->addControl($date2);
$section1->addControl($textarea);
$section1->addControl($radio);
$section2->addControl($select);
$section2->addControl($radio2);
$section2->addControl($select2);
$section2->addControl($tel);
$section2->addControl($text2);
$section2->addControl($text2);
$section2->addControl($number);

$section3 = new FormSection('Section III');
$section3->setOrder(-1);

$radio3 = new RadioButtonControl('Root', 'root');
$textarea2 = new TextAreaControl('Child 1', 'child1');
$radio4 = new RadioButtonControl('Child 2', 'child2');
$radio5 = new RadioButtonControl('Child 3', 'child3');
$textarea3 = new TextAreaControl('Child 4', 'child4');

$radio3->addOption('Yes', 'yes');
$radio3->addOption('No', 'no');
$radio4->addOption('Yes', 'yes');
$radio4->addOption('No', 'no');
$radio5->addOption('Yes', 'yes');
$radio5->addOption('No', 'no');

$radio3->setRequired(true);
$textarea2->setRequired(true);
$radio4->setRequired(true);
$radio5->setRequired(true);
$textarea3->setRequired(true);

$radio5->addChild($textarea3);
$radio4->addChild($radio5);
$radio3->addChild($textarea2);
$radio3->addChild($radio4);

$section3->addControl($radio3);

$radio->setColumnSpan(2);

$password_control->setShowPasswordStrength(true);

$section1->setColumnCount(2);
$section2->setColumnCount(3);

$form->addSection($section1);
$form->addSection($section2);
$form->addSection($section3);

$form->addHiddenValue('hidden', 'garfunkel');

$submit_button = new SubmitButton();

$submit_button->setSubmitCallback(function ($values) {
    /** @var \FormBuilder\Controls\FormControl[] $controls */
    if ($values['password'] === 'dummy')
        return new ErrorResponse('No.');
    elseif ($values['name'] === 'redirectme')
        return new RedirectResponse('https://www.google.com');

    return new SuccessResponse('You did it!');
});

$other = new SubmitButton('Do something else', 'other', 'plane', BootstrapClass::SECONDARY);

$other->setSubmitCallback(function () {
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha256-eSi1q2PG6J7g7ib17yAaWMcrr5GrtohYChqibrV7PBE=" crossorigin="anonymous" />
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha18/css/tempusdominus-bootstrap-4.min.css"
          integrity="sha256-9wLOlmGnL51taEbgcXqZQUq0taUCQy3UhwDdNJzsNnk=" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
          integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"
          integrity="sha256-sJQnfQcpMXjRFWGNJ9/BWB1l6q7bkQYsRqToxoHlNJY=" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.15/css/intlTelInput.css"
          integrity="sha256-Q35kn/SM+AW5mosKvh9cdofWZ2XZQECPFULVWv4LB6U=" crossorigin="anonymous"/>
    <link rel="stylesheet" href="../../dist/css/style.min.css"/>
    <script src='https://www.google.com/recaptcha/api.js'></script>
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

            foreach ($form->getControls(true) as $control) {
                printf('<li>%s: %s</li>', $control->getName(), parseVal($control->getValue()));
            }

            ?>
        </ul>
    <?php endif; ?>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha256-VsEqElsCHSGmnmHXGQzvoWjWwoznFSZc6hs7ARLRacQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha18/js/tempusdominus-bootstrap-4.min.js"
        integrity="sha256-8De73E/55v3s1x7gSEQ4pqpp+YgzggqakxdeXVsIjE0=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.15/js/intlTelInput.min.js"
        integrity="sha256-DU7VFVkQvkOiAFq6ovT5dnQFcZVeXnFnfmTVirFCsAw=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.15/js/utils.js"
        integrity="sha256-+iMZzfetfvKzWUvuUAGnNmowUrc1d11Y+JWx1cHfI8Y=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"
        integrity="sha256-Znf8FdJF85f1LV0JmPOob5qudSrns8pLPZ6qkd/+F0o=" crossorigin="anonymous"></script>
<script src="../../dist/js/script.min.js"></script>
<script src="../../dist/js/locale/fr.js"></script>
</body>
</html>

