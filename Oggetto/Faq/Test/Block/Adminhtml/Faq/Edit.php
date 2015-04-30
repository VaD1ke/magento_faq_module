<?php
/**
 * Oggetto FAQ extension for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the Oggetto FAQ module to newer versions in the future.
 * If you wish to customize the Oggetto FAQ module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @copyright  Copyright (C) 2015 Oggetto Web (http://oggettoweb.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Block faq for admin
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Block
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Test_Block_Adminhtml_Faq_Edit extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Initialize itself in constructor method
     *
     * @return void
     */
    public function testInitsItselfInConstructor()
    {
        $this->replaceByMock('singleton', 'core/session', $this->getModelMock('core/session', ['start']));

        $block = $this->getMockBuilder('Oggetto_Faq_Block_Adminhtml_Faq_Edit')
            ->disableOriginalConstructor()
            ->setMethods(['updateButton', 'addButton'])
            ->getMock();

        $reflected = new ReflectionClass('Oggetto_Faq_Block_Adminhtml_Faq_Edit');

        $constructor = $reflected->getConstructor();

        $constructor->invoke($block);

        $layout = $this->getModelMock('core/layout', ['createBlock']);

        $layout->expects($this->at(0))
            ->method('createBlock')
            ->with($this->equalTo('oggetto_faq/adminhtml_faq_edit_form'))
            ->willReturn(new Mage_Core_Block_Template);

        $block->setLayout($layout);
    }

    /**
     * Get header text
     *
     * @return void
     */
    public function testGetsHeaderText()
    {
        $testValue = 'test';

        $block = $this->getMockBuilder('Oggetto_Faq_Block_Adminhtml_Faq_Edit')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        Mage::unregister('current_questions');
        $questions = $this->getModelMock('oggetto_faq/questions', ['getId']);

        $questions->expects($this->once())
            ->method('getId')
            ->willReturn(123);

        Mage::register('current_questions', $questions);

        $helper = $this->getHelperMock('oggetto_faq/data', ['__']);

        $helper->expects($this->once())
            ->method('__')
            ->with($this->equalTo("Edit Question item %s"), $this->equalTo(123))
            ->will($this->returnValue($testValue));

        $this->replaceByMock('helper', 'oggetto_faq', $helper);

        $this->assertEquals($testValue, $block->getHeaderText());
    }
}
