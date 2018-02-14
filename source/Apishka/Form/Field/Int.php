<?php

/**
 * Apishka form field int
 *
 * @property-read int $value
 * @property-read int $default
 */
class Apishka_Form_Field_Int extends Apishka_Form_FieldAbstract
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
        $transformations['Transform/Int'] = [];
        $transformations['Transform/ArrayKeyExists'] = ['condition' => function () {return $this->getValues() !== null; }, 'values' => function () {return $this->__getValues(); }];

        return $transformations;
    }
}
