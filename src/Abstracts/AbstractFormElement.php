<?php

namespace Validator\Abstracts;

use \Validator\Exceptions\FormElementHasNoNameException;

abstract class AbstractFormElement
{
    protected $attributes;
    protected $label;
    protected $errors;

    public function __construct($attributes, $label = null)
    {
        $this->attributes = $attributes;
        $this->label = $label;

        return $this;
    }

    public function getName()
    {
        if (isset($this->attributes['name'])) {
            return $this->attributes['name'];
        } else {
            throw new FormElementHasNoNameException();
        }
    }

    public function getValue()
    {
        if (isset($this->attributes['value'])) {
            return $this->attributes['value'];
        } else {
            return '';
        }
    }

    public function setValue($value)
    {
        $this->attributes['value'] = $value;

        return $this;
    }

    public function addError($error)
    {
        $this->errors[] = $error;

        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function renderLabel()
    {
        if (is_null($this->label)) {
            return false;
        }

        $label = '<label';
        $label .= ' for="' . $this->getName() . '"';
        $label .= '>';
        $label .= $this->label;
        $label .= '</label>';

        return $label;
    }

    abstract public function renderField();
}
