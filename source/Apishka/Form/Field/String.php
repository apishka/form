<?php

/**
 * Apishka form field string
 */

class Apishka_Form_Field_String extends Apishka_Form_FieldAbstract
{
    /**
     * Get default validations
     *
     * @return array
     */

    protected function getDefaultValidations()
    {
        $validations = array();

        $validations['Transform/Blank'] = [];

        if ($this->getRequired())
            $validations['Transform/NotBlank'] = [];

        $validations['Transform/String'] = [];

        return $validations;
    }
}
