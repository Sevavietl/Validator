<?php

use \Validator\FormElements\Button;

class ButtonTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Validator\FormElements\Button::renderField
     */
    public function testRenderFieldWithValue()
    {
        // Arrange
        $attributes = [
            'type' => 'button',
            'onclick' => 'alert(\'Hello World!\')',
            'value' => 'Click Me!',
        ];
        $input = new Button($attributes);
        $expected = '<button type="button" onclick="alert(\'Hello World!\')">';
        $expected .= 'Click Me!';
        $expected .= '</button>';

        // Act
        $actual = $input->renderField();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \Validator\FormElements\Button::renderField
     */
    public function testRenderFieldWithoutValue()
    {
        // Arrange
        $attributes = [
            'type' => 'button',
            'onclick' => 'alert(\'Hello World!\')',
        ];
        $input = new Button($attributes);
        $expected = '<button type="button" onclick="alert(\'Hello World!\')">';
        $expected .= '</button>';

        // Act
        $actual = $input->renderField();

        // Assert
        $this->assertEquals($expected, $actual);
    }
}
