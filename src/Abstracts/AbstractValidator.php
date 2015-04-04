<?php

namespace Validator\Abstracts;

use \Validator\Exceptions\RuleAbsentException;
use \Validator\Exceptions\BadRuleDeclarationException;

abstract class AbstractValidator
{
    protected $rules;
    protected $inputs;
    protected $messages;

    protected $messages_patterns = [
        'required' => '%s is required',
        'boolean' => '%s must be boolean',
        'numeric' => '%s must be numeric',
        'integer' => '%s must be integer',
        'between' => '%s must be between %d and %d',
        'min' => '%s must be bigger than %d',
        'max' => '%s must be lesser than %d',
        'email' => '%s must be email',
        'url' => '%s must be url',
        'regex' => '%s not matches regex "%s"',
    ];

    public function setInputs(&$inputs)
    {
        $this->inputs = $inputs;

        return $this;
    }

    /**
     * Find out if validation fails
     *
     * @return bool
     */
    abstract public function fails();

    abstract public function validate($input);

    protected function composeMessage($rule, $attribute, $parameters = null)
    {
        if (!array_key_exists($rule, $this->messages_patterns)) {
            throw new RuleAbsentException();
        }

        $message = $this->messages_patterns[$rule];
        return vsprintf($message, array_merge([$attribute], $parameters ?: []));
    }

    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Get rules
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Parse rules, exploding by '|'
     * @param mixed $rules
     */
    protected function parseRules($rules)
    {
        if (is_array($rules)) {
            return $rules;
        }

        return explode('|', $rules);
    }

    /**
     * Parse rule to retrive parameters for complex rules
     *
     * Throws BadRuleDeclarationException if rule delcared not right
     *
     * @param string $rule
     * @return mixed
     */
    protected function parseRule($rule)
    {
        if (strpos($rule, ':') !== false) {
            $count = substr_count($rule, ':');
            $rule = explode(':', $rule);

            if ((count($rule) - 1) !== $count || in_array('', $rule)) {
                throw new BadRuleDeclarationException();
            }

            return [
                array_shift($rule),
                array_map(function ($parameter) {
                    return ctype_digit($parameter) ? (int) $parameter : $parameter;
                }, $rule)
            ];
        }

        return [$rule, []];
    }

    /**
     * Validate that a required attribute exists.
     *
     * @param mixed $value
     * @return bool
     */
    protected function validateRequired($value)
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        } elseif ((is_array($value) || $value instanceof \Countable) && count($value) < 1) {
            return false;
        }

        return true;
    }

    /**
    * Validate that an attribute is a boolean.
    *
    * @param  mixed   $value
    * @return bool
    */
    protected function validateBoolean($value)
    {
        $acceptable = array(true, false, 0, 1, '0', '1');

        return in_array($value, $acceptable, true);
    }

    /**
    * Validate that an attribute is numeric.
    *
    * @param  mixed   $value
    * @return bool
    */
    protected function validateNumeric($value)
    {
        return is_numeric($value);
    }

    /**
    * Validate that an attribute is an integer.
    *
    * @param  mixed   $value
    * @return bool
    */
    protected function validateInteger($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    /**
    * Validate the size of an attribute is between a set of values.
    *
    * @param  mixed   $value
    * @param  array   $parameters
    * @return bool
    */
    protected function validateBetween($value, $parameters)
    {
        $size = $this->getSize($value);

        return $size >= $parameters[0] && $size <= $parameters[1];
    }

    /**
    * Validate the size of an attribute is greater than a minimum value.
    *
    * @param  mixed   $value
    * @param  array   $parameters
    * @return bool
    */
    protected function validateMin($value, $parameters)
    {
        return $this->getSize($value) >= $parameters[0];
    }

    /**
    * Validate the size of an attribute is less than a maximum value.
    *
    * @param  mixed   $value
    * @param  array   $parameters
    * @return bool
    */
    protected function validateMax($value, $parameters)
    {
        return $this->getSize($value) <= $parameters[0];
    }

    /**
    * Get the size of an attribute.
    *
    * @param  mixed   $value
    * @return mixed
    */
    protected function getSize($value)
    {
        if (is_numeric($value)) {
            return $value;
        } elseif (is_array($value)) {
            return count($value);
        }

        return $this->getStringSize($value);
    }

    /**
    * Get the size of a string.
    *
    * @param  string  $value
    * @return int
    */
    protected function getStringSize($value)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($value);
        }

        return strlen($value);
    }

    /**
    * Validate that an attribute is a valid e-mail address.
    *
    * @param  mixed   $value
    * @return bool
    */
    protected function validateEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
    * Validate that an attribute is a valid URL.
    *
    * @param  mixed   $value
    * @return bool
    */
    protected function validateUrl($value)
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    /**
    * Validate that an attribute passes a regular expression check.
    *
    * @param  mixed   $value
    * @param  array   $parameters
    * @return bool
    */
    protected function validateRegex($value, $parameters)
    {
        return (boolean) preg_match($parameters[0], $value);
    }
}
