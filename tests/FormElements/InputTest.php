<?php

use \Validator\FormElements\Input;

class InputTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Validator\FormElements\Input::defineType
     * @covers \Validator\FormElements\Input::renderField
     */
    public function testEmailInput()
    {
        // Arrange
        $attributes = [
            'name' => 'email',
        ];
        $input = new Input($attributes);
        $input->defineType('email');
        $expected = '<input type="email" name="email">';

        // Act
        $actual = $input->renderField();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \Validator\FormElements\Input::defineType
     * @covers \Validator\FormElements\Input::getType
     */
    public function testGetType()
    {
        // Arrange
        $attributes = [
            'name' => 'email',
        ];
        $input = new Input($attributes);
        $input->defineType('email');
        $expectedType = 'email';

        // Act
        $type = $input->getType();

        // Assert
        $this->assertEquals($expectedType, $type);
    }

    /**
     * @covers \Validator\FormElements\Input::defineType
     * @covers \Validator\FormElements\Input::renderField
     * @covers \Validator\FormElements\Input::renderLabel
     */
    public function testEmailInputWithLabel()
    {
        // Arrange
        $attributes = [
            'name' => 'username',
        ];
        $label = 'Username';
        $input = new Input($attributes, $label);
        $input->defineType('text');
        $expectedLabel = '<label for="username">Username</label>';
        $expectedField = '<input type="text" name="username">';

        // Act
        $label = $input->renderLabel();
        $field = $input->renderField();

        // Assert
        $this->assertEquals($expectedField, $field);
        $this->assertEquals($expectedLabel, $label);
    }

    /**
     * @covers \Validator\FormElements\Input::renderLabel
     */
    public function testRenderLabelFalseIfNoLabel()
    {
        // Arrange
        $attributes = [
            'name' => 'username',
        ];
        $input = new Input($attributes);

        // Act
        $label = $input->renderLabel();

        // Assert
        $this->assertFalse($label);
    }

    /**
     * @covers \Validator\FormElements\Input::getValue
     */
    public function testGetValue()
    {
        // Arrange
        $attributes = [
            'name' => 'username',
            'value' => 'john',
        ];
        $input = new Input($attributes);
        $expectedValue = 'john';

        // Act
        $value = $input->getValue();

        // Assert
        $this->assertEquals($expectedValue, $value);
    }

    /**
     * @covers \Validator\FormElements\Input::getValue
     */
    public function testGetValueEmpty()
    {
        // Arrange
        $attributes = [
            'name' => 'username',
        ];
        $input = new Input($attributes);
        $expectedValue = '';

        // Act
        $value = $input->getValue();

        // Assert
        $this->assertEquals($expectedValue, $value);
    }

    /**
     * @covers \Validator\FormElements\Input::setValue
     * @covers \Validator\FormElements\Input::getValue
     */
    public function testSetValue()
    {
        // Arrange
        $attributes = [
            'name' => 'username',
        ];
        $input = new Input($attributes);
        $input->setValue('john');
        $expectedValue = 'john';

        // Act
        $value = $input->getValue();

        // Assert
        $this->assertEquals($expectedValue, $value);
    }

    /**
     * @covers \Validator\FormElements\Input::getName
     */
    public function testGetName()
    {
        // Arrange
        $attributes = [
            'name' => 'username',
        ];
        $input = new Input($attributes);
        $expectedName = 'username';

        // Act
        $name = $input->getName();

        // Assert
        $this->assertEquals($expectedName, $name);
    }

    /**
     * @covers \Validator\FormElements\Input::getName
     * @covers \Validator\Exceptions\FormElementHasNoNameException
     *
     * @expectedException \Validator\Exceptions\FormElementHasNoNameException
     */
    public function testFormElementHasNoNameException()
    {
        // Arrange
        $attributes = [];
        $input = new Input($attributes);

        // Act
        $name = $input->getName();
    }

    /**
     * @covers \Validator\FormElements\Input::addError
     * @covers \Validator\FormElements\Input::getErrors
     */
    public function testAddError()
    {
        // Arrange
        $attributes = [
            'name' => 'email',
        ];
        $input = new Input($attributes);
        $error = 'Must be valid email';

        // Act
        $input->addError($error);
        $errors = $input->getErrors();

        // Assert
        $this->assertContains($error, $errors);
    }

    /**
     * @covers \Validator\FormElements\Input::addError
     * @covers \Validator\FormElements\Input::getErrors
     */
    public function testGetErrors()
    {
        // Arrange
        $attributes = [
            'name' => 'password',
        ];
        $input = new Input($attributes);
        $error1 = 'Must be at least 6 characters long';
        $error2 = 'Must contain letters and numbers';

        // Act
        $input->addError($error1)
            ->addError($error2);

        $errors = $input->getErrors();

        // Assert
        $this->assertCount(2, $errors);
        $this->assertContains($error1, $errors);
        $this->assertContains($error2, $errors);
    }

    /**
     * @covers \Validator\FormElements\Input::getErrors
     */
    public function testNoErrors()
    {
        // Arrange
        $attributes = [
            'name' => 'email',
        ];
        $input = new Input($attributes);

        // Act
        $errors = $input->getErrors();

        // Assert
        $this->assertEmpty($errors);
    }
}
