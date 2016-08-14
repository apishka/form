<?php

/**
 * Apishka form field bool
 */

class Apishka_Form_Field_Bool extends Apishka_Form_FieldAbstract
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
        $transformations['Transform/NotBlank'] = ['condition' => function () {return $this->getRequired();}];
        $transformations['Transform/Bool'] = [];

        return $transformations;
    }
}
