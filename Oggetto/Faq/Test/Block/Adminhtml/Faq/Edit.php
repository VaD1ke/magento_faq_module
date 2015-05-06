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
 * Block edit question for admin
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Block
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Test_Block_Adminhtml_Faq_Edit extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Check form child block group and controller
     *
     * @return void
     */
    public function testChecksChildBlockGroupAndController()
    {
        $this->replaceByMock('singleton', 'core/session', $this->getModelMock('core/session', ['start']));

        $expectedBlockGroup = 'oggetto_faq';
        $expectedController = 'adminhtml_faq';
        $expectedMode = 'edit';

        $block = new Oggetto_Faq_Block_Adminhtml_Faq_Edit;
        $layout = new Mage_Core_Model_Layout;
        $block->setLayout($layout);

        $this->assertEquals($expectedBlockGroup . '/' . $expectedController . '_' . $expectedMode . '_form',
                $block->getChildData('form')['type']);
    }

    /**
     * Get header text
     *
     * @return void
     */
    public function testGetsHeaderText()
    {
        $testId = 123;

        $block = $this->getMockBuilder('Oggetto_Faq_Block_Adminhtml_Faq_Edit')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();


        $questions = $this->getModelMock('oggetto_faq/questions', ['getId']);

        $questions->expects($this->once())
            ->method('getId')
            ->willReturn($testId);


        Mage::unregister('current_questions');
        Mage::register('current_questions', $questions);

        $this->assertEquals("Edit Question item $testId", $block->getHeaderText());
    }

    /**
     * Return current questions model from mage registry
     *
     * @return void
     */
    public function testReturnsCurrentQuestionsModel()
    {
        $block = $this->getMockBuilder('Oggetto_Faq_Block_Adminhtml_Faq_Edit')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $questions = Mage::getModel('oggetto_faq/questions');

        Mage::unregister('current_questions');
        Mage::register('current_questions', $questions);

        $this->assertEquals($questions, $block->getCurrentQuestionsModel());
    }

    public static function tearDownAfterClass()
    {
        Mage::unregister('current_questions');
    }
}
