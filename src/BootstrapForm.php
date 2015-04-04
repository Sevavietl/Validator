<?php

namespace Validator;

use \Validator\Abstracts\AbstractForm;

class BootstrapForm extends AbstractForm
{
    public function __toString()
    {
        $form = '<form ';
        $form .= 'action="' . $this->action . '" ';
        $form .= 'method="' . $this->method . '">';

        if (isset($this->elements) && !empty($this->elements)) {
            foreach ($this->elements as $element) {
                $form .= '<div class="form-group';
                if (!empty($element->getErrors())) {
                    $form .= ' has-error';
                }
                $form .= '">';
                $form .= $element->renderLabel();
                $form .= $element->renderField();
                if (!empty($element->getErrors())) {
                    foreach ($element->getErrors() as $error) {
                        $form .= '<span class="help-block">';
                        $form .= $error;
                        $form .= '</span>';
                    }
                }
                $form .= '</div>';
            }
        }

        $form .= '</form>';

        return $form;
    }
}
