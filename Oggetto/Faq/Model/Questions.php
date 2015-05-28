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
 * Questions Model
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Model
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Model_Questions extends Mage_Core_Model_Abstract
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
     * Set question to answered flag
     *
     * @return void
     */
    public function setAnswered()
    {
        $this->setIsAnswered(1);
    }

    /**
     * Set question to not answered flag
     *
     * @return void
     */
    public function setNotAnswered()
    {
        $this->setIsAnswered(0);
    }

    /**
     * Set question to was notified flag
     *
     * @return void
     */
    public function setNotified()
    {
        $this->setWasNotified(1);
    }

    /**
     * Validate users name, email and question is empty
     *
     * @return array|bool
     * @throws Exception
     * @throws Zend_Validate_Exception
     */
    public function validate()
    {
        $errors = [];
        if (!Zend_Validate::is($this->getName(), 'NotEmpty')) {
            $errors[] = Mage::helper('oggetto_faq')->__('Name is required');
        }
        if (!Zend_Validate::is($this->getEmail(), 'NotEmpty')) {
            $errors[] = Mage::helper('oggetto_faq')->__('Email is required');
        } elseif (!Zend_Validate::is($this->getEmail(), 'EmailAddress')) {
            $errors[] = Mage::helper('oggetto_faq')->__('Email is incorrect');
        }
        if (!Zend_Validate::is($this->getQuestionText(), 'NotEmpty')) {
            $errors[] = Mage::helper('oggetto_faq')->__('Question is required');
        }

        if ($errors) {
            return $errors;
        }

        return true;
    }
}
