<?php

/**
 * Form time field test
 */
class ApishkaTest_Form_Field_TimeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Get form
     *
     * @param bool $is_sent
     */
    protected function getForm($is_sent)
    {
        $stub = $this->getMockBuilder('Apishka_Form_FormAbstract')
            ->setMockClassName('ApishkaTest_Form_Field_TimeTest_Form')
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
        $field = Apishka_Form_Field_Time::apishka($name);
        $field->initialize($this->getForm($is_sent));

        return $field;
    }

    /**
     * Test name
     */
    public function testName()
    {
        $field = $this->getField('time_field');

        $this->assertSame(
            'time_field',
            $field->getName()
        );

        $this->assertSame(
            'time_field',
            $field->name
        );

        $this->assertSame(
            'time_field',
            $field->getStructureName()
        );

        $field->setName('time_field_2');

        $this->assertSame(
            'time_field_2',
            $field->getName()
        );

        $this->assertSame(
            'time_field_2',
            $field->name
        );

        $this->assertSame(
            'time_field',
            $field->getStructureName()
        );
    }

    /**
     * Test value with empty request
     */
    public function testValueWithEmptyRequest()
    {
        $field = $this->getField('time_field');

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
        $field = $this->getField('time_field');

        $_POST = [
            $field->name => '10:11:12',
        ];

        $this->assertTrue($field->isValid());
        $this->assertSame(
            '10:11:12',
            $field->value
        );
    }

    /**
     * Test not required
     */
    public function testNotRequired()
    {
        $field = $this->getField('time_field');

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
        $field = $this->getField('time_field');
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
        $field = $this->getField('time_field');

        $_POST = [
            $field->name => '',
        ];

        $this->assertTrue($field->isValid());
        $this->assertNull($field->value);
    }

    /**
     * Test default value
     */
    public function testDefaultValue()
    {
        $field = $this->getField('time_field');
        $field->setDefault('13:13:44');

        $this->assertTrue($field->isValid());
        $this->assertSame(
            '13:13:44',
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
        $field = $this->getField('time_field');
        $field->setDefault('13:13:44');

        $_POST = [
            $field->name => '',
        ];

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
        $field = $this->getField('time_field');
        $field->setRequired(true);
        $field->setDefault('default_value');

        $_POST = [
            $field->name => '',
        ];

        $this->assertFalse($field->isValid());

        throw $field->getError();
    }

    /**
     * Test default value for non sent form
     */
    public function testDefaultValueForNonSentForm()
    {
        $field = $this->getField('time_field', false);
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
        $field = $this->getField('time_field')
            ->setValues($values)
        ;

        $_POST = [
            $field->name => $value,
        ];

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
        return [
            ['10:11:12', ['10:11:12' => 'test']],
            ['10:11:12', function () {return ['10:11:12' => 123]; }],
        ];
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
        $field = $this->getField('time_field')
            ->setValues($values)
        ;

        $_POST = [
            $field->name => $value,
        ];

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
        return [
            ['25:12:14', ['25:12:14' => 'test']],
            ['1986-02-30 14:12:14', ['1986-02-30 14:12:14' => 'test']],
            ['1986-02-30', ['1986-02-30' => 'test']],
            [1, ['test' => 'test']],
            [1.2, ['test' => 'test']],
            [true, ['test' => 'test']],
            ['test', ['test1' => 'test1']],
            [function () {}, ['test' => 'test']],
            [new \StdClass(), ['test' => 'test']],
        ];
    }
}
