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
 * Questions collection test class
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Model
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Test_Model_Resource_Questions_Collection extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Questions collection
     *
     * @var Mage_Core_Model_Abstract
     */
    protected $_collection = null;

    /**
     * Set up initial variables
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_collection = Mage::getModel('oggetto_faq/questions')->getCollection();
    }

    /**
     * Checks model name of collection
     *
     * @return void
     */
    public function testChecksModelName()
    {
        $this->assertEquals('oggetto_faq/questions', $this->_collection->getModelName());
    }

    /**
     * Add to questions filter is answered
     *
     * @return void
     *
     * @loadFixture testQuestionsCollection
     */
    public function testAddsFilterQuestionIsAnswered()
    {
        $this->_collection->answered();

        $answeredQuestionsId = [];
        foreach ($this->_collection as $item) {
            $answeredQuestionsId[] = $item->getId();
        }

        $expectedId = $this->expected('question_id')->getData();

        $this->assertEquals($expectedId, $answeredQuestionsId);
    }

    /**
     * Order questions new on top
     *
     * @return void
     *
     * @loadFixture testQuestionsCollection
     */
    public function testOrdersQuestionsNewOnTop()
    {
        $this->_collection->newOnTop();

        $orderedQuestionsId = [];
        foreach ($this->_collection as $item) {
            $orderedQuestionsId[] = $item->getId();
        }

        $expectedId = $this->expected('question_id')->getData();

        $this->assertEquals($expectedId, $orderedQuestionsId);
    }
}
