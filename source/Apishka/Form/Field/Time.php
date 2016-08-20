<?php

/**
 * Apishka form field time
 */

class Apishka_Form_Field_Time extends Apishka_Form_Field_DateTimeAbstract
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
        $transformations['Transform/Time'] = [];
        $transformations['Transform/ArrayKeyExists'] = ['condition' => function () {return $this->getValues() !== null;}, 'values' => function () {return $this->__getValues();}];

        return $transformations;
    }
}
