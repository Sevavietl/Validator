<?php

use \Validator\FormValidator;
use \Validator\FormElements\Input;

class FormValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Validator\FormValidator
     */
    protected $validator;

    protected function setUp()
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
        $this->validator = new FormValidator();
        $this->validator->setRules($rules);
    }

    protected function tearDown()
    {
        unset($this->validator);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @covers \Validator\FormValidator::composeMessage
     */
    public function testComposeMessage()
    {
        // Arrange
        $attribute = 'some_input';

        $rule1 = 'required';
        $parameters1 = null;

        $rule2 = 'boolean';
        $parameters2 = null;

        $rule3 = 'numeric';
        $parameters3 = null;

        $rule4 = 'integer';
        $parameters4 = null;

        $rule5 = 'between';
        $parameters5 = [2, 5];

        $rule6 = 'min';
        $parameters6 = [1];

        $rule7 = 'max';
        $parameters7 = [5];

        $rule8 = 'email';
        $parameters8 = null;

        $rule9 = 'url';
        $parameters9 = null;

        $rule10 = 'regex';
        $parameters10 = ['/^*/'];

        $expected1 = 'some_input is required';
        $expected2 = 'some_input must be boolean';
        $expected3 = 'some_input must be numeric';
        $expected4 = 'some_input must be integer';
        $expected5 = 'some_input must be between 2 and 5';
        $expected6 = 'some_input must be bigger than 1';
        $expected7 = 'some_input must be lesser than 5';
        $expected8 = 'some_input must be email';
        $expected9 = 'some_input must be url';
        $expected10 = 'some_input not matches regex "/^*/"';

        // Act
        for ($i = 1; $i <= 10; $i++) {
            ${'act' . $i} = $this->invokeMethod(
                $this->validator,
                'composeMessage',
                [${'rule' . $i}, $attribute, ${'parameters' . $i}]
            );

            // Assert
            $this->assertEquals(${'expected' . $i}, ${'act' . $i});
        }
    }

    /**
     * @covers \Validator\FormValidator::setInputs
     */
    public function testSetInputs()
    {
        // Arrange
        $inputs = [
            (new Input(['name' => 'email']))->defineType('email'),
            (new Input(['name' => 'password']))->defineType('password'),
        ];

        // Act
        $this->validator->setInputs($inputs);

        $validator = new \ReflectionObject($this->validator);
        $property = $validator->getProperty('inputs');
        $property->setAccessible(true);
        $actual = $property->getValue($this->validator);

        // Assert
        $this->assertEquals($inputs, $actual);
    }

    /**
     * @covers \Validator\FormValidator::validate
     */
    public function testValidate()
    {
        // Arrange
        $input1 = (new Input([
            'name' => 'email',
            'value' => 'foo@bar.baz'
        ]))->defineType('email');
        $input2 = (new Input([
            'name' => 'email',
            'value' => ''
        ]))->defineType('email');
        $input3 = (new Input([
            'name' => 'email',
            'value' => 'foo@bar'
        ]))->defineType('email');

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
     * @covers \Validator\FormValidator::validate
     */
    public function testValidateException()
    {
        // Arrange
        $input = (new Input([
            'name' => 'message',
            'value' => 'foo@bar.baz'
        ]))->defineType('email');

        // Act
        $actual = $this->validator->validate($input);

        // Assert
        $this->assertTrue($actual);
    }

    /**
     * @covers \Validator\FormValidator::fails
     */
    public function testFails()
    {
        // Arrange
        $inputs1 = [
            (new Input([
                'name' => 'email',
                'value' => 'foo@bar.baz'
            ]))->defineType('email'),
            (new Input([
                'name' => 'password',
                'value' => 'quuuux'
            ]))->defineType('password'),
        ];
        $inputs2 = [
            (new Input([
                'name' => 'email',
                'value' => 'foo@bar'
            ]))->defineType('email'),
            (new Input([
                'name' => 'password',
                'value' => ''
            ]))->defineType('password'),
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
     * @covers \Validator\FormValidator::setRules
     * @covers \Validator\FormValidator::getRules
     */
    public function setRules()
    {
        // Arrange
        $expected = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];

        // Act
        $this->validator->setRules($expected);
        $rules = $this->validator->getRules();

        // Assert
        $this->assertEquals($expected, $rules);
    }

    /**
     * @covers \Validator\FormValidator::getRules
     */
    public function testGetRules()
    {
        // Arrange
        $expected = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];

        // Act
        $rules = $this->validator->getRules();

        // Assert
        $this->assertEquals($expected, $rules);
    }

    /**
     * @covers \Validator\FormValidator::parseRules
     */
    public function testParseRulesString()
    {
        // Arrange
        $rules = 'required|email';
        $expectedRules = [
            'required',
            'email'
        ];

        // Act
        $rules = $this->invokeMethod(
            $this->validator,
            'parseRules',
            [$rules]
        );

        // Assert
        $this->assertEquals($expectedRules, $rules);
    }

    /**
     * @covers \Validator\FormValidator::parseRules
     */
    public function testParseRulesArray()
    {
        // Arrange
        $rules = [
            'required' => '',
            'email' => '',
        ];
        $expectedRules = [
            [
                'required' => '',
                'email' => '',
            ]
        ];

        // Act
        $rules = $this->invokeMethod(
            $this->validator,
            'parseRules',
            [$rules]
        );

        // Assert
        $this->assertEquals($expectedRules, $rules);
    }

    /**
     * @covers \Validator\FormValidator::parseRule
     */
    public function testParseRule()
    {
        // Arrange
        $rule1 = ['min' => 3];
        $rule2 = 'between:1:2';
        $rule3 = 'regex:<.*?>';

        $expected1 = ['min', [3]];
        $expected2 = ['between', [1, 2]];
        $expected3 = ['regex', ['<.*?>']];

        // Act
        for ($i = 1; $i <= 3; $i++) {
            ${'rule' . $i} = $this->invokeMethod(
                $this->validator,
                'parseRule',
                [${'rule' . $i}]
            );
        }

        // Assert
        $this->assertEquals($expected1, $rule1);
        $this->assertEquals($expected2, $rule2);
        $this->assertEquals($expected3, $rule3);
    }

    /**
     * @covers \Validator\FormValidator::parseRule
     */
    public function testParseRuleNotComplex()
    {
        // Arrange
        $rule = 'required';

        $expected = ['required', []];

        // Act
        $rule = $this->invokeMethod(
            $this->validator,
            'parseRule',
            [$rule]
        );

        // Assert
        $this->assertEquals($expected, $rule);
    }

    /**
     * @covers \Validator\FormValidator::parseRule
     *
     * @expectedException \Validator\Exceptions\BadRuleDeclarationException
     */
    public function testParseRuleThrowsException1()
    {
        // Arrange
        $rule = 'min:';

        // Act
        $rule = $this->invokeMethod(
            $this->validator,
            'parseRule',
            [$rule]
        );
    }

    /**
     * @covers \Validator\FormValidator::parseRule
     *
     * @expectedException \Validator\Exceptions\BadRuleDeclarationException
     */
    public function testParseRuleThrowsException2()
    {
        // Arrange
        $rule = ':';

        // Act
        $rule = $this->invokeMethod(
            $this->validator,
            'parseRule',
            [$rule]
        );
    }

    /**
     * @covers \Validator\FormValidator::parseRule
     *
     * @expectedException \Validator\Exceptions\BadRuleDeclarationException
     */
    public function testParseRuleThrowsException3()
    {
        // Arrange
        $rule = 'between::3';

        // Act
        $rule = $this->invokeMethod(
            $this->validator,
            'parseRule',
            [$rule]
        );
    }

    /**
     * @covers \Validator\FormValidator::validateRequired
     */
    public function testValidateRequired()
    {
        // Arrange
        $value1 = 'john';
        $value2 = null;
        $value3 = '';
        $value4 = [1, 2];
        $value5 = [];

        // Act
        for ($i = 1; $i <= 5; $i++) {
            ${'act' . $i} = $this->invokeMethod(
                $this->validator,
                'validateRequired',
                [${'value' . $i}]
            );
        }

        // Assert
        $this->assertTrue($act1);
        $this->assertFalse($act2);
        $this->assertFalse($act3);
        $this->assertTrue($act4);
        $this->assertFalse($act5);
    }

    /**
     * @covers \Validator\FormValidator::validateBoolean
     */
    public function testValidateBoolean()
    {
        // Arrange
        $value1 = true;
        $value2 = false;
        $value3 = 1;
        $value4 = 0;
        $value5 = '1';
        $value6 = '0';

        $value7 = 'foo';
        $value8 = 'bar';
        $value9 = 'null';

        // Act
        for ($i = 1; $i <= 9; $i++) {
            ${'act' . $i} = $this->invokeMethod(
                $this->validator,
                'validateBoolean',
                [${'value' . $i}]
            );
        }

        // Assert
        $this->assertTrue($act1);
        $this->assertTrue($act2);
        $this->assertTrue($act3);
        $this->assertTrue($act4);
        $this->assertTrue($act5);
        $this->assertTrue($act6);
        $this->assertFalse($act7);
        $this->assertFalse($act9);
        $this->assertFalse($act8);
    }

    /**
     * @covers \Validator\FormValidator::validateNumeric
     */
    public function testValidateNumeric()
    {
        // Arrange
        $value1 = '123';
        $value2 = 123;
        $value3 = 'abc';
        $value4 = null;
        $value5 = '12.3';
        $value6 = '12,3';

        // Act
        for ($i = 1; $i <= 6; $i++) {
            ${'act' . $i} = $this->invokeMethod(
                $this->validator,
                'validateNumeric',
                [${'value' . $i}]
            );
        }

        // Assert
        $this->assertTrue($act1);
        $this->assertTrue($act2);
        $this->assertFalse($act3);
        $this->assertFalse($act4);
        $this->assertTrue($act5);
        $this->assertFalse($act6);
    }

    /**
     * @covers \Validator\FormValidator::validateInteger
     */
    public function testValidateInteger()
    {
        // Arrange
        $value1 = '123';
        $value2 = 123;
        $value3 = 'abc';
        $value4 = '12.3';

        // Act
        for ($i = 1; $i <= 4; $i++) {
            ${'act' . $i} = $this->invokeMethod(
                $this->validator,
                'validateInteger',
                [${'value' . $i}]
            );
        }

        // Assert
        $this->assertTrue($act1);
        $this->assertTrue($act2);
        $this->assertFalse($act3);
        $this->assertFalse($act4);
    }

    /**
     * @covers \Validator\FormValidator::getStringSize
     */
    public function testGetStringSize()
    {
        // Arrange
        $string1 = 'foo';
        $string2 = '';

        $size1 = 3;
        $size2 = 0;

        // Act
        for ($i = 1; $i <= 2; $i++) {
            ${'act' . $i} = $this->invokeMethod(
                $this->validator,
                'getStringSize',
                [${'string' . $i}]
            );

            // Assert
            $this->assertEquals(${'size' . $i}, ${'act' . $i});
        }
    }

    /**
     * @covers \Validator\FormValidator::getSize
     */
    public function testGetSize()
    {
        // Arrange
        $value1 = 'foo';
        $value2 = '';
        $value3 = [];
        $value4 = [1];
        $value5 = ['foo', 'bar'];

        $size1 = 3;
        $size2 = 0;
        $size3 = 0;
        $size4 = 1;
        $size5 = 2;

        // Act
        for ($i = 1; $i <= 2; $i++) {
            ${'act' . $i} = $this->invokeMethod(
                $this->validator,
                'getSize',
                [${'value' . $i}]
            );

            // Assert
            $this->assertEquals(${'size' . $i}, ${'act' . $i});
        }
    }

    /**
     * @covers \Validator\FormValidator::validateMin
     */
    public function testValidateMin()
    {
        // Arrange
        $parameters = [2];

        $value1 = 'foo';
        $value2 = 'f';
        $value3 = 3;
        $value4 = 1;
        $value5 = [];
        $value6 = ['foo', 'bar'];

        // Act
        for ($i = 1; $i <= 6; $i++) {
            ${'act' . $i} = $this->invokeMethod(
                $this->validator,
                'validateMin',
                [${'value' . $i}, $parameters]
            );
        }

        // Assert
        $this->assertTrue($act1);
        $this->assertFalse($act2);
        $this->assertTrue($act3);
        $this->assertFalse($act4);
        $this->assertFalse($act5);
        $this->assertTrue($act6);
    }

    /**
     * @covers \Validator\FormValidator::validateMax
     */
    public function testValidateMax()
    {
        // Arrange
        $parameters = [3];

        $value1 = 'foo';
        $value2 = 'foobar';
        $value3 = 3;
        $value4 = 5;
        $value5 = ['foo', 'bar'];
        $value6 = [1, 2, 3, 4, 5, 6];

        // Act
        for ($i = 1; $i <= 6; $i++) {
            ${'act' . $i} = $this->invokeMethod(
                $this->validator,
                'validateMax',
                [${'value' . $i}, $parameters]
            );
        }

        // Assert
        $this->assertTrue($act1);
        $this->assertFalse($act2);
        $this->assertTrue($act3);
        $this->assertFalse($act4);
        $this->assertTrue($act5);
        $this->assertFalse($act6);
    }

    /**
     * @covers \Validator\FormValidator::validateBetween
     */
    public function testValidateBetween()
    {
        // Arrange
        $parameters = [2, 5];

        $value1 = 'foo';
        $value2 = 'foobar';
        $value3 = 'f';
        $value4 = 3;
        $value5 = 6;
        $value6 = 1;
        $value7 = ['foo', 'bar'];
        $value8 = [1, 2, 3, 4, 5, 6];
        $value9 = [1];

        // Act
        for ($i = 1; $i <= 9; $i++) {
            ${'act' . $i} = $this->invokeMethod(
                $this->validator,
                'validateBetween',
                [${'value' . $i}, $parameters]
            );
        }

        // Assert
        $this->assertTrue($act1);
        $this->assertFalse($act2);
        $this->assertFalse($act3);
        $this->assertTrue($act4);
        $this->assertFalse($act5);
        $this->assertFalse($act6);
        $this->assertTrue($act7);
        $this->assertFalse($act8);
        $this->assertFalse($act8);
    }

    /**
     * @covers \Validator\FormValidator::validateEmail
     */
    public function testValidateEmail()
    {
        // Arrange
        $value1 = 'foo@bar.baz';
        $value2 = 'foobar';

        // Act
        for ($i = 1; $i <= 2; $i++) {
            ${'act' . $i} = $this->invokeMethod(
                $this->validator,
                'validateEmail',
                [${'value' . $i}]
            );
        }

        // Assert
        $this->assertTrue($act1);
        $this->assertFalse($act2);
    }

    /**
     * @covers \Validator\FormValidator::validateUrl
     */
    public function testValidateUrl()
    {
        // Arrange
        $value1 = 'http://bar.baz';
        $value2 = 'foobar';

        // Act
        for ($i = 1; $i <= 2; $i++) {
            ${'act' . $i} = $this->invokeMethod(
                $this->validator,
                'validateUrl',
                [${'value' . $i}]
            );
        }

        // Assert
        $this->assertTrue($act1);
        $this->assertFalse($act2);
    }

    /**
     * @covers \Validator\FormValidator::validateRegex
     */
    public function testValidateRegex()
    {
        // Arrange
        $parameters = ['/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.'];
        $parameters[0] .= '(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.';
        $parameters[0] .= '(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.';
        $parameters[0] .= '(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)/';

        $value1 = '127.0.0.1';
        $value2 = '999.999.999.999';

        // Act
        for ($i = 1; $i <= 2; $i++) {
            ${'act' . $i} = $this->invokeMethod(
                $this->validator,
                'validateRegex',
                [${'value' . $i}, $parameters]
            );
        }

        // Assert
        $this->assertTrue($act1);
        $this->assertFalse($act2);
    }
}
