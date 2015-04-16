<?php

class Oggetto_Faq_Adminhtml_FaqController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout()->_setActiveMenu('oggetto_faq');
        $this->_addContent($this->getLayout()->createBlock('oggetto_faq/adminhtml_faq'));
        $this->renderLayout();
    }

    public function editAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        Mage::register('current_questions', Mage::getModel('oggetto_faq/questions')->load($id));

        $this->loadLayout()->_setActiveMenu('oggetto_faq');
        $this->_addContent($this->getLayout()->createBlock('oggetto_faq/adminhtml_faq_edit'));
        $this->renderLayout();
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {

            try {

                $model = Mage::getModel('oggetto_faq/questions');
                $model->setData($data)->setId($this->getRequest()->getParam('id'));
                if ($data['answer_text']) {
                    $model->setIsAnswered(1);
                } else {
                    $model->setIsAnswered(0);
                }

                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->__('Questions was saved successfully')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

            } catch (Exception $e) {

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array(
                    'id' => $this->getRequest()->getParam('id')
                ));

            }

            $this->_redirect('*/*/');
            return;

        }

        Mage::getSingleton('adminhtml/session')->addError(
            $this->__('Unable to find item to save')
        );

        $this->_redirect('*/*/');
    }

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

    public function massDeleteAction()
    {
        $questions = $this->getRequest()->getParam('questions', null);

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

}
