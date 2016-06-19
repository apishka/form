<?php

/**
 * Array type test
 */

class ApishkaTest_Form_Field_SignatureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get form
     *
     * @return void
     */

    protected function getForm($is_sent = true)
    {
        $stub = $this->getMockBuilder('Apishka_Form_FormAbstract')
            ->setMockClassName('Test_Form')
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
     *
     * @return Apishka_Form_Field_Signature
     */

    protected function getField($name)
    {
        $field = Apishka_Form_Field_Signature::apishka($name);
        $field->initialize($this->getForm());

        return $field;
    }

    /**
     * Test name
     */

    public function testName()
    {
        $field = $this->getField('signature_test');

        $this->assertEquals(
            'signature_test_01b11c5336cb3bf56a97a6bdfda37d12',
            $field->getName()
        );

        $this->assertEquals(
            'signature_test_01b11c5336cb3bf56a97a6bdfda37d12',
            $field->name
        );

        $field->setName('signature_test_2');

        $this->assertEquals(
            'signature_test_2_01b11c5336cb3bf56a97a6bdfda37d12',
            $field->getName()
        );

        $this->assertEquals(
            'signature_test_2_01b11c5336cb3bf56a97a6bdfda37d12',
            $field->name
        );
    }

    /**
     * Test structure name
     */

    public function testStructureName()
    {
        $field = $this->getField('signature_test');

        $this->assertEquals(
            'signature_test',
            $field->getStructureName()
        );
    }
}
