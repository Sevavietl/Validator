<?php
namespace Validator\FormElements;

use \Validator\Abstracts\AbstractFormElement;

class Input extends AbstractFormElement
{
    private $type;

    /**
     * Type setter
     *
     * @param string $type
     *
     * @return object
     */
    public function defineType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Type getter
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Render field
     *
     * @return string
     */
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
