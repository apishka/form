<?php

/**
 * Apishka test form form abstract test
 */

class ApishkaTest_Form_FormAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get form
     *
     * @param bool $is_sent
     */

    protected function getForm()
    {
        return $this->getMockForAbstractClass('Apishka_Form_FormAbstract');
    }

    /**
     * Test is sent
     */

    public function testIsSentWithoudRequest()
    {
        $form = $this->getForm();

        $this->assertFalse($form->isSent());
    }
}
