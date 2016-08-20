<?php

/**
 * Apishka form field date
 */

class Apishka_Form_Field_DateTime extends Apishka_Form_FieldAbstract
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
        $transformations['Transform/DateTime'] = [];
        $transformations['Transform/ArrayKeyExists'] = ['condition' => function () {return $this->getValues() !== null;}, 'values' => function () {return $this->__getValues();}];

        return $transformations;
    }
}
