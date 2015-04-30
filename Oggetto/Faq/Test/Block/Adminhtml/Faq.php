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
class Oggetto_Faq_Test_Block_Adminhtml_Faq extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Initialize itself in constructor method
     *
     * @return void
     */
    public function testInitsItselfInConstructor()
    {

        $this->replaceByMock('singleton', 'core/session', $this->getModelMock('core/session'));

        $helper = $this->getHelperMock('oggetto_faq/data', ['__']);

        $helper->expects($this->once())
            ->method('__')
            ->with($this->equalTo('FAQ Management'))
            ->willReturn('FAQ Management');

        $this->replaceByMock('helper', 'oggetto_faq', $helper);

        $block = $this->getMockBuilder('Oggetto_Faq_Block_Adminhtml_Faq')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $reflected = new ReflectionClass('Oggetto_Faq_Block_Adminhtml_Faq');

        $constructor = $reflected->getConstructor();

        $constructor->invoke($block);

        $this->assertEquals('FAQ Management', $block->getHeaderText());

        $layout = $this->getModelMock('core/layout', ['createBlock']);

        $layout->expects($this->at(0))
            ->method('createBlock')
            ->with(
                $this->equalTo('oggetto_faq/adminhtml_faq_grid'),
                $this->equalTo('adminhtml_faq.grid')
            )
            ->willReturn(new Mage_Core_Block_Template());

        $block->setLayout($layout);
    }
}
