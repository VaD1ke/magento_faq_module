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
 * If you wish to customize the Oggetto Filter module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @copyright  Copyright (C) 2015 Oggetto Web (http://oggettoweb.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;

$installer->startSetup();

try {

    $table = $installer->getConnection()
        ->newTable($installer->getTable('oggetto_faq/table_questions'))
        ->addColumn('question_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'identity' => true,
            'nullable' => false,
            'primary'  => true,
        ), 'Question ID')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, '255', array(
            'nullable' => false,
        ), 'User name')
        ->addColumn('email', Varien_Db_Ddl_Table::TYPE_VARCHAR, '255', array(
            'nullable' => false,
        ), 'User email')
        ->addColumn('question_text', Varien_Db_Ddl_Table::TYPE_VARCHAR, '255', array(
            'nullable' => false,
        ), 'Question Text')
        ->addColumn('answer_text', Varien_Db_Ddl_Table::TYPE_VARCHAR, '255', array(
            'nullable' => true,
        ), 'Answer Text')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            'nullable' => true,
            'default'  => Varien_Db_Ddl_Table::TIMESTAMP_INIT,
        ), 'Created At')
        ->addColumn('is_answered', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
            'nullable' => false,
            'default'  => 0,
        ), 'Is Answered')
        ->setComment('Questions Table');

    $installer->getConnection()->createTable($table);

} catch (Exception $e) {

    Mage::logException($e);

}

$installer->endSetup();
