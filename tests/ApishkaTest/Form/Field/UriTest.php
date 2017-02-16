<?php

/**
 * Form uri field test
 */

class ApishkaTest_Form_Field_UriTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Get form
     *
     * @param bool $is_sent
     */

    protected function getForm($is_sent)
    {
        $stub = $this->getMockBuilder('Apishka_Form_FormAbstract')
            ->setMockClassName('ApishkaTest_Form_Field_UriTest_Form')
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
     * @param string $code
     *
     * @return Apishka_Form_Field_Signature
     */

    protected function getField($name, $is_sent = true, $code = 'RU')
    {
        $field = Apishka_Form_Field_Uri::apishka($name);
        $field->initialize($this->getForm($is_sent));

        return $field;
    }

    /**
     * Test name
     */

    public function testName()
    {
        $field = $this->getField('uri_field');

        $this->assertSame(
            'uri_field',
            $field->getName()
        );

        $this->assertSame(
            'uri_field',
            $field->name
        );

        $this->assertSame(
            'uri_field',
            $field->getStructureName()
        );

        $field->setName('uri_field_2');

        $this->assertSame(
            'uri_field_2',
            $field->getName()
        );

        $this->assertSame(
            'uri_field_2',
            $field->name
        );

        $this->assertSame(
            'uri_field',
            $field->getStructureName()
        );
    }

    /**
     * Test value with empty request
     */

    public function testValueWithEmptyRequest()
    {
        $field = $this->getField('uri_field');

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
        $field = $this->getField('uri_field');

        $_POST = array(
            $field->name => 'http://example.com',
        );

        $this->assertTrue($field->isValid());
        $this->assertSame(
            'http://example.com',
            $field->value
        );
    }

    /**
     * Test not required
     */

    public function testNotRequired()
    {
        $field = $this->getField('uri_field');

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
        $field = $this->getField('uri_field');
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
        $field = $this->getField('uri_field');

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
        $field = $this->getField('uri_field');
        $field->setDefault('https://example.com');

        $this->assertTrue($field->isValid());
        $this->assertSame(
            'https://example.com',
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
        $field = $this->getField('uri_field');
        $field->setDefault('default_value');

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
        $field = $this->getField('uri_field');
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
        $field = $this->getField('uri_field', false);
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
        $field = $this->getField('string_field')
            ->setValues($values)
        ;

        $_POST = array(
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
            array('https://example.com/', ['https://example.com/' => 'Example']),
        );
    }

    /**
     * Test good values
     *
     * @dataProvider             badValuesProvider
     * @backupGlobals            enabled
     * @expectedException        \Apishka\Transformer\Exception
     * @expectedExceptionMessage wrong uri format
     *
     * @param mixed $value
     * @param array $values
     */

    public function testBadValues($value, $values)
    {
        $field = $this->getField('string_field')
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
            array(1, ['test' => 'test']),
            array(1.2, ['test' => 'test']),
            array(true, ['test' => 'test']),
            array('test', ['test1' => 'test1']),
        );
    }
}
