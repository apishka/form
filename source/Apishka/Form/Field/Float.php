<?php

/**
 * Apishka form field float
 */
class Apishka_Form_Field_Float extends Apishka_Form_FieldAbstract
{
    /**
     * Get default transformations
     *
     * @return array
     */
    protected function getDefaultTransformations()
    {
        $transformations = [];

        $transformations['Transform/Blank'] = [];
        $transformations['Transform/NotBlank'] = ['condition' => function () {return $this->getRequired(); }];
        $transformations['Transform/Float'] = [];
        $transformations['Transform/ArrayKeyExists'] = ['condition' => function () {return $this->getValues() !== null; }, 'values' => function () {return $this->__getValues(); }];

        return $transformations;
    }
}
