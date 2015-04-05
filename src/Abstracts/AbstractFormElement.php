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

    /**
     * Name getter
     *
     * @return object
     */
    public function getName()
    {
        if (isset($this->attributes['name'])) {
            return $this->attributes['name'];
        } else {
            throw new FormElementHasNoNameException();
        }
    }

    /**
     * Value getter
     *
     * @return mixed
     */
    public function getValue()
    {
        if (isset($this->attributes['value'])) {
            return $this->attributes['value'];
        } else {
            return '';
        }
    }

    /**
     * Value setter
     *
     * @param mixed $value
     *
     * @return object
     */
    public function setValue($value)
    {
        $this->attributes['value'] = $value;

        return $this;
    }

    /**
     * Add error
     *
     * @param string $error
     *
     * @return object
     */
    public function addError($error)
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * Errors getter
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Render element label
     *
     * @return mixed
     */
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

    /**
     * Render field
     */
    abstract public function renderField();
}
