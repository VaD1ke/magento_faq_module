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
 * Block grid for admin
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Block
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Test_Block_Adminhtml_Faq_Grid extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Replace core session by mock
     *
     * @return void
     */
    protected function setUp()
    {
        $this->replaceByMock('singleton', 'core/session', $this->getModelMock('core/session', ['start']));
    }

    /**
     * Set question collection and add filter by answered status
     *
     * @return void
     */
    public function testSetsQuestionsCollectionAndFilterByAnsweredStatus()
    {
        $questions = $this->getResourceModelMock('oggetto_faq/questions_collection', ['newOnTop']);

        $questions->expects($this->once())
            ->method('newOnTop');


        $modelMock = $this->getModelMock('oggetto_faq/questions', ['getCollection']);

        $modelMock->expects($this->once())
            ->method('getCollection')
            ->willReturn($questions);

        $this->replaceByMock('model', 'oggetto_faq/questions', $modelMock);


        $blockMock = $this->getBlockMock('oggetto_faq/adminhtml_faq_grid', [
            '_prepareColumns',
            '_prepareMassaction',
            '_prepareMassactionBlock',
            'setDefaultFilter'
        ]);

        $blockMock->expects($this->once())
            ->method('setDefaultFilter')
            ->with(['is_answered' => 0]);

        $blockMock->toHtml();
    }

    /**
     * Prepare columns for grid
     *
     * @return void
     */
    public function testPreparesColumns()
    {
        $block = new Oggetto_Faq_Block_Adminhtml_Faq_Grid;
        $layout = new Mage_Core_Model_Layout;
        $block->setLayout($layout);
        $block->toHtml();

        $blockColumns = array_keys($block->getColumns());

        $this->assertEquals($this->expected()->getColumns(), $blockColumns);
    }
}
