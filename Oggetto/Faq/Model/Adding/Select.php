<?php

class Oggetto_Faq_Model_Adding_Select
{
    public function toOptionArray()
    {
        return array(
            array('value'  =>  0,
                  'label'  =>  Mage::helper('oggetto_faq')->__('No')),
            array('value'  =>  1,
                  'label'  =>  Mage::helper('oggetto_faq')->__('Yes')),
        );
    }

}

