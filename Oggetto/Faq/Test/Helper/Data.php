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
 * Helper data test class
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Helper
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Test_Helper_Data extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Tests adding option is disabled
     *
     * @return void
     *
     * @loadFixture
     */
    public function testChecksAddingOptionIsDisabled()
    {
        $isDisabled = Mage::helper('oggetto_faq')->isDisabledAddingOptionData();
        $this->assertEquals(1, $isDisabled);
    }

    /**
     * Tests return e-mail for support from store config
     *
     * @return void
     *
     * @loadFixture
     */
    public function testReturnsSupportEmailFromStoreConfig()
    {
        $supportEmail = Mage::helper('oggetto_faq')->getSupportEmail();
        $this->assertEquals('support@example.com', $supportEmail);
    }

    /**
     * Tests return name for support from store config
     *
     * @return void
     *
     * @loadFixture
     */
    public function testReturnsSupportNameFromStoreConfig()
    {
        $supportName = Mage::helper('oggetto_faq')->getSupportName();
        $this->assertEquals('Support', $supportName);
    }
}
