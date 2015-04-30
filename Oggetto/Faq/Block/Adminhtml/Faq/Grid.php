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
 * Displaying questions in grid in admin
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Block
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Block_Adminhtml_Faq_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Prepare question collection for displaying
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $this->setDefaultFilter(array('is_answered' => 0));

        $collection = Mage::getModel('oggetto_faq/questions')->getCollection();
        $collection->newOnTop();

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare columns for grid
     *
     * @return $this
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $helper = Mage::helper('oggetto_faq');

        $this->addColumn('question_id', array(
            'header' => $helper->__('Question ID'),
            'index'  => 'question_id',
            ))
            ->addColumn('name', array(
            'header' => $helper->__('User Name'),
            'index'  => 'name',
            'type'   => 'varchar',
            ))
            ->addColumn('email', array(
            'header' => $helper->__('User Email'),
            'index'  => 'email',
            'type'   => 'varchar',
            ))
            ->addColumn('question_text', array(
            'header' => $helper->__('Question'),
            'index'  => 'question_text',
            'type'   => 'varchar',
            ))
            ->addColumn('answer_text', array(
            'header' => $helper->__('Answer'),
            'index'  => 'answer_text',
            'type'   => 'varchar',
            ))
            ->addColumn('created_at', array(
            'header' => $helper->__('Created'),
            'index'  => 'created_at',
            'type'   => 'timestamp',
            ))
            ->addColumn('is_answered', array(
            'header' => $helper->__('Is answered'),
            'index'  => 'is_answered',
            'type'   => 'boolean',
            ));

        return parent::_prepareColumns();
    }

    /**
     * Add delete item to massaction block
     *
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('question_id');
        $this->getMassactionBlock()->setFormFieldName('questions');

        $this->getMassactionBlock()->addItem('delete', [
            'label' => $this->__('Delete'),
            'url'   => $this->getUrl('*/*/massDelete'),
        ]);

        return $this;
    }

    /**
     * Init links for rows in grid
     *
     * @param Mage_Core_Model_Abstract $model Question Model
     *
     * @return string
     */
    public function getRowUrl($model)
    {
        return $this->geturl('*/*/edit', array(
            'id' => $model->getId(),
        ));
    }

}
