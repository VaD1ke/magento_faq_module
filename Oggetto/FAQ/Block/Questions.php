<?php

class Oggetto_Faq_Block_Questions extends Mage_Core_Block_Template
{
    public function getQuestionsCollection()
    {
        $questionsCollection = Mage::getModel('oggetto_faq/questions')->getCollection();
        var_dump($questionsCollection);die;
        $questionsCollection->setOrder('created_at', 'DESC');
        return $questionsCollection;
    }
}