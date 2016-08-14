<?php

/**
 * Apishka test form field int test
 */

class ApishkaTest_Form_Field_IntTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get form
     *
     * @param bool $is_sent
     */

    protected function getForm($is_sent)
    {
        $stub = $this->getMockBuilder('Apishka_Form_FormAbstract')
            ->setMockClassName('ApishkaTest_Form_Field_IntTest_Form')
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
        $field = Apishka_Form_Field_Int::apishka($name);
        $field->initialize($this->getForm($is_sent));

        return $field;
    }

    /**
     * Test name
     */

    public function testName()
    {
        $field = $this->getField('int_field');

        $this->assertEquals(
            'int_field',
            $field->getName()
        );

        $this->assertEquals(
            'int_field',
            $field->name
        );

        $this->assertEquals(
            'int_field',
            $field->getStructureName()
        );

        $field->setName('int_field_2');

        $this->assertEquals(
            'int_field_2',
            $field->getName()
        );

        $this->assertEquals(
            'int_field_2',
            $field->name
        );

        $this->assertEquals(
            'int_field',
            $field->getStructureName()
        );
    }

    /**
     * Test value with empty request
     */

    public function testValueWithEmptyRequest()
    {
        $field = $this->getField('int_field');

        $this->assertNull($field->getValue());

        $this->assertTrue($field->isValid());
        $this->assertNull($field->value);
    }

    /**
     * Test value
     *
     * @backupGlobals enabled
     * @expectedException Apishka\Transformer\FriendlyException
     * @expectedExceptionMessage wrong input format
     */

    public function testValueWithInvalidRequest()
    {
        $field = $this->getField('int_field');

        $_REQUEST = array(
            $field->name => 'edfaed6b51dedc42b21d58134f1afe93',
        );

        $this->assertFalse($field->isValid());

        throw $field->getError();
    }

    /**
     * Test value
     *
     * @backupGlobals enabled
     */

    public function testValueWithRequest()
    {
        $field = $this->getField('int_field');

        $_REQUEST = array(
            $field->name => '100',
        );

        $this->assertTrue($field->isValid());
        $this->assertEquals(
            100,
            $field->isValid()
        );
    }

    /**
     * Test not required
     */

    public function testNotRequired()
    {
        $field = $this->getField('int_field');

        $this->assertTrue($field->isValid());
        $this->assertNull($field->value);
    }

    /**
     * Test not required
     *
     * @expectedException \Apishka\Transformer\FriendlyException
     * @expectedExceptionMessage cannot be empty
     */

    public function testRequired()
    {
        $field = $this->getField('int_field');
        $field->setRequired(true);

        $this->assertFalse($field->isValid());

        throw $field->getError();
    }

    /**
     * Test blank value
     */

    public function testBlankValue()
    {
        $field = $this->getField('int_field');

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
        $field = $this->getField('string_field');
        $field->setDefault(1);

        $this->assertTrue($field->isValid());
        $this->assertEquals(
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
        $field = $this->getField('string_field');
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
        $field = $this->getField('string_field', false);
        $field->setDefault(1);

        $this->assertTrue($field->isValid());
        $this->assertEquals(
            1,
            $field->value
        );
    }
}
