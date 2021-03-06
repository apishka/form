<?php

/**
 * Apishka form field string
 *
 * @property-read array $value
 * @property-read array $default
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
            [
                'default_value' => [],
                'element_value' => null,
                'element_key'   => null,
            ]
        );
    }

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
        $transformations['Transform/Array'] = [];
        $transformations['Transform/ArrayIntersect'] = ['condition' => function () {return $this->getValues() !== null; }, 'values' => function () {return array_keys($this->__getValues()); }];

        return $transformations;
    }

    /**
     * Initialize
     *
     * @param Apishka_Form_FormAbstract $form
     *
     * @return Admin_ElementAbstract this
     */
    public function initialize(Apishka_Form_FormAbstract $form)
    {
        parent::initialize($form);

        if ($this->getElement())
            $this->getElement()->initialize($form);

        if ($this->getElementKey())
            $this->getElementKey()->initialize($form);

        return $this;
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
        if ($this->isInitialized())
            $field->initialize($this->getForm());

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
        if ($this->isInitialized())
            $field->initialize($this->getForm());

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
     * @param null|mixed $value
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

        $result = [];
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
