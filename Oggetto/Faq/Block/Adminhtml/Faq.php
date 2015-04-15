<?php

class Oggetto_Faq_Block_Adminhtml_Faq extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    protected function _construct()
    {
        parent::_construct();

        $helper = Mage::helper('oggetto_faq');
        $this->_blockGroup = 'oggetto_faq';
        $this->_controller = 'adminhtml_faq';

        $this->_headerText = $helper->__('FAQ Management');
        //$this->_addButtonLabel = $helper->__('Add answer');
    }

    protected function _prepareLayout() {
        $this->_removeButton('add');
        return parent::_prepareLayout();
    }

}