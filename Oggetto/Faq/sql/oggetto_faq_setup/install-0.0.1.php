<?php

$installer = $this;

$installer->startSetup();

try {

    $table = $installer->getConnection()
        ->newTable($installer->getTable('oggetto_faq/table_questions'))
        ->addColumn('question_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'identity' => true,
            'nullable' => false,
            'primary' => true,
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
            'default' => null,
        ), 'Created At')
        ->addColumn('is_answered', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
            'nullable' => false,
            'default' => false,
        ), 'Is Answered')
        ->setComment('Questions Table');

    $installer->getConnection()->createTable($table);

} catch (Exception $e) {
    Mage::logException($e);
}

$installer->endSetup();