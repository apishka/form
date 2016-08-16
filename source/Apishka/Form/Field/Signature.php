<?php

/**
 * Apishka form field signature
 *
 * @easy-extend-base
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
                'required'          => true,
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
        $transformations = parent::getDefaultTransformations();

        $transformations['Transform/Callback'] = array(
            'callback' => function ($value)
            {
                if ($this->getValue() != $value)
                {
                    throw Apishka\Transformer\FriendlyException::apishka(
                        array(
                            'message'   => 'wrong signature',
                        )
                    );
                }
            },
        );

        return $transformations;
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
     * Get ID
     *
     * @return string
     */

    public function getId()
    {
        if ($this->getId())
            return $this->getId();

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

    /**
     * Set default
     */

    public function setDefault($value)
    {
        throw new LogicException('signature field not supports default values');
    }
}
