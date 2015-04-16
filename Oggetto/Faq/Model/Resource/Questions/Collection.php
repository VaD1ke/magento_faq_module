<?php

class Oggetto_Faq_Model_Resource_Questions_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('oggetto_faq/questions');
    }

    public function answered()
    {
        $this->addFieldToFilter('is_answered', 1);
        return $this;
    }

    public function newOnTop()
    {
        $this->setOrder('created_at', 'DESC');
        return $this;
    }

}
