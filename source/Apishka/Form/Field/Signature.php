<?php

/**
 * Apishka form field signature
 */

class Apishka_Form_Field_Signature extends Apishka_Form_FieldAbstract
{
    /**
     * Get default options
     *
     * @return array
     */

    protected function getDefaultOptions()
    {
        return array_replace_recursive(
            parent::getDefaultOptions(),
            array(
                'structure_name'    => parent::getName(),
            )
        );
    }

    /**
     * Get name
     *
     * @return string
     */

    public function getName()
    {
        return parent::getName() . '_' . md5(parent::getName() . get_class($this->getForm()));
    }
}
