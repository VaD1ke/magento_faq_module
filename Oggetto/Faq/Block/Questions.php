<?php

class Oggetto_Faq_Block_Questions extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();
        $collection = Mage::getModel('oggetto_faq/questions')->getCollection();
        $this->setCollection($collection);
    }

    public function getQuestionsCollection()
    {
        $questionsCollection = Mage::getModel('oggetto_faq/questions')->getCollection();
        $questionsCollection->setOrder('created_at', 'DESC');
        return $questionsCollection;
    }

    public function getAnswersCollection()
    {
        $answersCollection = Mage::getModel('oggetto_faq/questions')->getCollection();
        $answersCollection->addFieldToFilter('is_answered', 0);
        $answersCollection->setOrder('created_at', 'DESC');
        return $answersCollection;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array(5=>5,10=>10,20=>20,'all'=>'all'));
        $pager->setCollection($this->getCollection()
                              ->addFieldToFilter('answer_text', array('notnull' => true))
                              ->setOrder('created_at', 'DESC')
        );
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}