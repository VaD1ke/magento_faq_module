<?php

class Oggetto_FAQ_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

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

    public function addAction()
    {
        $data = Mage::app()->getRequest()->getPost();

        try {

            $model = Mage::getModel('oggetto_faq/questions');
            $model->setData($data)->setIsAnswered(0);

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

