<?php

class Oggetto_Faq_Block_Questions extends Mage_Core_Block_Template
{

    private function preparePager()
    {
        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array(5 => 5, 10 => 10, 20 => 20, 'all' => 'all'));
        $pager->setCollection($this->getCollection());

        return $pager;
    }


    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->preparePager();

        $this->setChild('pager', $pager)->getCollection()->load();

        return $this;
    }


    public function __construct()
    {
        parent::__construct();

        $collection = Mage::getModel('oggetto_faq/questions')->getCollection();
        $collection->newOnTop()->answered();

        $this->setCollection($collection);
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getQuestionAskUrl()
    {
        return Mage::getUrl('faq/index/ask');
    }

    public function isDisabledAsk()
    {
        $isDisabled = Mage::helper('oggetto_faq')->isDisabledAddingOptionData();
        return $isDisabled;
    }

}

