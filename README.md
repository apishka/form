# Simple form usage

```php
<?php

class MyForm extends Apishka_Form_FormAbstract
{
    /**
     * Returns form structure
     *
     * @return array
     */

    public function processStructure()
    {
        // This will add signature field to form, to determine when form was posted,
        // don't forger to include 'signature' in your markup
        parent::processStructure();

        // We can add simple string field
        $this->addField(
            Apishka_Form_Field_String::apishka('string_field')
        );

        // We add simpel int field
        $this->addField(
            Apishka_Form_Field_Int::apishka('int_field')
        );
    }
}
```
