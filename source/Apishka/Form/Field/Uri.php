<?php

/**
 * Apishka form field uri
 */

class Apishka_Form_Field_Uri extends Apishka_Form_FieldAbstract
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
        $transformations['Transform/Trim'] = [];
        $transformations['Transform/Uri'] = [];

        return $transformations;
    }
}
