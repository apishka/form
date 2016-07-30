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
            ->setMockClassName('Test_Form')
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

        $this->assertEquals(
            'signature_test_8cc9791279d8571cdc35a4fbe9357a5c',
            $field->getName()
        );

        $this->assertEquals(
            'signature_test_8cc9791279d8571cdc35a4fbe9357a5c',
            $field->name
        );

        $field->setName('signature_test_2');

        $this->assertEquals(
            'signature_test_2_779f90f06b9104daca4d454b6188a9bc',
            $field->getName()
        );

        $this->assertEquals(
            'signature_test_2_779f90f06b9104daca4d454b6188a9bc',
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

        $this->assertEquals(
            'signature_test_8cc9791279d8571cdc35a4fbe9357a5c',
            $field->getName()
        );

        $field->setStructureName('signature_test_2');

        $this->assertEquals(
            'signature_test_2',
            $field->getStructureName()
        );

        $this->assertEquals(
            'signature_test_8cc9791279d8571cdc35a4fbe9357a5c',
            $field->getName()
        );
    }

    /**
     * Test value with empty request
     *
     * @expectedException Apishka\Validator\FriendlyException
     * @expectedExceptionMessage cannot be empty
     */

    public function testValueWithEmptyRequest()
    {
        $field = $this->getField('signature');

        $this->assertEquals(
            '82abf2f6354089771876c169ef39234d',
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
            $field->name => '82abf2f6354089771876c169ef39234d',
        );

        $this->assertTrue($field->isValid());
        $this->assertEquals(
            '82abf2f6354089771876c169ef39234d',
            $field->value
        );
    }

    /**
     * Test value with wrong request
     *
     * @backupGlobals enabled
     * @expectedException Apishka\Validator\FriendlyException
     * @expectedExceptionMessage wrong signature
     */

    public function testValueWithWrongRequest()
    {
        $field = $this->getField('signature');

        $_REQUEST = array(
            $field->name => 'foo',
        );

        $this->assertFalse($field->isValid());
        $this->assertEquals(
            'foo',
            $field->value
        );

        throw $field->getError();
    }
}
