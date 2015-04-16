<?php

class Oggetto_Faq_Block_Adminhtml_Faq_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    protected function _construct()
    {
        $this->_blockGroup = 'oggetto_faq';
        $this->_controller = 'adminhtml_faq';
    }

    public function getHeaderText()
    {
        $helper = Mage::helper('oggetto_faq');
        $model = Mage::registry('current_questions');

        if ($model->getId()) {
            return $helper->__('Edit Question item %s', $this->escapeHtml($model->getId()));
        } else {
            return $helper->__('Add Question item');
        }
    }

}
