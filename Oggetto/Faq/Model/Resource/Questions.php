<?php

class Oggetto_Faq_Model_Resource_Questions extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Init object
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('oggetto_faq/table_questions', 'question_id');
    }

}
