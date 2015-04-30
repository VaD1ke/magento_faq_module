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
     * Prepare columns for grid
     *
     * @return void
     */
    public function testPreparesColumns()
    {
        $this->replaceByMock('singleton', 'core/session', $this->getModelMock('core/session', ['start']));

        $block = $this->getBlockMock('oggetto_faq/adminhtml_faq_grid',
            [ 'addColumn', '_prepareMassactionBlock']
        );

        for ($i = 0; $i < 7; $i++) {
            $block->expects($this->at($i))
                ->method('addColumn')
                ->with(
                    $this->equalTo($this->expected($i)->getId()),
                    $this->equalTo($this->expected($i)->getParams())
                )
                ->willReturnSelf();
        }

        $block->toHtml();
    }

    /**
     * Prepare massaction for deleting questions
     *
     * @return void
     */
    public function testPreparesMassactionForDelete()
    {
        $block = $this->getBlockMock('oggetto_faq/adminhtml_faq_grid',
            [
                'addColumn',
                '_prepareCollection',
                '_prepareColumns',
                'setMassactionIdField',
                'getMassactionBlock',
                'getMassactionBlockName',
                'getLayout',
                'setChild',
                'getUrl',
                '_prepareMassactionColumn'
            ]);

        $massBlock = $this->getBlockMock('adminhtml/widget_grid_massaction',
            ['setFormFieldName', 'addItem', 'isAvailable']);

        $massBlock->expects($this->once())
            ->method('isAvailable')
            ->willReturn(true);

        $massBlock->expects($this->once())
            ->method('setFormFieldName')
            ->with($this->equalTo('questions'));

        $massBlock->expects($this->at(1))
            ->method('addItem')
            ->with(
                $this->equalTo('delete'),
                $this->equalTo([
                    'label'   => 'Delete',
                    'url'     => '*/*/massDelete'
                ])
            );

        $layout = $this->getModelMock('core/layout', ['createBlock']);

        $block->expects($this->any())
            ->method('setChild')
            ->with(
                $this->equalTo('massaction'),
                $this->equalTo($massBlock)
            );

        $block->expects($this->any())
            ->method('getLayout')
            ->will($this->returnValue($layout));

        $layout->expects($this->any())
            ->method('createBlock')
            ->with($this->equalTo('massaction'))
            ->willReturn($massBlock);

        $block->expects($this->once())
            ->method('getMassactionBlockName')
            ->willReturn('massaction');

        $block->expects($this->any())
            ->method('getMassactionBlock')
            ->willReturn($massBlock);

        $block->expects($this->any())
            ->method('getUrl')
            ->with($this->anything())
            ->will($this->returnArgument(0));

        $block->expects($this->once())
            ->method('setMassactionIdField')
            ->with($this->equalTo('question_id'));

        $block->toHtml();
    }


    /**
     * Check url for rows in grid
     *
     * @return void
     */
    public function testChecksUrlForRowInGrid()
    {
        $testValue = 'test';

        $questions = $this->getModelMock('oggetto_faq/questions', ['getId']);

        $questions->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(777));

        $block = $this->getBlockMock('oggetto_faq/adminhtml_faq_grid', ['getUrl']);

        $block->expects($this->once())
            ->method('getUrl')
            ->with(
                $this->equalTo('*/*/edit'),
                $this->equalTo(['id' => 777])
            )
            ->willReturn($testValue);

        $this->assertEquals($testValue, $block->getRowUrl($questions));
    }
}
