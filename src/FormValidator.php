<?php

namespace Validator;

use \Validator\Abstracts\AbstractValidator;
use \Validator\Abstracts\AbstractFormElement;

class FormValidator extends AbstractValidator
{
    /**
     * Find out if validation fails
     *
     * @return bool
     */
    public function fails()
    {
        $fails = false;

        foreach ($this->inputs as $input) {
            if ($this->validate($input)) {
                $fails = true;
            }
        }

        return $fails;
    }

    /**
     * Validate form element
     *
     * @param  \Validator\Abstracts\AbstractFormElement $input
     * @return boolean
     */
    public function validate($input)
    {
        $attribute = $input->getName();

        if (!array_key_exists($attribute, $this->getRules())) {
            return true;
        }

        $rules = $this->parseRules($this->getRules()[$attribute]);

        foreach ($rules as $rule) {
            list($attribute, $parameters) = $this->parseRule($rule);

            if (method_exists($this, "validate{$attribute}")) {
                $method = "validate{$attribute}";

                if (!$this->$method($input->getValue(), $parameters)) {
                    $message = $this->composeMessage(
                        $attribute,
                        $input->getName(),
                        $parameters
                    );
                    $input->addError($message);
                }
            } else {
                throw new \BadMethodCallException();
            }
        }

        if (empty($input->getErrors())) {
            return true;
        } else {
            return false;
        }
    }
}
