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
 * Questions Collection
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Model
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Model_Resource_Questions_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    /**
     * Init object
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('oggetto_faq/questions');
    }

    /**
     * Filter questions if its answered
     *
     * @return $this
     */
    public function answered()
    {
        $this->addFieldToFilter('is_answered', 1);
        return $this;
    }

    /**
     * Order questions by created date desc
     *
     * @return $this
     */
    public function newOnTop()
    {
        $this->setOrder('created_at', 'DESC');
        return $this;
    }

}
