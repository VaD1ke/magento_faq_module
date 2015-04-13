<?php
class Oggetto_FAQ_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function viewAction()
    {
        $questionId = Mage::app()->getRequest()->getParam('id', 0);
        $questions = Mage::getModel('oggetto_faq/questions')->load($questionId);

        if ($questions->getId() > 0) {
            $this->loadLayout();
            $this->getLayout()->getBlock('questions.content')->assign(array(
                "newsItem" => $questions,
            ));
            $this->renderLayout();
        } else {
            $this->_forward('noRoute');
        }
    }

}