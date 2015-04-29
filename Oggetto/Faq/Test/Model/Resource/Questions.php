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
 * Questions resource model test class
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Model
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Test_Model_Resource_Questions extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Model questions
     *
     * @var Mage_Core_Model_Abstract
     */
    protected $_resourceModel = null;

    /**
     * Set up initial variables
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_resourceModel = Mage::getResourceModel('oggetto_faq/questions');
    }

    /**
     * Checks main table and id field name
     *
     * @return void
     */
    public function testChecksMainTableAndIdFieldName()
    {
        $this->assertEquals('oggetto_faq_questions', $this->_resourceModel->getMainTable());
        $this->assertEquals('question_id', $this->_resourceModel->getIdFieldName());
    }
}
