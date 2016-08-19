<?php

/**
 * Apishka test form field bool test
 */

class ApishkaTest_Form_Field_BoolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get form
     *
     * @param bool $is_sent
     */

    protected function getForm($is_sent)
    {
        $stub = $this->getMockBuilder('Apishka_Form_FormAbstract')
            ->setMockClassName('ApishkaTest_Form_Field_BoolTest_Form')
            ->setMethods(['isSent'])
            ->getMock()
        ;

        $stub->expects($this->any())
            ->method('isSent')
            ->will($this->returnValue($is_sent))
        ;

        return $stub;
    }

    /**
     * Get field
     *
     * @param string $name
     * @param bool   $is_sent
     *
     * @return Apishka_Form_Field_Signature
     */

    protected function getField($name, $is_sent = true)
    {
        $field = Apishka_Form_Field_Bool::apishka($name);
        $field->initialize($this->getForm($is_sent));

        return $field;
    }

    /**
     * Test name
     */

    public function testName()
    {
        $field = $this->getField('bool_field');

        $this->assertSame(
            'bool_field',
            $field->getName()
        );

        $this->assertSame(
            'bool_field',
            $field->name
        );

        $this->assertSame(
            'bool_field',
            $field->getStructureName()
        );

        $field->setName('bool_field_2');

        $this->assertSame(
            'bool_field_2',
            $field->getName()
        );

        $this->assertSame(
            'bool_field_2',
            $field->name
        );

        $this->assertSame(
            'bool_field',
            $field->getStructureName()
        );
    }

    /**
     * Test value with empty request
     */

    public function testValueWithEmptyRequest()
    {
        $field = $this->getField('bool_field');

        $this->assertNull($field->getValue());

        $this->assertTrue($field->isValid());
        $this->assertNull($field->value);
    }

    /**
     * Test value
     *
     * @backupGlobals enabled
     */

    public function testValueWithInvalidRequest()
    {
        $field = $this->getField('bool_field');

        $_REQUEST = array(
            $field->name => 'edfaed6b51dedc42b21d58134f1afe93',
        );

        $this->assertTrue($field->isValid());
        $this->assertSame(
            1,
            $field->value
        );
    }

    /**
     * Test value
     *
     * @backupGlobals enabled
     */

    public function testValueWithRequest()
    {
        $field = $this->getField('bool_field');

        $_REQUEST = array(
            $field->name => '100',
        );

        $this->assertTrue($field->isValid());
        $this->assertSame(
            1,
            $field->value
        );
    }

    /**
     * Test not required
     */

    public function testNotRequired()
    {
        $field = $this->getField('bool_field');

        $this->assertTrue($field->isValid());
        $this->assertNull($field->value);
    }

    /**
     * Test required
     *
     * @expectedException \Apishka\Transformer\FriendlyException
     * @expectedExceptionMessage cannot be empty
     */

    public function testRequired()
    {
        $field = $this->getField('bool_field');
        $field->setRequired(true);

        $this->assertFalse($field->isValid());

        throw $field->getError();
    }

    /**
     * Test blank value
     *
     * @backupGlobals enabled
     */

    public function testBlankValue()
    {
        $field = $this->getField('bool_field');

        $_REQUEST = array(
            $field->name => '',
        );

        $this->assertTrue($field->isValid());
        $this->assertNull($field->value);
    }

    /**
     * Test default value
     */

    public function testDefaultValue()
    {
        $field = $this->getField('bool_field');
        $field->setDefault(1);

        $this->assertTrue($field->isValid());
        $this->assertSame(
            1,
            $field->value
        );
    }

    /**
     * Test default value with blank request
     *
     * @backupGlobals enabled
     */

    public function testDefaultValueWithBlankRequest()
    {
        $field = $this->getField('bool_field');
        $field->setDefault(1);

        $_REQUEST = array(
            $field->name => '',
        );

        $this->assertTrue($field->isValid());
        $this->assertNull($field->value);
    }

    /**
     * Test default required value with blank request
     *
     * @backupGlobals enabled
     * @expectedException \Apishka\Transformer\FriendlyException
     * @expectedExceptionMessage cannot be empty
     */

    public function testDefaultRequiredValueWithBlankRequest()
    {
        $field = $this->getField('bool_field');
        $field->setRequired(true);
        $field->setDefault(1);

        $_REQUEST = array(
            $field->name => '',
        );

        $this->assertFalse($field->isValid());

        throw $field->getError();
    }

    /**
     * Test default value for non sent form
     */

    public function testDefaultValueForNonSentForm()
    {
        $field = $this->getField('bool_field', false);
        $field->setDefault(1);

        $this->assertTrue($field->isValid());
        $this->assertSame(
            1,
            $field->value
        );
    }

    /**
     * Test set values
     *
     * @backupGlobals enabled
     */

    public function testSetValues()
    {
        $field = $this->getField('bool_field');
        $field->setValues(
            array(
                0 => 'No',
                1 => 'Yes',
            )
        );

        $_REQUEST = array(
            $field->name => 1,
        );

        $this->assertSame(
            array(
                0 => 'No',
                1 => 'Yes',
            ),
            $field->getValues()
        );

        $this->assertSame(
            array(
                0 => 'No',
                1 => 'Yes',
            ),
            $field->values
        );

        $this->assertTrue($field->isValid());
        $this->assertSame(
            1,
            $field->value
        );
    }

    /**
     * Test set values callback
     *
     * @backupGlobals enabled
     */

    public function testSetValuesCallback()
    {
        $field = $this->getField('bool_field');
        $field->setValues(
            function()
            {
                return array(
                    0 => 'No',
                    1 => 'Yes',
                );
            }
        );

        $_REQUEST = array(
            $field->name => 1,
        );

        $this->assertSame(
            array(
                0 => 'No',
                1 => 'Yes',
            ),
            $field->values
        );

        $this->assertTrue($field->isValid());
        $this->assertSame(
            1,
            $field->value
        );
    }
}
