<?php

namespace Validator\FormElements;

use \Validator\Abstracts\AbstractFormElement;

class Select extends AbstractFormElement
{
    private $options;

    /**
     * Options setter
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * Render field
     *
     * @return string
     */
    public function renderField()
    {
        $value = $this->getValue();

        $element = '<select';
        foreach ($this->attributes as $name => $value) {
            if ($name != 'value') {
                $element .= ' ' . $name . '="' . $value . '"';
            }
        }
        $element .= '>';

        if (isset($this->options) && !empty($this->options)) {
            foreach ($this->options as $option => $text) {
                $element .= '<option value="' . $option . '"';

                if ($option == $value) {
                    $element .= ' selected';
                }

                $element .= '>';
                $element .= $text;
                $element .= '</option>';
            }
        }

        $element .= '</select>';

        return $element;
    }
}
