<?php

namespace Validator\FormElements;

use \Validator\Abstracts\AbstractFormElement;

class Button extends AbstractFormElement
{
    public function renderField()
    {
        $element = '<button';
        foreach ($this->attributes as $name => $value) {
            if ($name != 'value') {
                $element .= ' ' . $name . '="' . $value . '"';
            }
        }
        $element .= '>';

        $element .= $this->getValue();

        $element .= '</button>';

        return $element;
    }
}
