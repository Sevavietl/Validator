<?php

use \Validator\FormElements\Select;

class SelectTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Validator\FormElements\Select::setOptions
     * @covers \Validator\FormElements\Select::renderField
     */
    public function testRenderFieldWithoutValue()
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
        $input = new Select($attributes);
        $input->setOptions($options);
        $expected = '<select name="cars">';
        $expected .= '<option value="volvo">Volvo</option>';
        $expected .= '<option value="saab">Saab</option>';
        $expected .= '<option value="fiat">Fiat</option>';
        $expected .= '<option value="audi">Audi</option>';
        $expected .= '</select>';

        // Act
        $actual = $input->renderField();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \Validator\FormElements\Select::setOptions
     * @covers \Validator\FormElements\Select::renderField
     */
    public function testRenderFieldWithValue()
    {
        // Arrange
        $attributes = [
            'name' => 'cars',
            'value' => 'volvo',
        ];
        $options = [
            'volvo' => 'Volvo',
            'saab' => 'Saab',
            'fiat' => 'Fiat',
            'audi' => 'Audi',
        ];
        $input = new Select($attributes);
        $input->setOptions($options);
        $expected = '<select name="cars">';
        $expected .= '<option value="volvo" selected>Volvo</option>';
        $expected .= '<option value="saab">Saab</option>';
        $expected .= '<option value="fiat">Fiat</option>';
        $expected .= '<option value="audi">Audi</option>';
        $expected .= '</select>';

        // Act
        $actual = $input->renderField();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \Validator\FormElements\Select::renderField
     */
    public function testRenderFieldWithoutOptions()
    {
        // Arrange
        $attributes = [
            'name' => 'cars',
        ];
        $input = new Select($attributes);
        $expected = '<select name="cars">';
        $expected .= '</select>';

        // Act
        $actual = $input->renderField();

        // Assert
        $this->assertEquals($expected, $actual);
    }
}
