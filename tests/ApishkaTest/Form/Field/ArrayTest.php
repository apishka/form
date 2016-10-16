<?php

/**
 * Apishka test form field array test
 */

class ApishkaTest_Form_Field_ArrayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get form
     *
     * @param bool $is_sent
     */

    protected function getForm($is_sent)
    {
        $stub = $this->getMockBuilder('Apishka_Form_FormAbstract')
            ->setMockClassName('ApishkaTest_Form_Field_ArrayTest_Form')
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
        $field = Apishka_Form_Field_Array::apishka($name);
        $field->initialize($this->getForm($is_sent));

        return $field;
    }

    /**
     * Test name
     */

    public function testName()
    {
        $field = $this->getField('array_field');

        $this->assertSame(
            'array_field',
            $field->getName()
        );

        $this->assertSame(
            'array_field',
            $field->name
        );

        $this->assertSame(
            'array_field',
            $field->getStructureName()
        );

        $field->setName('array_field_2');

        $this->assertSame(
            'array_field_2',
            $field->getName()
        );

        $this->assertSame(
            'array_field_2',
            $field->name
        );

        $this->assertSame(
            'array_field',
            $field->getStructureName()
        );
    }

    /**
     * Test value with empty request
     */

    public function testValueWithEmptyRequest()
    {
        $field = $this->getField('array_field');

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
        $field = $this->getField('array_field');

        $_REQUEST = array(
            $field->name => array(1,2,3),
        );

        $this->assertTrue($field->isValid());
        $this->assertSame(
            array(1, 2, 3),
            $field->value
        );
    }

    /**
     * Test not required
     */

    public function testNotRequired()
    {
        $field = $this->getField('array_field');

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
        $field = $this->getField('array_field');
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
        $field = $this->getField('array_field');

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
        $field = $this->getField('array_field');
        $field->setDefault(array(1));

        $this->assertTrue($field->isValid());
        $this->assertSame(
            array(1),
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
        $field = $this->getField('array_field');
        $field->setDefault(array(1));

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
        $field = $this->getField('array_field');
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
        $field = $this->getField('array_field', false);
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
        $field = $this->getField('array_field')
            ->setValues($values)
        ;

        $_REQUEST = array(
            $field->name => $value,
        );

        $this->assertTrue($field->isValid());
        $this->assertSame(
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
            array(array(1), ['1' => 'test', '2' => 'test2', '3' => 'test3']),
            array(array(-1), ['-1' => 'test', '-2' => 'test 2', '-3' => 'test 3']),
            array(array(true), ['1' => 'yes', '0' => 'no']),
            array(array('1'), function () {return array(1 => 123, 2 => 345);}),
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
        $field = $this->getField('array_field')
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
            array(array(1), ['test' => 'test']),
            array(array(1.2), ['test' => 'test']),
            array(array(true), ['test' => 'test']),
            array(array('test'), ['test1' => 'test1']),
            array(function () {}, ['test' => 'test']),
            array(new \StdClass(), ['test' => 'test']),
        );
    }

    /**
     * Test element good values
     *
     * @backupGlobals enabled
     */

    public function testElementGoodValues()
    {
        $field = $this->getField('array_field')
            ->setElement(
                Apishka_Form_Field_Int::element()
            )
        ;

        $_REQUEST = array(
            $field->name => array(
                '1',
                '2',
                '5',
            ),
        );

        $this->assertTrue($field->isValid());
        $this->assertSame(
            array(
                1,
                2,
                5,
            ),
            $field->value
        );
    }
}
