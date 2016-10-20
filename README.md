# Simple form usage

```php
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
# Basic field usage
Each field has functions to manipulate
```php
$field = Apishka_Form_Field_String::apishka('field_name')
    // By default all fields have unique ID, but if you want to
    // set some specific
    ->setId('some_id')

    // You can mark field as required
    ->setRequired(true)

    // Returns value if field not presented in request
    ->setDefault('some_string')

    // Set current field value
    ->setValue('some_value')

    // Add transformation on value to the end of transformations list
    ->pushTransformation(
        'Transform/Length',
        [
            'min' => 10,
            'max' => 20,
        ]
    )

    // Unshift transformation to transformations list
    ->pushTransformation(
        'Transform/Callback',
        [
            'callback' => function($value)
            {
                return mb_strtolower($value);
            }
        ]
    )
;
```
