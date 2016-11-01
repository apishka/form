<?php

/**
 * Form date field test
 */

class ApishkaTest_Form_Field_DateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get form
     *
     * @param bool $is_sent
     */

    protected function getForm($is_sent)
    {
        $stub = $this->getMockBuilder('Apishka_Form_FormAbstract')
            ->setMockClassName('ApishkaTest_Form_Field_DateTest_Form')
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
        $field = Apishka_Form_Field_Date::apishka($name);
        $field->initialize($this->getForm($is_sent));

        return $field;
    }

    /**
     * Test name
     */

    public function testName()
    {
        $field = $this->getField('date_field');

        $this->assertSame(
            'date_field',
            $field->getName()
        );

        $this->assertSame(
            'date_field',
            $field->name
        );

        $this->assertSame(
            'date_field',
            $field->getStructureName()
        );

        $field->setName('date_field_2');

        $this->assertSame(
            'date_field_2',
            $field->getName()
        );

        $this->assertSame(
            'date_field_2',
            $field->name
        );

        $this->assertSame(
            'date_field',
            $field->getStructureName()
        );
    }

    /**
     * Test value with empty request
     */

    public function testValueWithEmptyRequest()
    {
        $field = $this->getField('date_field');

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
        $field = $this->getField('date_field');

        $_POST = array(
            $field->name => '1986-03-10',
        );

        $this->assertTrue($field->isValid());
        $this->assertSame(
            '1986-03-10',
            $field->value
        );
    }

    /**
     * Test not required
     */

    public function testNotRequired()
    {
        $field = $this->getField('date_field');

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
        $field = $this->getField('date_field');
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
        $field = $this->getField('date_field');

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
        $field = $this->getField('date_field');
        $field->setDefault('1986-03-10');

        $this->assertTrue($field->isValid());
        $this->assertSame(
            '1986-03-10',
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
        $field = $this->getField('date_field');
        $field->setDefault('1986-03-10');

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
        $field = $this->getField('date_field');
        $field->setRequired(true);
        $field->setDefault('default_value');

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
        $field = $this->getField('date_field', false);
        $field->setDefault('default_value');

        $this->assertTrue($field->isValid());
        $this->assertSame(
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
        $field = $this->getField('date_field')
            ->setValues($values)
        ;

        $_POST = array(
            $field->name => $value,
        );

        $this->assertTrue($field->isValid());
        $this->assertSame(
            (string) $value,
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
            array('1986-03-10', ['1986-03-10' => 'test']),
            array('1986-03-10', function () {return array('1986-03-10' => 123);}),
        );
    }

    /**
     * Test good values
     *
     * @dataProvider             badValuesProvider
     * @backupGlobals            enabled
     * @expectedException        \Apishka\Transformer\Exception
     * @expectedExceptionMessage wrong input format
     *
     * @param mixed $value
     * @param array $values
     */

    public function testBadValues($value, $values)
    {
        $field = $this->getField('date_field')
            ->setValues($values)
        ;

        $_POST = array(
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
            array('1986-02-30', ['1986-02-30' => 'test']),
            array(1, ['test' => 'test']),
            array(1.2, ['test' => 'test']),
            array(true, ['test' => 'test']),
            array('test', ['test1' => 'test1']),
            array(function () {}, ['test' => 'test']),
            array(new \StdClass(), ['test' => 'test']),
        );
    }
}
