<?php

/**
 * Form email field test
 */

class ApishkaTest_Form_Field_EmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get form
     *
     * @param bool $is_sent
     */

    protected function getForm($is_sent)
    {
        $stub = $this->getMockBuilder('Apishka_Form_FormAbstract')
            ->setMockClassName('ApishkaTest_Form_Field_EmailTest_Form')
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
        $field = Apishka_Form_Field_Email::apishka($name);
        $field->initialize($this->getForm($is_sent));

        return $field;
    }

    /**
     * Test name
     */

    public function testName()
    {
        $field = $this->getField('email_field');

        $this->assertEquals(
            'email_field',
            $field->getName()
        );

        $this->assertEquals(
            'email_field',
            $field->name
        );

        $this->assertEquals(
            'email_field',
            $field->getStructureName()
        );

        $field->setName('email_field_2');

        $this->assertEquals(
            'email_field_2',
            $field->getName()
        );

        $this->assertEquals(
            'email_field_2',
            $field->name
        );

        $this->assertEquals(
            'email_field',
            $field->getStructureName()
        );
    }

    /**
     * Test value with empty request
     */

    public function testValueWithEmptyRequest()
    {
        $field = $this->getField('email_field');

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
        $field = $this->getField('email_field');

        $_REQUEST = array(
            $field->name => 'eugene.reich@gmail.com',
        );

        $this->assertTrue($field->isValid());
        $this->assertEquals(
            'eugene.reich@gmail.com',
            $field->value
        );
    }

    /**
     * Test not required
     */

    public function testNotRequired()
    {
        $field = $this->getField('email_field');

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
        $field = $this->getField('email_field');
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
        $field = $this->getField('email_field');

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
        $field = $this->getField('email_field');
        $field->setDefault('eugene.reich+default@gmail.com');

        $this->assertTrue($field->isValid());
        $this->assertEquals(
            'eugene.reich+default@gmail.com',
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
        $field = $this->getField('email_field');
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
        $field = $this->getField('email_field');
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
        $field = $this->getField('email_field', false);
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
            array('eugene.reich@gmail.com', ['eugene.reich@gmail.com' => 'Eugene Reich']),
        );
    }

    /**
     * Test good values
     *
     * @dataProvider             badValuesProvider
     * @backupGlobals            enabled
     * @expectedException        \Apishka\Transformer\Exception
     * @expectedExceptionMessage wrong email format
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
