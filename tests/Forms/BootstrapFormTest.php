<?php

use \Validator\BootstrapForm;
use \Validator\FormValidator;

class BootstrapFormTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Validator\BootstrapForm
     */
    protected $form;

    protected function setUp()
    {
        $method = 'POST';
        $action = 'register';
        $this->form = new BootstrapForm($action, $method);
    }

    protected function tearDown()
    {
        unset($this->form);
    }

    /**
     * @covers \Validator\BootstrapForm::addInput
     * @covers \Validator\BootstrapForm::getElementByName
     */
    public function testAddInput()
    {
        // Arrange
        $type = 'text';
        $attributes = [
            'name' => 'username',
        ];

        // Act
        $this->form->addInput($type, $attributes);
        $element = $this->form->getElementByName('username');

        // Assert
        $this->assertInstanceOf('\Validator\FormElements\Input', $element);
    }

    /**
     * @covers \Validator\BootstrapForm::getElementByName
     * @covers \Validator\Exceptions\NoFormElementWithGivenName
     *
     * @expectedException \Validator\Exceptions\NoFormElementWithGivenName
     */
    public function testGetElementByNameException()
    {
        // Arrange
        // Act
        $element = $this->form->getElementByName('username');
    }

    /**
     * @covers \Validator\BootstrapForm::addInput
     * @covers \Validator\BootstrapForm::setElementValue
     * @covers \Validator\BootstrapForm::getElementByName
     */
    public function testSetElementValue()
    {
        // Arrange
        $type = 'text';
        $attributes = [
            'name' => 'username',
        ];
        $expectedValue = 'john';

        // Act
        $this->form
            ->addInput($type, $attributes)
            ->setElementValue('username', 'john');

        $value = $this->form->getElementByName('username')->getValue();

        // Assert
        $this->assertEquals($expectedValue, $value);
    }

    /**
     * @covers \Validator\BootstrapForm::addTextarea
     * @covers \Validator\BootstrapForm::getElementByName
     */
    public function testAddTextarea()
    {
        // Arrange
        $attributes = [
            'name' => 'message',
        ];

        // Act
        $this->form->addTextarea($attributes);
        $element = $this->form->getElementByName('message');

        // Assert
        $this->assertInstanceOf('\Validator\FormElements\Textarea', $element);
    }

    /**
     * @covers \Validator\BootstrapForm::addSelect
     * @covers \Validator\BootstrapForm::getElementByName
     */
    public function testAddSelect()
    {
        // Arrange
        $attributes = [
            'name' => 'cars',
        ];
        $options = [
            'volvo' => 'Volvo',
            'saab' => 'Saab',
            'fiat' => 'Fiat',
            'audi' => 'Audi',
        ];

        // Act
        $this->form->addSelect($options, $attributes);
        $element = $this->form->getElementByName('cars');

        // Assert
        $this->assertInstanceOf('\Validator\FormElements\Select', $element);
    }

    /**
     * @covers \Validator\BootstrapForm::addButton
     * @covers \Validator\BootstrapForm::getElementByName
     */
    public function testAddButton()
    {
        // Arrange
        $attributes = [
            'name' => 'push',
        ];

        // Act
        $this->form->addButton($attributes);
        $element = $this->form->getElementByName('push');

        // Assert
        $this->assertInstanceOf('\Validator\FormElements\Button', $element);
    }

    /**
     * @covers \Validator\BootstrapForm::__call
     * @covers \Validator\BootstrapForm::addInput
     * @covers \Validator\BootstrapForm::getElementByName
     *
     * @uses \Validator\FormElements\Input::getType
     */
    public function testAddInputText()
    {
        // Arrange
        $attributes = [
            'name' => 'username',
        ];

        $expectedType = 'text';

        // Act
        $this->form->addInputText($attributes);
        $element = $this->form->getElementByName('username');
        $type = $element->getType();

        // Assert
        $this->assertEquals($expectedType, $type);
    }

    /**
     * @covers \Validator\BootstrapForm::__call
     * @covers \Validator\BootstrapForm::addInput
     * @covers \Validator\BootstrapForm::getElementByName
     *
     * @uses \Validator\FormElements\Input::getType
     */
    public function testAddInputEmail()
    {
        // Arrange
        $attributes = [
            'name' => 'email',
        ];

        $expectedType = 'email';

        // Act
        $this->form->addInputEmail($attributes);
        $element = $this->form->getElementByName('email');
        $type = $element->getType();

        // Assert
        $this->assertEquals($expectedType, $type);
    }

    /**
     * @covers \Validator\BootstrapForm::__call
     * @covers \Validator\BootstrapForm::addInput
     * @covers \Validator\BootstrapForm::getElementByName
     *
     * @uses \Validator\FormElements\Input::getType
     */
    public function testAddInputPassword()
    {
        // Arrange
        $attributes = [
            'name' => 'password',
        ];

        $expectedType = 'password';

        // Act
        $this->form->addInputPassword($attributes);
        $element = $this->form->getElementByName('password');
        $type = $element->getType();

        // Assert
        $this->assertEquals($expectedType, $type);
    }

    /**
     * @covers \Validator\BootstrapForm::__call
     * @covers \Validator\BootstrapForm::addInput
     * @covers \Validator\BootstrapForm::getElementByName
     *
     * @uses \Validator\FormElements\Input::getType
     */
    public function testAddInputSubmit()
    {
        // Arrange
        $attributes = [
            'name' => 'submit',
            'value' => 'Submit',
        ];

        $expectedType = 'submit';

        // Act
        $this->form->addInputSubmit($attributes);
        $element = $this->form->getElementByName('submit');
        $type = $element->getType();

        // Assert
        $this->assertEquals($expectedType, $type);
    }

    /**
     * @covers \Validator\BootstrapForm::__call
     * @covers \Validator\BootstrapForm::addInput
     * @covers \Validator\BootstrapForm::getElementByName
     *
     * @uses \Validator\FormElements\Input::getType
     */
    public function testAddInputRadio()
    {
        // Arrange
        $attributes = [
            'name' => 'sex',
            'value' => 'male',
        ];

        $expectedType = 'radio';

        // Act
        $this->form->addInputRadio($attributes);
        $element = $this->form->getElementByName('sex');
        $type = $element->getType();

        // Assert
        $this->assertEquals($expectedType, $type);
    }

    /**
     * @covers \Validator\BootstrapForm::__call
     * @covers \Validator\BootstrapForm::addInput
     * @covers \Validator\BootstrapForm::getElementByName
     *
     * @uses \Validator\FormElements\Input::getType
     */
    public function testAddInputCheckbox()
    {
        // Arrange
        $attributes = [
            'name' => 'vehicle',
            'value' => 'Bike',
        ];

        $expectedType = 'checkbox';

        // Act
        $this->form->addInputCheckbox($attributes);
        $element = $this->form->getElementByName('vehicle');
        $type = $element->getType();

        // Assert
        $this->assertEquals($expectedType, $type);
    }

    /**
     * @covers \Validator\BootstrapForm::__call
     * @covers \Validator\BootstrapForm::addInput
     * @covers \Validator\BootstrapForm::getElementByName
     *
     * @uses \Validator\FormElements\Input::getType
     */
    public function testAddInputButton()
    {
        // Arrange
        $attributes = [
            'name' => 'button',
            'onclick' => 'alert(\'Hello World!\')',
            'value' => 'Click Me!',
        ];

        $expectedType = 'button';

        // Act
        $this->form->addInputButton($attributes);
        $element = $this->form->getElementByName('button');
        $type = $element->getType();

        // Assert
        $this->assertEquals($expectedType, $type);
    }

    /**
     * @covers \Validator\BootstrapForm::__call
     *
     * @expectedException \BadMethodCallException
     */
    public function testAddInputException()
    {
        // Arrange
        $attributes = [];

        // Act
        $this->form->addInputFoo($attributes);
    }

    /**
     * @covers \Validator\BootstrapForm::setValidator
     *
     * @uses \Validator\Abstracts\AbstractValidator
     */
    public function testSetValidator()
    {
        // Arrange
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
        $expected = new FormValidator();
        $expected->setRules($rules);

        // Act
        $this->form->setValidator($expected);

        $validator = new \ReflectionObject($this->form);
        $property = $validator->getProperty('validator');
        $property->setAccessible(true);
        $actual = $property->getValue($this->form);

        // Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \Validator\BootstrapForm::addInput
     * @covers \Validator\BootstrapForm::setValidator
     * @covers \Validator\BootstrapForm::validate
     *
     * @uses \Validator\Abstracts\AbstractValidator
     */
    public function testValidatePasses()
    {
        // Arrange
        $this->form->addInputEmail([
            'name' => 'email',
            'value' => 'foo@bar.baz',
        ]);
        $this->form->addInputPassword([
            'name' => 'password',
            'value' => 'quux',
        ]);

        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
        $validator = new FormValidator();
        $validator->setRules($rules);

        $this->form->setValidator($validator);

        // Act
        $valid = $this->form->validate();

        // Assert
        $this->assertTrue($valid);
    }

    /**
     * @covers \Validator\BootstrapForm::addInput
     * @covers \Validator\BootstrapForm::setValidator
     * @covers \Validator\BootstrapForm::validate
     *
     * @uses \Validator\Abstracts\AbstractValidator
     */
    public function testValidateFails()
    {
        // Arrange
        $this->form->addInputEmail([
            'name' => 'email',
            'value' => 'foo@bar',
        ]);
        $this->form->addInputPassword([
            'name' => 'password',
            'value' => 'quux',
        ]);

        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
        $validator = new FormValidator();
        $validator->setRules($rules);

        $this->form->setValidator($validator);

        // Act
        $valid = $this->form->validate();

        // Assert
        $this->assertFalse($valid);
    }

    /**
     * @covers \Validator\BootstrapForm::addInput
     * @covers \Validator\BootstrapForm::__toString
     */
    public function testFormRendering()
    {
        // Arrange
        $this->form->addInputEmail([
            'name' => 'email',
            'value' => 'foo@bar.baz',
        ]);
        $this->form->addInputPassword([
            'name' => 'password',
            'value' => 'quux',
        ]);
        $this->form->addInputSubmit([
            'name' => 'submit',
            'value' => 'Register',
        ]);

        $expected = '<form action="register" method="POST">';
        $expected .= '<div class="form-group">';
        $expected .= '<input type="email" name="email" value="foo@bar.baz">';
        $expected .= '</div>';
        $expected .= '<div class="form-group">';
        $expected .= '<input type="password" name="password" value="quux">';
        $expected .= '</div><div class="form-group">';
        $expected .= '<input type="submit" name="submit" value="Register">';
        $expected .= '</div>';
        $expected .= '</form>';

        // Act
        $actual = (string) $this->form;

        // Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \Validator\BootstrapForm::addInput
     * @covers \Validator\BootstrapForm::setValidator
     * @covers \Validator\BootstrapForm::validate
     * @covers \Validator\BootstrapForm::__toString
     *
     * @uses \Validator\Abstracts\AbstractValidator
     */
    public function testFormRenderingWithErrors()
    {
        // Arrange
        $this->form->addInputEmail([
            'name' => 'email',
            'value' => 'foo@bar',
        ]);
        $this->form->addInputPassword([
            'name' => 'password',
            'value' => 'quux',
        ]);
        $this->form->addInputSubmit([
            'name' => 'submit',
            'value' => 'Register',
        ]);

        $expected = '<form action="register" method="POST">';
        $expected .= '<div class="form-group has-error">';
        $expected .= '<input type="email" name="email" value="foo@bar">';
        $expected .= '<span class="help-block">email must be email</span>';
        $expected .= '</div>';
        $expected .= '<div class="form-group has-error">';
        $expected .= '<input type="password" name="password" value="quux">';
        $expected .= '<span class="help-block">password must be bigger than 6</span>';
        $expected .= '</div>';
        $expected .= '<div class="form-group">';
        $expected .= '<input type="submit" name="submit" value="Register">';
        $expected .= '</div>';
        $expected .= '</form>';

        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
        $validator = new FormValidator();
        $validator->setRules($rules);
        $this->form->setValidator($validator);

        $this->form->validate();

        // Act
        $actual = (string) $this->form;

        // Assert
        $this->assertEquals($expected, $actual);
    }
}
