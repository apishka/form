<?php

/**
 * Apishka test form field float test
 */
class ApishkaTest_Form_Field_FloatTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Get form
     *
     * @param bool $is_sent
     */
    protected function getForm($is_sent)
    {
        $stub = $this->getMockBuilder('Apishka_Form_FormAbstract')
            ->setMockClassName('ApishkaTest_Form_Field_FloatTest_Form')
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
        $field = Apishka_Form_Field_Float::apishka($name);
        $field->initialize($this->getForm($is_sent));

        return $field;
    }

    /**
     * Test name
     */
    public function testName()
    {
        $field = $this->getField('float_filed');

        $this->assertSame(
            'float_filed',
            $field->getName()
        );

        $this->assertSame(
            'float_filed',
            $field->name
        );

        $this->assertSame(
            'float_filed',
            $field->getStructureName()
        );

        $field->setName('float_filed_2');

        $this->assertSame(
            'float_filed_2',
            $field->getName()
        );

        $this->assertSame(
            'float_filed_2',
            $field->name
        );

        $this->assertSame(
            'float_filed',
            $field->getStructureName()
        );
    }

    /**
     * Test value with empty request
     */
    public function testValueWithEmptyRequest()
    {
        $field = $this->getField('float_filed');

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
        $field = $this->getField('float_filed');

        $_POST = [
            $field->name => 'edfaed6b51dedc42b21d58134f1afe93',
        ];

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
        $field = $this->getField('float_filed');

        $_POST = [
            $field->name => '100.1',
        ];

        $this->assertTrue($field->isValid());
        $this->assertSame(
            100.1,
            $field->value
        );
    }

    /**
     * Test not required
     */
    public function testNotRequired()
    {
        $field = $this->getField('float_filed');

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
        $field = $this->getField('float_filed');
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
        $field = $this->getField('float_filed');

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
        $field = $this->getField('float_filed');
        $field->setDefault(1.0);

        $this->assertTrue($field->isValid());
        $this->assertSame(
            1.0,
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
        $field = $this->getField('float_filed');
        $field->setDefault(1);

        $_POST = [
            $field->name => '',
        ];

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
        $field = $this->getField('float_filed');
        $field->setRequired(true);
        $field->setDefault(1);

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
        $field = $this->getField('float_filed', false);
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
        $field = $this->getField('float_filed')
            ->setValues($values)
        ;

        $_POST = [
            $field->name => $value,
        ];

        $this->assertTrue($field->isValid());
        $this->assertSame(
            (float) $value,
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
            [1, ['1' => 'test']],
            [-1, ['-1' => 'test']],
            [true, ['1' => 'test']],
            ['1', function () {return [1 => 123]; }],
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
        $field = $this->getField('float_filed')
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
            [1, ['test' => 'test']],
            [1.2, ['test' => 'test']],
            [true, ['test' => 'test']],
            ['test', ['test1' => 'test1']],
            [function () {}, ['test' => 'test']],
            [new \StdClass(), ['test' => 'test']],
        ];
    }
}
