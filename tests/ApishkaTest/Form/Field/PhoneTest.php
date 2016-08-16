<?php

/**
 * Form phone field test
 */

class ApishkaTest_Form_Field_PhoneTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get form
     *
     * @param bool $is_sent
     */

    protected function getForm($is_sent)
    {
        $stub = $this->getMockBuilder('Apishka_Form_FormAbstract')
            ->setMockClassName('ApishkaTest_Form_Field_PhoneTest_Form')
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
     * @param string $code
     *
     * @return Apishka_Form_Field_Signature
     */

    protected function getField($name, $is_sent = true, $code = 'RU')
    {
        $field = Apishka_Form_Field_Phone::apishka($name);
        $field->setCountryCode($code);
        $field->initialize($this->getForm($is_sent));

        return $field;
    }

    /**
     * Test name
     */

    public function testName()
    {
        $field = $this->getField('phone_field');

        $this->assertEquals(
            'phone_field',
            $field->getName()
        );

        $this->assertEquals(
            'phone_field',
            $field->name
        );

        $this->assertEquals(
            'phone_field',
            $field->getStructureName()
        );

        $field->setName('phone_field_2');

        $this->assertEquals(
            'phone_field_2',
            $field->getName()
        );

        $this->assertEquals(
            'phone_field_2',
            $field->name
        );

        $this->assertEquals(
            'phone_field',
            $field->getStructureName()
        );
    }

    /**
     * Test value with empty request
     */

    public function testValueWithEmptyRequest()
    {
        $field = $this->getField('phone_field');

        $this->assertNull($field->getValue());

        $this->assertTrue($field->isValid());
        $this->assertNull($field->value);
    }

    /**
     * Test value
     *
     * @backupGlobals enabled
     */

    public function testValueWithRequest()
    {
        $field = $this->getField('phone_field');

        $_REQUEST = array(
            $field->name => '+79161234567',
        );

        $this->assertTrue($field->isValid());
        $this->assertEquals(
            '+79161234567',
            $field->value
        );
    }

    /**
     * Test not required
     */

    public function testNotRequired()
    {
        $field = $this->getField('phone_field');

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
        $field = $this->getField('phone_field');
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
        $field = $this->getField('phone_field');

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
        $field = $this->getField('phone_field');
        $field->setDefault('+79161234567');

        $this->assertTrue($field->isValid());
        $this->assertEquals(
            '+79161234567',
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
        $field = $this->getField('phone_field');
        $field->setDefault('default_value');

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
        $field = $this->getField('phone_field');
        $field->setRequired(true);
        $field->setDefault('default_value');

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
        $field = $this->getField('phone_field', false);
        $field->setDefault('default_value');

        $this->assertTrue($field->isValid());
        $this->assertEquals(
            'default_value',
            $field->value
        );
    }

    /**
     * Test good values
     *
     * @dataProvider  goodValuesProvider
     * @backupGlobals enabled
     *
     * @param mixed $value
     * @param array $values
     */

    public function testGoodValues($value, $values)
    {
        $field = $this->getField('string_field')
            ->setValues($values)
        ;

        $_REQUEST = array(
            $field->name => $value,
        );

        $this->assertTrue($field->isValid());
        $this->assertEquals(
            $value,
            $field->value
        );
    }

    /**
     * Good data provider
     *
     * @return array
     */

    public function goodValuesProvider()
    {
        return array(
            array('+79161234567', ['+79161234567' => 'Eugene Reich']),
            array('79161234567', ['+79161234567' => 'Eugene Reich']),
        );
    }

    /**
     * Test good values
     *
     * @dataProvider             badValuesProvider
     * @backupGlobals            enabled
     * @expectedException        \Apishka\Transformer\Exception
     * @expectedExceptionMessage wrong phone format
     *
     * @param mixed $value
     * @param array $values
     */

    public function testBadValues($value, $values)
    {
        $field = $this->getField('string_field')
            ->setValues($values)
        ;

        $_REQUEST = array(
            $field->name => $value,
        );

        $this->assertFalse($field->isValid());

        throw $field->getError();
    }

    /**
     * Good data provider
     *
     * @return array
     */

    public function badValuesProvider()
    {
        return array(
            array(1, ['test' => 'test']),
            array(1.2, ['test' => 'test']),
            array(true, ['test' => 'test']),
            array('test', ['test1' => 'test1']),
        );
    }
}
