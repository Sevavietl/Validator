<?php

namespace Validator;

use \Validator\Abstracts\AbstractValidator;

class InputValidator extends AbstractValidator
{
    /**
     * Find out if validation fails
     *
     * @return bool
     */
    public function fails()
    {
        $fails = false;
        foreach ($this->inputs as $name => $value) {
            if (!$this->validate([$name, $value])) {
                $fails = true;
            }
        }

        return $fails;
    }

    /**
     * Validate plain input
     *
     * @param  array $input First element is name, second is value
     * @return boolean
     */
    public function validate($input)
    {
        list($name, $value) = $input;

        if (!array_key_exists($name, $this->getRules())) {
            return true;
        }

        $rules = $this->parseRules($this->getRules()[$name]);

        $errors = [];

        foreach ($rules as $rule) {
            list($attribute, $parameters) = $this->parseRule($rule);

            if (method_exists($this, "validate{$attribute}")) {
                $method = "validate{$attribute}";

                if (!$this->$method($value, $parameters)) {
                    $message = $this->composeMessage(
                        $attribute,
                        $name,
                        $parameters
                    );
                    $errors[] = $message;
                }
            } else {
                throw new \BadMethodCallException();
            }
        }

        if (empty($errors)) {
            return true;
        } else {
            $this->messages[$name] = $errors;

            return false;
        }
    }

    /**
     * Get all error messages
     *
     * @return mixed
     */
    public function getAllMessages()
    {
        return $this->messages ?: [];
    }

    /**
     * Get error messages by input name
     *
     * @param string $name
     * @return mixed
     */
    public function getMessages($name)
    {
        if (isset($this->messages[$name]) && !empty($this->messages[$name])) {
            return $this->messages[$name];
        }

        return [];
    }
}
