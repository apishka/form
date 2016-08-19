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
                'element_value' => null,
                'element_key'   => null,
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

    /**
     * Set sub-field
     *
     * @param Apishka_Form_FieldAbstract $field
     *
     * @return Apishka_Form_Field_Array this
     */

    public function setElement(Apishka_Form_FieldAbstract $field)
    {
        return $this->setOption('element_value', $field);
    }

    /**
     * Get sub-field
     *
     * @return Apishka_Form_FieldAbstract
     */

    public function getElement()
    {
        return $this->getOption('element_value');
    }

    /**
     * Set sub-field key
     *
     * @param Apishka_Form_FieldAbstract $field
     *
     * @return Apishka_Form_Field_Array this
     */

    public function setElementKey(Apishka_Form_FieldAbstract $field)
    {
        return $this->setOption('element_key', $field);
    }

    /**
     * Get sub-field key
     *
     * @return Apishka_Form_FieldAbstract
     */

    public function getElementKey()
    {
        return $this->getOption('element_key');
    }

    /**
     * Run validations
     *
     * @return mixed
     */

    public function runValidations($value = null)
    {
        $value = parent::runValidations($value);

        $element_value = $this->getElement();
        $element_key   = $this->getElementKey();

        if (!$element_key && !$element_value)
            return $value;

        $result = array();
        foreach ($value as $key => $value)
        {
            $key = $element_key
                ? $element_key->runValidations($key)
                : $key
            ;

            $result[$key] = $element_value
                ? $element_value->runValidations($value)
                : $value
            ;
        }

        return $result;
    }
}
