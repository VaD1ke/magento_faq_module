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
 * Block test class for displaying questions
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Block
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Test_Block_Questions extends Oggetto_Faq_Test_Case_Block_Page
{
    /**
     * Block questions
     *
     * @var Mage_Core_Block_Template
     */
    protected $_questionsBlock;

    /**
     * Set up initial variables
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_questionsBlock = new Oggetto_Faq_Block_Questions;
    }

    /**
     * Check class alias
     *
     * @return void
     */
    public function testChecksClassAlias()
    {
        $this->assertInstanceOf('Oggetto_Faq_Block_Questions',
            $this->getBlockMock('oggetto_faq/questions'));
    }

    /**
     * Initialize itself in constructor
     *
     * @return void
     */
    public function testInitsItselfInConstructor()
    {
        $questions = $this->getResourceModelMock('oggetto_faq/questions_collection',
            ['newOnTop', 'answered']);

        $questions->expects($this->once())
            ->method('newOnTop')
            ->willReturnSelf();

        $questions->expects($this->once())
            ->method('answered');

        $this->replaceByMock('resource_model', 'oggetto_faq/questions_collection', $questions);

        $block = new Oggetto_Faq_Block_Questions;

        $this->assertEquals($questions, $block->getCollection());

    }

    /**
     * Tests ask page is disabled
     *
     * @return void
     */
    public function testChecksAskOptionIsDisabled()
    {
        $value = 4;

        $helperDataMock = $this->getHelperMock('oggetto_faq/data', ['isDisabledAddingOptionData']);

        $helperDataMock->expects($this->once())
            ->method('isDisabledAddingOptionData')
            ->will($this->returnValue($value));

        $this->replaceByMock('helper', 'oggetto_faq', $helperDataMock);

        $this->assertEquals($value, $this->_questionsBlock->isDisabledAsk());
    }

    /**
     * Get url for asking question page
     *
     * @return void
     */
    public function testGetsUrlForAskQuestionPage()
    {
        $testValue = 'test';

        $this->createAndReplaceMockForGettingUrl('ask', $testValue);

        $this->assertEquals($testValue, $this->_questionsBlock->getQuestionAskUrl());
    }

    /**
     * Get child html from block
     *
     * @return void
     */
    public function testGetsChildHtmlBlock()
    {
        $mockBlock = $this->getBlockMock('oggetto_faq/questions', ['getChildHtml']);

        $pager = new Mage_Page_Block_Html_Pager;

        $mockBlock->expects($this->once())
            ->method('getChildHtml')
            ->with($this->equalTo('pager'))
            ->will($this->returnValue($pager));

        $this->replaceByMock('block', 'oggetto_faq/questions', $mockBlock);

        $mockBlock->getPagerHtml();
    }

    /**
     * Add pager block while preparing
     *
     * @return void
     */
    public function testAddsPagerInPreparing()
    {
        $blockMock = $this->getBlockMock('oggetto_faq/questions',
            ['getLayout', 'createBlock', 'setChild', 'getCollection']);

        $questions = $this->getResourceModelMock('oggetto_faq/questions_collection',
            ['load']);

        $pagerMock = $this->getMock('Mage_Page_block_Html_Pager', ['setCollection', 'setAvailableLimit']);

        $questions->expects($this->once())
            ->method('load');

        $blockMock->expects($this->exactly(2))
            ->method('getCollection')
            ->willReturn($questions);

        $blockMock->expects($this->once())
            ->method('getLayout')
            ->willReturnSelf();

        $pagerMock->expects($this->once())
            ->method('setAvailableLimit')
            ->with([5 => 5, 10 => 10, 20 => 20, 'all' => 'all']);

        $pagerMock->expects($this->once())
            ->method('setCollection')
            ->with($this->equalTo($questions))
            ->willReturnSelf();


        $blockMock->expects($this->once())
            ->method('createBlock')
            ->with($this->equalTo('page/html_pager'), $this->equalTo('custom.pager'))
            ->willReturn($pagerMock);

        $blockMock->expects($this->once())
            ->method('setChild')
            ->with($this->equalTo('pager'), $this->anything())
            ->willReturn($pagerMock);

        $blockMock->setLayout(new Mage_Core_Model_Layout);
    }
}
