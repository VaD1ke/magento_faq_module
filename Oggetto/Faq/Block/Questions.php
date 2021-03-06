<?php
/**
 * Oggetto FAQ extension for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the Oggetto FAQ module to newer versions in the future.
 * If you wish to customize the Oggetto FAQ module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @copyright  Copyright (C) 2015 Oggetto Web (http://oggettoweb.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Block for displaying questions
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Block
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Block_Questions extends Mage_Core_Block_Template
{
    /**
     * Init object
     *
     * @return Oggetto_Faq_Block_Questions
     */
    public function __construct()
    {
        parent::__construct();

        $collection = Mage::getModel('oggetto_faq/questions')->getCollection();
        $collection->newOnTop()->answered();

        $this->setCollection($collection);
    }

    /**
     * Get html for paging
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get url for ask question page
     *
     * @return string
     */
    public function getQuestionAskUrl()
    {
        return $this->getUrl('faq/index/ask');
    }

    /**
     * Get url for ask question page
     *
     * @return string
     */
    public function getQuestionViewUrl()
    {
        return $this->getUrl('faq/index/view');
    }

    /**
     * Check if disabled option is enabled
     *
     * @return mixed
     */
    public function isDisabledAsk()
    {
        $isDisabled = Mage::helper('oggetto_faq')->isDisabledAddingOptionData();
        return $isDisabled;
    }


    /**
     * Prepare layout for questions page
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->_preparePager();

        $this->setChild('pager', $pager);
        $this->getCollection()->load();

        return $this;
    }


    /**
     * Prepare paging
     *
     * @return Mage_Core_Block_Abstract
     */
    private function _preparePager()
    {
        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit([5 => 5, 10 => 10, 20 => 20, 'all' => 'all']);
        $pager->setCollection($this->getCollection());

        return $pager;
    }

}
