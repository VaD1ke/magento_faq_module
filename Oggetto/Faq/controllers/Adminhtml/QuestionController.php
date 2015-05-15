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
 * FAQ Controller for admin
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage controllers
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Adminhtml_QuestionController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Display grid with questions
     *
     * @return void
     */
    public function indexAction()
    {
        $this->loadLayout()->_setActiveMenu('oggetto_faq');
        $this->_addContent($this->getLayout()->createBlock('oggetto_faq/adminhtml_faq'));
        $this->renderLayout();
    }

    /**
     * Display form for editing question
     *
     * @return void
     */
    public function editAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        Mage::register('current_questions', Mage::getModel('oggetto_faq/questions')->load($id));
        $this->loadLayout()->_setActiveMenu('oggetto_faq');
        $this->_addContent($this->getLayout()->createBlock('oggetto_faq/adminhtml_faq_edit'));
        $this->renderLayout();
    }

    /**
     * Save changes in question
     *
     * @return Mage_Core_Controller_Varien_Action
     */
    public function saveAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/');
        }
        if ($data = $this->getRequest()->getPost()) {
            try {
                $id = $this->getRequest()->getParam('id');
                $model = Mage::getModel('oggetto_faq/questions');

                $currentQuestion = $model->load($id);
                $withFeedback = $currentQuestion->getWithFeedback();
                $wasNotified  = $currentQuestion->getWasNotified();
                $nameTo  = $currentQuestion->getName();
                $emailTo = $currentQuestion->getEmail();

                unset($data['form_key']);

                $model->setData($data)->setId($id);

                if ($data['answer_text']) {
                    if ($withFeedback && !$wasNotified) {
                        $model->setNotified();

                        $data['name_to']  = $nameTo;
                        $data['email_to'] = $emailTo;
                        $data['id']       = $id;

                        /** @var Oggetto_Faq_Model_EmailSender $emailSender */
                        $emailSender = Mage::getModel('oggetto_faq/emailSender');
                        $emailSender->sendNotificationToCustomer($data);
                    }
                    $model->setAnswered();
                } else {
                    $model->setNotAnswered();
                }
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->__('Question was saved successfully')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array(
                    'id' => $this->getRequest()->getParam('id')
                ));
            }
            return $this->_redirect('*/*/');
        }
        Mage::getSingleton('adminhtml/session')->addError(
            $this->__('Unable to find item to save')
        );
        return $this->_redirect('*/*/');
    }

    /**
     * Delete question
     *
     * @return void
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                Mage::getModel('oggetto_faq/questions')->setId($id)->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->__('Question was deleted successfully')
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Delete few questions
     *
     * @return void
     */
    public function massDeleteAction()
    {
        $questions = $this->getRequest()->getParam('questions');
        if (is_array($questions) && sizeof($questions) > 0) {
            try {
                foreach ($questions as $id) {
                    Mage::getModel('oggetto_faq/questions')->setId($id)->delete();
                }
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        } else {
            $this->_getSession()->addError($this->__('Please select questions'));
        }
        $this->_redirect('*/*/');
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
