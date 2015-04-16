<?php

class Oggetto_Faq_Block_Adminhtml_Faq_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    protected function  _prepareCollection()
    {
        $this->setDefaultFilter(array('is_answered' => 0));

        $collection = Mage::getModel('oggetto_faq/questions')->getCollection();
        $collection->newOnTop();

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

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

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('question_id');
        $this->getMassactionBlock()->setFormFieldName('questions');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'  =>  $this->__('Delete'),
            'url'    =>  $this->getUrl('*/*/massDelete'),
        ));

        return $this;
    }


    public function getRowUrl($model)
    {
        return $this->geturl('*/*/edit', array(
            'id'     =>  $model->getId(),
        ));
    }

}
