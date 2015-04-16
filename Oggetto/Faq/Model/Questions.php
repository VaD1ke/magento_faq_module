<?php

class Oggetto_Faq_Model_Questions extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('oggetto_faq/questions');
    }

}
