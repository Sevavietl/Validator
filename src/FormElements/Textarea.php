<?php

namespace Validator\FormElements;

use \Validator\Abstracts\AbstractFormElement;

class Textarea extends AbstractFormElement
{
    public function renderField()
    {
        $element = '<textarea';
        foreach ($this->attributes as $name => $value) {
            if ($name != 'value') {
                $element .= ' ' . $name . '="' . $value . '"';
            }
        }
        $element .= '>';

        $element .= $this->getValue();

        $element .= '</textarea>';

        return $element;
    }
}
