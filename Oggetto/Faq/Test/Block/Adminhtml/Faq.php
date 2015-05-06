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
     * Check child block group and controller
     *
     * @return void
     */
    public function testChecksChildBlockGroupAndController()
    {
        $this->replaceByMock('singleton', 'core/session', $this->getModelMock('core/session', ['start']));

        $expectedBlockGroup = 'oggetto_faq';
        $expectedController = 'adminhtml_faq';

        $block = new Oggetto_Faq_Block_Adminhtml_Faq;
        $layout = new Mage_Core_Model_Layout;
        $block->setLayout($layout);

        $this->assertEquals($expectedBlockGroup . '/' . $expectedController . '_grid',
                $block->getChildData('grid')['type']);
    }
}
