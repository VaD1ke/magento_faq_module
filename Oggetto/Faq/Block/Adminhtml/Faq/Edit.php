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
 * Block for editing questions in admin
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Block
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Block_Adminhtml_Faq_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'oggetto_faq';
        $this->_controller = 'adminhtml_faq';
    }

    /**
     * Getting header text for editing
     *
     * @return string
     */
    public function getHeaderText()
    {
        $helper = Mage::helper('oggetto_faq');
        $model = $this->getCurrentQuestionsModel();

        return $helper->__('Edit Question item %s', $this->escapeHtml($model->getId()));
    }

    /**
     * Return current questions model from mage registry
     *
     * @return Oggetto_Faq_Model_Questions
     */
    public function getCurrentQuestionsModel()
    {
        return Mage::registry('current_questions');
    }

}
