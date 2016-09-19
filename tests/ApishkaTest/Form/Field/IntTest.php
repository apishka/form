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

        $this->assertSame(
            'int_field',
            $field->getName()
        );

        $this->assertSame(
            'int_field',
            $field->name
        );

        $this->assertSame(
            'int_field',
            $field->getStructureName()
        );

        $field->setName('int_field_2');

        $this->assertSame(
            'int_field_2',
            $field->getName()
        );

        $this->assertSame(
            'int_field_2',
            $field->name
        );

        $this->assertSame(
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
        $this->assertSame(
            100,
            $field->value
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
     * Test required
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
     *
     * @backupGlobals enabled
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
        $field = $this->getField('int_field');
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
        $field = $this->getField('int_field');
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
     * @backupGlobals            enabled
     * @expectedException        \Apishka\Transformer\FriendlyException
     * @expectedExceptionMessage cannot be empty
     */

    public function testDefaultRequiredValueWithBlankRequest()
    {
        $field = $this->getField('int_field');
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
        $field = $this->getField('int_field', false);
        $field->setDefault(100);

        $this->assertTrue($field->isValid());
        $this->assertSame(
            100,
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
        $field = $this->getField('int_field')
            ->setValues($values)
        ;

        $_REQUEST = array(
            $field->name => $value,
        );

        $this->assertTrue($field->isValid());
        $this->assertSame(
            (int) $value,
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
            array(1, ['1' => 'test']),
            array(-1, ['-1' => 'test']),
            array(true, ['1' => 'test']),
            array('1', function () {return array(1 => 123);}),
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
        $field = $this->getField('int_field')
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
            array(function () {}, ['test' => 'test']),
            array(new \StdClass(), ['test' => 'test']),
        );
    }

    /**
     * Test request getter
     *
     * @backupGlobals            enabled
     */

    public function testRequestGetter()
    {
        $field = $this->getField('int_field')
            ->setRequestGetter(
                function()
                {
                    return 100;
                }
            )
        ;

        $_REQUEST = array(
            $field->name => 200,
        );

        $this->assertTrue($field->isValid());
        $this->assertSame(
            (int) 100,
            $field->value
        );
    }
}
