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
 * FAQ Controller for front
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage controllers
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_FAQ_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Display questions
     *
     * @return void
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Display form for asking question
     *
     * @return void
     */
    public function askAction()
    {
        $isDisabled = Mage::helper('oggetto_faq')->isDisabledAddingOptionData();

        if (!$isDisabled) {
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $this->_redirect('faq');
        }
    }

    /**
     * Add question
     *
     * @return void
     */
    public function addAction()
    {
        $data = Mage::app()->getRequest()->getPost();

        try {

            $model = Mage::getModel('oggetto_faq/questions');
            $model->setData($data)->setNotAnswered();

            $model->save();

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('Question was saved successfully')
            );
            Mage::getSingleton('adminhtml/session')->setFormData(false);

        } catch (Exception $e) {

            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setFormData($data);

        }

        $this->_redirect('faq');
    }

}
