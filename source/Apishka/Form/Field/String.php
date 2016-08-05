<?php

/**
 * Apishka form field string
 */

class Apishka_Form_Field_String extends Apishka_Form_FieldAbstract
{
    /**
     * Get default transformations
     *
     * @return array
     */

    protected function getDefaultTransformations()
    {
        $transformations = array();

        $transformations['Transform/Blank'] = [];

        if ($this->getRequired())
            $transformations['Transform/NotBlank'] = [];

        $transformations['Transform/String'] = [];

        return $transformations;
    }
}
