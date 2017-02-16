<?php

/**
 * Apishka test form form abstract test
 */

class ApishkaTest_Form_FormAbstractTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Get form
     *
     * @param bool $is_sent
     */

    protected function getForm()
    {
        $stub = $this->getMockBuilder('Apishka_Form_FormAbstract')
            ->setMockClassName('ApishkaTest_Form_Field_TestForm')
            ->setMethods(['drawTpl'])
            ->getMock()
        ;

        $stub->expects($this->any())
            ->method('drawTpl')
            ->willReturn(null)
        ;

        return $stub;
    }

    /**
     * Test is sent
     */

    public function testIsSentWithoudRequest()
    {
        $form = $this->getForm();

        $this->assertFalse($form->isSent());
        $this->assertFalse($form->isValid());
    }
}
