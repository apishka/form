<?php

/**
 * Array type test
 */

class ApishkaTest_Form_Field_SignatureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get form
     *
     * @param bool $is_sent
     */

    protected function getForm($is_sent)
    {
        $stub = $this->getMockBuilder('Apishka_Form_FormAbstract')
            ->setMockClassName('ApishkaTest_Form_Field_SignatureTest_Form')
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
        $field = Apishka_Form_Field_Signature::apishka($name);
        $field->initialize($this->getForm($is_sent));

        return $field;
    }

    /**
     * Test name
     */

    public function testName()
    {
        $field = $this->getField('signature_test');

        $this->assertSame(
            'signature_test_dbfa21668cfb0bd4794172328e0eccc8',
            $field->getName()
        );

        $this->assertSame(
            'signature_test_dbfa21668cfb0bd4794172328e0eccc8',
            $field->name
        );

        $field->setName('signature_test_2');

        $this->assertSame(
            'signature_test_2_46b789da4432c263f116eba8ecde5cbf',
            $field->getName()
        );

        $this->assertSame(
            'signature_test_2_46b789da4432c263f116eba8ecde5cbf',
            $field->name
        );
    }

    /**
     * Test structure name
     */

    public function testStructureName()
    {
        $field = $this->getField('signature_test');

        $this->assertSame(
            'signature_test',
            $field->getStructureName()
        );

        $this->assertSame(
            'signature_test_dbfa21668cfb0bd4794172328e0eccc8',
            $field->getName()
        );

        $field->setStructureName('signature_test_2');

        $this->assertSame(
            'signature_test_2',
            $field->getStructureName()
        );

        $this->assertSame(
            'signature_test_dbfa21668cfb0bd4794172328e0eccc8',
            $field->getName()
        );
    }

    /**
     * Test value with empty request
     *
     * @expectedException Apishka\Transformer\FriendlyException
     * @expectedExceptionMessage cannot be empty
     */

    public function testValueWithEmptyRequest()
    {
        $field = $this->getField('signature');

        $this->assertSame(
            'edfaed6b51dedc42b21d58134f1afe93',
            $field->getValue()
        );

        $this->assertFalse($field->isValid());
        $this->assertNull($field->value);

        throw $field->getError();
    }

    /**
     * Test value
     *
     * @backupGlobals enabled
     */

    public function testValueWithRequest()
    {
        $field = $this->getField('signature');

        $_REQUEST = array(
            $field->name => 'edfaed6b51dedc42b21d58134f1afe93',
        );

        $this->assertTrue($field->isValid());
        $this->assertSame(
            'edfaed6b51dedc42b21d58134f1afe93',
            $field->value
        );
    }

    /**
     * Test value with wrong request
     *
     * @backupGlobals enabled
     * @expectedException Apishka\Transformer\FriendlyException
     * @expectedExceptionMessage wrong signature
     */

    public function testValueWithWrongRequest()
    {
        $field = $this->getField('signature');

        $_REQUEST = array(
            $field->name => 'foo',
        );

        $this->assertFalse($field->isValid());
        $this->assertSame(
            'foo',
            $field->value
        );

        throw $field->getError();
    }

    /**
     * Test set default
     *
     * @expectedException LogicException
     * @expectedExceptionMessage signature field not supports default values
     */

    public function testSetDefault()
    {
        $field = $this->getField('signature');
        $field->setDefault('test');
    }
}
