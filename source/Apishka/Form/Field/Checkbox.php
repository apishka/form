<?php

/**
 * Apishka form field checkbox
 *
 * @easy-extend-base
 */

class Apishka_Form_Field_Checkbox extends Apishka_Form_Field_Bool
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
                'default_value' => 0,
            )
        );
    }
}
