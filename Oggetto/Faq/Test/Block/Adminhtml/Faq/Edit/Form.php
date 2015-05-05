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
 * Form for editing questions for admin
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Block
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Test_Block_Adminhtml_Faq_Edit_Form extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Prepare form field for editing a question
     *
     * @return void
     */
    public function testPreparesFormFieldsForEditingQuestion()
    {
        $testUrl = $this->expected()->getFormData('action');

        $this->replaceByMock('singleton', 'core/session', $this->getModelMock('core/session', ['start']));

        $questions = $this->getModelMock('oggetto_faq/questions', ['getId']);

        Mage::unregister('current_questions');
        Mage::register('current_questions', $questions);

        $blockMock = $this->getBlockMock('oggetto_faq/adminhtml_faq_edit_form', ['getUrl']);

        $blockMock->expects($this->any())
            ->method('getUrl')
            ->with($this->equalTo('*/*/save'), $this->anything())
            ->willReturn($testUrl);

        $blockMock->toHtml();

        $form = $blockMock->getForm();

        $elements = [];

        foreach ($form->getElements() as $element) {
            if ($element->getType() == 'fieldset') {
                foreach ($element->getElements() as $field) {
                    $elements[] =$field->getId();
                }
            }
        }

        $this->assertEquals($this->expected()->getId(), $elements);

        $expectedFormData = $this->expected()->getFormData();

        $this->_assertFormOnExpectedIdActionMethodEnctypeAndUseContainerStatus($expectedFormData, $form);

        Mage::unregister('current_questions');
    }

    /**
     * Assert form on expected id, action, method, enctype and using container status
     *
     * @param array            $expected expected values
     * @param Varien_Data_Form $form     form
     *
     * @return void
     */
    private function _assertFormOnExpectedIdActionMethodEnctypeAndUseContainerStatus($expected, $form)
    {
        $this->assertEquals($expected['id'], $form->getId());
        $this->assertEquals($expected['action'], $form->getAction());
        $this->assertEquals($expected['method'], $form->getMethod());
        $this->assertEquals($expected['enctype'], $form->getEnctype());

        $this->assertTrue($form->getUseContainer());
    }
}
