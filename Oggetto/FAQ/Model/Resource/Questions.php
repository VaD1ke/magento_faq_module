<?php

class Oggetto_Faq_Model_Resource_Questions extends Mage_Core_Model_Mysql4_Abstract
{

    public function _construct()
    {
        $this->_init('oggetto_faq/question', 'question_id');
    }

}