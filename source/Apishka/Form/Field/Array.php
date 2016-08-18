<?php

/**
 * Apishka form field string
 */

class Apishka_Form_Field_Array extends Apishka_Form_FieldAbstract
{
    /**
     * Get default options
     *
     * @return array
     */

    protected function getDefaultOptions()
    {
        return array_replace(
            parent::getDefaultOptions(),
            array(
                'default_value' => array(),
            )
        );
    }

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
        $transformations['Transform/Array'] = [];

        return $transformations;
    }
}
