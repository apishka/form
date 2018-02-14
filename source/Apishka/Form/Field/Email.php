<?php

/**
 * Apishka form field email
 *
 * @property-read string $value
 * @property-read string $default
 */
class Apishka_Form_Field_Email extends Apishka_Form_FieldAbstract
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
        $transformations['Transform/Trim'] = [];
        $transformations['Transform/Lower'] = [];
        $transformations['Transform/Email'] = [];

        return $transformations;
    }
}
