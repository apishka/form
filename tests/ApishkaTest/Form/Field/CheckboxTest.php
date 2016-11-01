<?php

/**
 * Apishka test form field checkbox test
 */

class ApishkaTest_Form_Field_CheckboxTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get form
     *
     * @param bool $is_sent
     */

    protected function getForm($is_sent)
    {
        $stub = $this->getMockBuilder('Apishka_Form_FormAbstract')
            ->setMockClassName('ApishkaTest_Form_Field_CheckboxTest_Form')
            ->setMethods(['isSent', 'drawTpl'])
            ->getMock()
        ;

        $stub->expects($this->any())
            ->method('isSent')
            ->will($this->returnValue($is_sent))
        ;

        $stub->expects($this->any())
            ->method('drawTpl')
            ->willReturn(null)
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
        $field = Apishka_Form_Field_Checkbox::apishka($name);
        $field->initialize($this->getForm($is_sent));

        return $field;
    }

    /**
     * Test name
     */

    public function testName()
    {
        $field = $this->getField('checkbox_field');

        $this->assertSame(
            'checkbox_field',
            $field->getName()
        );

        $this->assertSame(
            'checkbox_field',
            $field->name
        );

        $this->assertSame(
            'checkbox_field',
            $field->getStructureName()
        );

        $field->setName('checkbox_field_2');

        $this->assertSame(
            'checkbox_field_2',
            $field->getName()
        );

        $this->assertSame(
            'checkbox_field_2',
            $field->name
        );

        $this->assertSame(
            'checkbox_field',
            $field->getStructureName()
        );
    }

    /**
     * Test value with empty request
     */

    public function testValueWithEmptyRequest()
    {
        $field = $this->getField('checkbox_field');

        $this->assertNull($field->getValue());

        $this->assertTrue($field->isValid());
        $this->assertSame(
            0,
            $field->value
        );
    }

    /**
     * Test value
     *
     * @backupGlobals enabled
     */

    public function testValueWithInvalidRequest()
    {
        $field = $this->getField('checkbox_field');

        $_POST = array(
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
        $field = $this->getField('checkbox_field');

        $_POST = array(
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
        $field = $this->getField('checkbox_field');

        $this->assertTrue($field->isValid());
        $this->assertSame(
            0,
            $field->value
        );
    }

    /**
     * Test required
     */

    public function testRequired()
    {
        $field = $this->getField('checkbox_field');
        $field->setRequired(true);

        $this->assertTrue($field->isValid());
        $this->assertSame(
            0,
            $field->value
        );
    }

    /**
     * Test blank value
     */

    public function testBlankValue()
    {
        $field = $this->getField('checkbox_field');

        $_POST = array(
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
        $field = $this->getField('string_field');
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
        $field = $this->getField('string_field');
        $field->setDefault(1);

        $_POST = array(
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
        $field = $this->getField('string_field');
        $field->setRequired(true);
        $field->setDefault(1);

        $_POST = array(
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
        $field = $this->getField('string_field', false);
        $field->setDefault(1);

        $this->assertTrue($field->isValid());
        $this->assertSame(
            1,
            $field->value
        );
    }
}
