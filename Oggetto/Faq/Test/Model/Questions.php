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
 * Questions model test class
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Model
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Test_Model_Questions extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Model questions
     *
     * @var Mage_Core_Model_Abstract
     */
    protected $_model = null;

    /**
     * Set up initial variables
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_model = Mage::getModel('oggetto_faq/questions');
    }

    /**
     * Check resource model name
     *
     * @return void
     */
    public function testChecksResourceModelName()
    {
        $this->assertEquals('oggetto_faq/questions', $this->_model->getResourceName());
    }

    /**
     * Test validation of adding questions if name, email, question text is not empty
     *
     * @param array $dataStatus   expected statuses of validation
     * @param array $providerData data for validation
     *
     * @return void
     *
     * @dataProvider dataProvider
     */
    public function testValidatesAddedQuestionOnEmpty($dataStatus, $providerData)
    {
        $this->_model->setData($providerData);

        $result = $this->_model->validate();

        $this->assertSame(
            $this->expected($dataStatus)->getResult(),
            $result
        );
    }

    /**
     * Test setting status answered / not answered
     *
     * @param array $data data
     *
     * @return void
     *
     * @dataProvider dataProvider
     */
    public function testSetsStatusAnswered($data)
    {
        $this->_model->setData($data);

        $this->assertEquals(0, $this->_model->getIsAnswered());

        $this->_model->setAnswered();
        $this->assertEquals(1, $this->_model->getIsAnswered());

        $this->_model->setNotAnswered();
        $this->assertEquals(0, $this->_model->getIsAnswered());
    }
}
