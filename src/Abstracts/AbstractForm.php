<?php

namespace Validator\Abstracts;

use \Validator\FormElements\Button;
use \Validator\FormElements\Input;
use \Validator\FormElements\Select;
use \Validator\FormElements\Textarea;

use \Validator\Exceptions\NoFormElementWithGivenName;

abstract class AbstractForm
{
    protected $method;
    protected $action;
    protected $elements;
    protected $errors;
    protected $validator;

    protected $inputTypes = [
        'text',
        'password',
        'submit',
        'radio',
        'checkbox',
        'button',
        'email',
    ];

    public function __construct($action, $method)
    {
        $this->action = $action;
        $this->method = $method;
    }

    public function setElementValue($name, $value)
    {
        $this->getElementByName($name)
            ->setValue($value);

        return $this;
    }

    public function getElementByName($name)
    {
        if (isset($this->elements) && !empty($this->elements)) {
            foreach ($this->elements as &$element) {
                if ($element->getName() == $name) {
                    return $element;
                }
            }
        }

        throw new NoFormElementWithGivenName();
    }

    public function __call($method, $args)
    {
        $words = preg_split('/(?=[A-Z])/', $method);
        $words = array_map(function($word) {
            return strtolower($word);
        }, $words);

        if ($words[0] == 'add' && $words[1] == 'input') {
            if (isset($words[2]) && !is_null($words[2])) {
                if (in_array($words[2], $this->inputTypes)) {
                    return $this->addInput(
                        $words[2],
                        $args[0],
                        isset($args[1]) ? $args[1] : null
                    );
                }
            }
        }

        if (method_exists($this, $method)) {
            return call_user_func_array($this->$method, $args);
        }

        throw new \BadMethodCallException();
    }

    protected function addElement(AbstractFormElement $element)
    {
        $this->elements[] = $element;
    }

    public function addInput($type, $attributes, $label = null)
    {
        $input = new Input($attributes, $label);

        $input->defineType($type);

        $this->addElement($input);

        return $this;
    }

    public function addTextarea($attributes, $label = null)
    {
        $textarea = new Textarea($attributes, $label);

        $this->addElement($textarea);

        return $this;
    }

    public function addSelect($options, $attributes, $label = null)
    {
        $select = new Select($attributes, $label);
        $select->setOptions($options);

        $this->addElement($select);

        return $this;
    }

    public function addButton($attributes)
    {
        $button = new Button($attributes);

        $this->addElement($button);

        return $this;
    }

    public function setValidator(AbstractValidator $validator)
    {
        $this->validator = $validator;

        return $this;
    }

    public function validate()
    {
        $this->validator->setInputs($this->elements);

        return !$this->validator->fails();
    }

    abstract public function __toString();
}
