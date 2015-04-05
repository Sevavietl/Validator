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

    /**
     * Set value to element with given name
     *
     * @param string $name
     * @param mixed $value
     *
     * @return object
     */
    public function setElementValue($name, $value)
    {
        $this->getElementByName($name)
            ->setValue($value);

        return $this;
    }

    /**
     * Get element by given name
     *
     * @param string $name
     *
     * @return object
     */
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

    /**
     * Add element to the elements
     *
     * @param AbstractFormElement $element
     */
    protected function addElement(AbstractFormElement $element)
    {
        $this->elements[] = $element;
    }

    /**
     * Add inputs to inputs
     *
     * @param string $type
     * @param array $attributes
     * @param string $label
     *
     * @return object
     */
    public function addInput($type, $attributes, $label = null)
    {
        $input = new Input($attributes, $label);

        $input->defineType($type);

        $this->addElement($input);

        return $this;
    }

    /**
     * Add textarea element
     *
     * @param array $attributes
     * @param string $label
     *
     * @return object
     */
    public function addTextarea($attributes, $label = null)
    {
        $textarea = new Textarea($attributes, $label);

        $this->addElement($textarea);

        return $this;
    }

    /**
     * Add Select element
     *
     * @param array $options
     * @param array $attributes
     * @param string $label
     *
     * @return object
     */
    public function addSelect($options, $attributes, $label = null)
    {
        $select = new Select($attributes, $label);
        $select->setOptions($options);

        $this->addElement($select);

        return $this;
    }

    /**
     * Add button object
     *
     * @param array $attributes
     *
     * @return object
     */
    public function addButton($attributes)
    {
        $button = new Button($attributes);

        $this->addElement($button);

        return $this;
    }

    /**
     * Set validator for the form
     *
     * @param AbstractValidator $validator [description]
     *
     * @return object
     */
    public function setValidator(AbstractValidator $validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Validate from elements
     *
     * @return boolean
     */
    public function validate()
    {
        $this->validator->setInputs($this->elements);

        return !$this->validator->fails();
    }

    /**
     * Function to render the form
     *
     * @return string
     */
    abstract public function __toString();
}
