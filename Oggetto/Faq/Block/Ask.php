<?php

class Oggetto_Faq_Block_Ask extends Mage_Core_Block_Template
{

    public function getQuestionAddUrl()
    {
        return Mage::getUrl('faq/index/add');
    }

}
