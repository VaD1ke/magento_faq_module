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
class Oggetto_Faq_Test_Block_Ask extends EcomDev_PHPUnit_Test_Case_Controller
{
    /**
     * Block ask
     *
     * @var Mage_Core_Block_Template
     */
    protected $_askBlock;

    /**
     * Set up initial variable
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_askBlock = new Oggetto_Faq_Block_Ask;
    }

    public function testGetsQuestionAskUrl()
    {
        $testValue = 'test';

        $coreUrl = $this->getModelMock('core/url', ['getUrl']);

        $coreUrl->expects($this->once())
            ->method('getUrl')
            ->with('faq/index/add')
            ->willReturn($testValue);

        $this->replaceByMock('model', 'core/url', $coreUrl);

        $this->assertEquals($testValue, $this->_askBlock->getQuestionAddUrl());
    }

}
