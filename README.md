# Form and Validator

##Summary:

This is a package build as a test task. It implements form generator
and validator.

There is a BootstrapForm.php class can be used for generating bootstrap from.
FormValidator.php can be used as form internal validator.
InputValidator.php is for validating the raw input.

Using Abstract classes other types of forms and validators can be derived.

Package was written keeping in mind PSR.

##Examples

###1. Create simple form for registration

```php
// Stage Registration form
$registerForm = new \Validator\BootstrapForm('register', 'post');
$registerForm->addInputEmail(
    [
        'name' => 'email',
        'class' => 'form-control',
    ],
    'Email:'
)->addInputPassword(
    [
        'name' => 'password',
        'class' => 'form-control',
    ],
    'Password:'
)->addInputSubmit(
    [
        'name' => 'submit',
        'class' => 'btn btn-default',
    ]
)->setElementValue('submit', 'Register');

// Stage Registration validator
$registerFormValidator = new \Validator\FormValidator();
$rules = [
    'email' => 'required|email',
    'password' => 'required|min:6',
];
$registerFormValidator->setRules($rules);

$registerForm->setValidator($registerFormValidator);
```

###2. Create validator for raw input

```php
// Stage raw input validator
$registerInputValidator = new \Validator\InputValidator();
$rules = [
    'email' => 'required|email',
    'password' => 'required|min:6',
];
$registerInputValidator->setRules($rules);

$registerInputValidator->setInputs($_POST);

if (! $registerInputValidator->fails()) {
    return 'Input is valid!';
}

foreach ($registerInputValidator->getAllMessages() as $messages) {
    foreach ($messages as $message) {
        echo '<div class="alert alert-danger">', $message, '</div>';
    }
}
```
