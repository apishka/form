<?php

/**
 * Apishka form field signature
 */

class Apishka_Form_Field_Signature extends Apishka_Form_Field_String
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
                'required'          => true,
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
        return parent::getName() . '_' . md5(parent::getName() . $this->getForm()->getUniqueId());
    }

    /**
     * Get value
     *
     * @return string
     */

    public function getValue()
    {
        return md5(
            $this->getForm()->getUniqueId() .
            serialize($this->getForm()->getSignatureParams())
        );
    }
}
