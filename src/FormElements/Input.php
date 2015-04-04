<?php
namespace Validator\FormElements;

use \Validator\Abstracts\AbstractFormElement;

class Input extends AbstractFormElement
{
    private $type;

    public function defineType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function renderField()
    {
        $element = '<input type="' . $this->type . '"';

        foreach ($this->attributes as $name => $value) {
            $element .= ' ' . $name . '="' . $value . '"';
        }

        $element .= '>';

        return $element;
    }
}
