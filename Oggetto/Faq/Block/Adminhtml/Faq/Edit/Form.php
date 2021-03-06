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
 * Form block for editing questions in admin
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Block
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Block_Adminhtml_Faq_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare form for edit a question
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     * @throws Exception
     */
    protected function _prepareForm()
    {
        $helper = Mage::helper('oggetto_faq');
        $model = $this->getCurrentQuestionsModel();

        $form = new Varien_Data_Form([
            'id'      => 'edit_form',
            'action'  => $this->getUrl('*/*/save', [
                'id'  => $this->getRequest()->getParam('id')
            ]),
            'method'  => 'post',
            'enctype' => 'multipart/form-data'
        ]);

        $this->setForm($form);


        $fieldset = $form->addFieldset('edit_form', ['legend' => $helper->__('Questions Information')]);

        $fieldset->addField('name', 'label', [
            'label' => $helper->__('User name'),
            'name'  => 'name',
        ]);
        $fieldset->addField('email', 'label', [
            'label' => $helper->__('User email'),
            'name'  => 'email',
        ]);
        $fieldset->addField('question_text', 'editor', [
            'label' => $helper->__('Question text'),
            'name'  => 'question_text',
        ]);
        $fieldset->addField('answer_text', 'editor', [
            'label' => $helper->__('Answer text'),
            'name'  => 'answer_text',
        ]);
        $fieldset->addField('created_at', 'label', [
            'label' => $helper->__('Created'),
            'name'  => 'created_at',
        ]);
        $fieldset->addField('with_feedback', 'label', [
            'label' => $helper->__('With feedback'),
            'name'  => 'with_feedback',
        ]);
        $fieldset->addField('was_notified', 'label', [
            'label' => $helper->__('Was notified to user'),
            'name'  => 'was_notified',
        ]);

        $form->setUseContainer(true);

        $form->setValues($model->getData());

        return parent::_prepareForm();
    }

    /**
     * Return current questions model from mage registry
     *
     * @return Oggetto_Faq_Model_Questions
     */
    public function getCurrentQuestionsModel()
    {
        return Mage::registry('current_questions');
    }

}
