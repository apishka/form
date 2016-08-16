<?php

/**
 * Apishka form field phone
 */

class Apishka_Form_Field_Phone extends Apishka_Form_FieldAbstract
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
        $transformations['Transform/Phone'] = ['country_code' => function () {return $this->getCountryCode();}];

        return $transformations;
    }

    /**
     * Set country code
     *
     * @param string $code
     *
     * @return Apishka_Form_FieldAbstract this
     */

    public function setCountryCode($code)
    {
        return $this->setOption('country_code', (string) $code);
    }

    /**
     * Get country code
     *
     * @return bool
     */

    public function getCountryCode()
    {
        return $this->getOption('country_code');
    }
}
