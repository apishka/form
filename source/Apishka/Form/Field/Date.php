<?php

/**
 * Apishka form field date
 */
class Apishka_Form_Field_Date extends Apishka_Form_Field_DateTimeAbstract
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
        $transformations['Transform/Date'] = [];
        $transformations['Transform/ArrayKeyExists'] = ['condition' => function () {return $this->getValues() !== null; }, 'values' => function () {return $this->__getValues(); }];

        return $transformations;
    }
}
