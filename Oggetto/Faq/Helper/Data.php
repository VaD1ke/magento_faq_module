<?php

class Oggetto_Faq_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isDisabledAddingOptionData()
    {
        return Mage::getStoreConfig('oggetto_faq_options/disable_options/disable_adding');
    }

}