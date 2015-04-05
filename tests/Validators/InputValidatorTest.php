<?php

use \Validator\InputValidator;

class InputValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Validator\InputValidator
     */
    protected $validator;

    protected function setUp()
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
        $this->validator = new InputValidator();
        $this->validator->setRules($rules);
    }

    protected function tearDown()
    {
        unset($this->validator);
    }

    /**
     * @covers \Validator\InputValidator::validate
     */
    public function testValidate()
    {
        // Arrange
        $input1 = [
            'email',
            'foo@bar.baz',
        ];
        $input2 = [
            'email',
            '',
        ];
        $input3 = [
            'email',
            'foo@bar',
        ];

        // Act
        for ($i = 1; $i <= 3; $i++) {
            ${'act' . $i} = $this->validator->validate(${'input' . $i});
        }

        // Assert
        $this->assertTrue($act1);
        $this->assertFalse($act2);
        $this->assertFalse($act3);
    }

    /**
     * @covers \Validator\InputValidator::validate
     */
    public function testValidateException()
    {
        // Arrange
        $input = [
            'message',
            'foo@bar.baz',
        ];

        // Act
        $actual = $this->validator->validate($input);

        // Assert
        $this->assertTrue($actual);
    }

    /**
     * @covers \Validator\InputValidator::fails
     */
    public function testFails()
    {
        // Arrange
        $inputs1 = [
            'email' => 'foo@bar.baz',
            'password' => 'quuuux',
        ];
        $inputs2 = [
            'email' => 'foo@bar',
            'password' => '',
        ];

        // Act
        for ($i = 1; $i <= 2; $i++) {
            ${'act' . $i} = $this->validator
                ->setInputs(${'inputs' . $i})
                ->fails();
        }

        // Assert
        $this->assertFalse($act1);
        $this->assertTrue($act2);
    }

    /**
     * @covers \Validator\InputValidator::getAllMessages
     */
    public function testGetAllMessages()
    {
        // Arrange
        $inputs = [
            'email' => 'foo@bar',
            'password' => '',
        ];

        $expected = [
            'email' => [
                'email must be email',
            ],
            'password' => [
                'password is required',
                'password must be bigger than 6'
            ],
        ];

        // Act
        $this->validator
            ->setInputs($inputs)
            ->fails();

        $actual = $this->validator->getAllMessages();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \Validator\InputValidator::getAllMessages
     */
    public function testGetMessages()
    {
        // Arrange
        $inputs = [
            'email' => 'foo@bar',
            'password' => '',
        ];

        $expected = [
            'password is required',
            'password must be bigger than 6'
        ];

        // Act
        $this->validator
            ->setInputs($inputs)
            ->fails();

        $actual = $this->validator->getMessages('password');

        // Assert
        $this->assertEquals($expected, $actual);
        $this->assertEquals([], $this->validator->getMessages('foo'));
    }
}
