<?php

class Oggetto_Faq_Block_Adminhtml_Faq_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $helper = Mage::helper('oggetto_faq');
        $model = Mage::registry('current_questions');

        $form = new Varien_Data_Form(array(
            'id'      => 'edit_form',
            'action'  => $this->getUrl('*/*/save', array(
                'id'  => $this->getRequest()->getParam('id')
            )),
            'method'  => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $this->setForm($form);

        $fieldset = $form->addFieldset('faq_form', array('legend' => $helper->__('Questions Information')));

        $fieldset->addField('name', 'label', array(
            'label' => $helper->__('User name'),
            'name'  => 'name',
        ));
        $fieldset->addField('email', 'label', array(
            'label' => $helper->__('User email'),
            'name'  => 'email',
        ));
        $fieldset->addField('question_text', 'editor', array(
            'label' => $helper->__('Question text'),
            'name'  => 'question_text',
        ));
        $fieldset->addField('answer_text', 'editor', array(
            'label' => $helper->__('Answer text'),
            'name'  => 'answer_text',
        ));
        $fieldset->addField('created_at', 'label', array(
            'label' => $helper->__('Created'),
            'name'  => 'created_at',
        ));

        $form->setUseContainer(true);

        if ($data = Mage::getSingleton('adminhtml/session')->getFormData()) {
            $form->setValues($data);
        } else {
            $form->setValues($model->getData());
        }

        return parent::_prepareForm();
    }

}
