<?php

use \Validator\FormElements\Textarea;

class TextareaTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Validator\FormElements\Textarea::renderField
     */
    public function testRenderFieldWithValue()
    {
        // Arrange
        $attributes = [
            'name' => 'message',
            'rows' => 10,
            'cols' => 30,
            'value' => 'The cat was playing in the garden.',
        ];
        $input = new Textarea($attributes);
        $expected = '<textarea name="message" rows="10" cols="30">';
        $expected .= 'The cat was playing in the garden.';
        $expected .= '</textarea>';

        // Act
        $actual = $input->renderField();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \Validator\FormElements\Textarea::renderField
     */
    public function testRenderFieldWithoutValue()
    {
        // Arrange
        $attributes = [
            'name' => 'message',
            'rows' => 10,
            'cols' => 30,
        ];
        $input = new Textarea($attributes);
        $expected = '<textarea name="message" rows="10" cols="30">';
        $expected .= '</textarea>';

        // Act
        $actual = $input->renderField();

        // Assert
        $this->assertEquals($expected, $actual);
    }
}
