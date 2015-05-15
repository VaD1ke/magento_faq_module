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
class Oggetto_Faq_IndexController extends Mage_Core_Controller_Front_Action
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
     * View question
     *
     * @return void
     */
    public function viewAction()
    {
        $questionId = Mage::app()->getRequest()->getParam('id', 0);
        $model = Mage::getModel('oggetto_faq/questions');
        $question = $model->load($questionId);

        if ($question->getId() > 0 && $question->getIsAnswered()) {
            foreach ($question->getData() as $index => $fields) {
                $question[$index] = htmlentities($fields);
            }

            $this->loadLayout();
            $this->getLayout()->getBlock('view.content')->assign([
                'questionItem' => $question,
            ]);
            $this->renderLayout();
        } else {
            $this->_redirect('faq');
        }
    }

    /**
     * Add question
     *
     * @return Mage_Core_Controller_Varien_Action
     */
    public function addAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/');
        }

        $post = Mage::app()->getRequest()->getPost();

        $data = [
            'name'          => $post['name'],
            'email'         => $post['email'],
            'question_text' => $post['question_text'],
        ];
        $data['with_feedback'] = isset($post['with_feedback']) ? (bool) $post['with_feedback'] : false;

        try {
            /** @var Oggetto_Faq_Model_Questions $model */
            $model = Mage::getModel('oggetto_faq/questions');
            $model->setData($data)->setNotAnswered();

            $validation = $model->validate();

            if ($validation === true) {
                $model->save();

                /** @var Oggetto_Faq_Model_EmailSender $emailSender */
                $emailSender = Mage::getModel('oggetto_faq/emailSender');

                unset($data['with_feedback']);

                $emailSender->sendNotificationToAdmin($data);

            } else {
                $errorText = 'Unable to submit your request. ';

                for ($i = 0; $i < count($validation); $i++) {
                    $errorText .= $validation[$i] . '. ';
                }

                Mage::getSingleton('core/session')->addError(Mage::helper('oggetto_faq')->__($errorText));
                return $this->_redirect('faq/index/ask');

            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('Question was saved successfully')
            );
            Mage::getSingleton('adminhtml/session')->setFormData(false);

        } catch (Exception $e) {

            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setFormData($data);

        }

        return $this->_redirect('faq');
    }

    /**
     * Validate form key to prevent csrf attack
     *
     * @return bool
     */
    protected function _validateFormKey()
    {
        /** @var Mage_Core_Model_Session $coreSession */
        $coreSession = Mage::getSingleton('core/session');

        if (!($formKey = $this->getRequest()->getParam('form_key'))
            || $formKey != $coreSession->getFormKey()) {
            return false;
        }

        return true;
    }
}
