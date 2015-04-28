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
 * Config test class for config.xml
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Config
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Test_Config_Main extends EcomDev_PHPUnit_Test_Case_Config
{
    /**
     * Test setup resources on definition and existence
     *
     * @return void
     */
    public function testSetupResources()
    {
        $this->assertSetupResourceDefined();
        $this->assertSetupResourceExists();
    }

    /**
     * Test class aliases for Model, Resource and Helper
     *
     * @return void
     */
    public function testClassAliases()
    {
        $this->assertModelAlias('oggetto_faq/questions', 'Oggetto_Faq_Model_Questions');
        $this->assertResourceModelAlias('oggetto_faq/questions', 'Oggetto_Faq_Model_Resource_Questions');
        $this->assertHelperAlias('oggetto_faq', 'Oggetto_Faq_Helper_Data');
    }

    /**
     * Test config node for Block class
     *
     * @return void
     */
    public function testConfigNode()
    {
        $this->assertConfigNodeValue('global/blocks/oggetto_faq/class', 'Oggetto_Faq_Block');
    }

    /**
     * Test codePool and version of module
     *
     * @return void
     */
    public function testModule()
    {
        $this->assertModuleCodePool('local', 'oggetto_faq');
        $this->assertModuleVersion('0.0.1');
    }

    /**
     * Test layout file on definition and existence
     *
     * @return void
     */
    public function testLayout()
    {
        $this->assertLayoutFileDefined('frontend', 'oggetto_faq.xml');
        $this->assertLayoutFileExists('frontend', 'oggetto_faq.xml');
    }

    /**
     * Test alias for questions table
     *
     * @return void
     */
    public function testTableAlias()
    {
        $this->assertTableAlias('oggetto_faq/table_questions', 'oggetto_faq_questions');
    }
}
