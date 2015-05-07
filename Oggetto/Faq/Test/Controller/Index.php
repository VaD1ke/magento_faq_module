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
 * Controller test class for faq on frontend
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Controller
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Test_Controller_Index extends EcomDev_PHPUnit_Test_Case_Controller
{
    /**
     * Tests index action
     *
     * @return void
     */
    public function testIndexActionChecksLayoutRendered()
    {
        $this->dispatch('faq');

        $this->_assertRequestsDispatchForwardRouteAndController('index');

        $this->assertLayoutHandleLoaded('oggetto_faq_index_index');
        $this->assertLayoutRendered();

        $this->assertLayoutBlockCreated('questions.content');

        $this->assertLayoutBlockInstanceOf('questions.content', 'Oggetto_Faq_Block_Questions');
        $this->assertLayoutBlockParentEquals('questions.content', 'content');
        $this->assertLayoutBlockRendered('questions.content');
        $this->assertLayoutBlockRendered('custom.pager');
    }

    /**
     * Tests view action with positive question id
     *
     * @return void
     */
    public function testViewActionChecksLayoutRenderedWithPositiveQuestionId()
    {
        $testId = 777;
        $this->getRequest()->setParam('id', $testId);

        $model = $this->getModelMock('oggetto_faq/questions', ['getId']);

        $model->expects($this->once())
            ->method('getId')
            ->willReturn($testId);

        $this->replaceByMock('model', 'oggetto_faq/questions', $model);

        $this->dispatch('faq/index/view');


        $this->_assertRequestsDispatchForwardRouteAndController('view');

        $this->assertLayoutHandleLoaded('oggetto_faq_index_view');
        $this->assertLayoutRendered();

        $this->assertLayoutBlockCreated('view.content');

        $this->assertLayoutBlockInstanceOf('view.content', 'Oggetto_Faq_Block_View');
        $this->assertLayoutBlockParentEquals('view.content', 'content');
        $this->assertLayoutBlockRendered('view.content');
    }

    /**
     * Tests view action with negative question id
     *
     * @return void
     */
    public function testViewActionChecksRequestRedirectedWithNegativeQuestionId()
    {
        $testId = 777;
        $this->getRequest()->setParam('id', $testId);

        $this->dispatch('faq/index/view');

        $this->assertRequestDispatched();
        $this->assertRedirectTo('faq');
    }

    /**
     * Tests adding questions action
     *
     * @param array $post post data
     *
     * @return void
     *
     * @dataProvider dataProvider
     */
    public function testAddActionSavesAndValidatesQuestion($post)
    {
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($post);

        $emailTemplateVariable = [
            'name'     => $post['name'],
            'question' => $post['question_text']
        ];
        $processedTemplateString = 'TestTemplate';

        $nameTo = 'TestName';
        $emailTo = 'TestEmail';

        $model = $this->getModelMock('oggetto_faq/questions', ['save', 'validate']);

        $model->expects($this->once())
            ->method('save');

        $model->expects($this->once())
            ->method('validate')
            ->will($this->returnValue(true));


        $mailTemplateModelMock = $this->getModelMock('core/email_template', ['loadDefault', 'getProcessedTemplate']);

        $mailTemplateModelMock->expects($this->once())
            ->method('loadDefault')
            ->with($this->equalTo('add_question_email_template'))
            ->willReturnSelf();

        $mailTemplateModelMock->expects($this->once())
            ->method('getProcessedTemplate')
            ->with($this->equalTo($emailTemplateVariable))
            ->willReturn($processedTemplateString);

        $this->_replaceMockForSettingEmailAndNameSupport($nameTo, $emailTo);

        $mailModelMock = $this->getModelMock('core/email', [
            'setToName',    'setToEmail',
            'setBody',      'setSubject',
            'setFromEmail', 'setFromName',
            'setType',      'send'
        ]);

        $mailModelMock->expects($this->once())
            ->method('setToName')
            ->with($nameTo)
            ->willReturnSelf();

        $mailModelMock->expects($this->once())
            ->method('setToEmail')
            ->with($emailTo)
            ->willReturnSelf();

        $mailModelMock->expects($this->once())
            ->method('setBody')
            ->with($processedTemplateString)
            ->willReturnSelf();

        $mailModelMock->expects($this->once())
            ->method('setSubject')
            ->with('Question was added')
            ->willReturnSelf();

        $mailModelMock->expects($this->once())
            ->method('setFromEmail')
            ->with($post['email'])
            ->willReturnSelf();

        $mailModelMock->expects($this->once())
            ->method('setFromName')
            ->with($post['name'])
            ->willReturnSelf();

        $mailModelMock->expects($this->once())
            ->method('setType')
            ->with('html')
            ->willReturnSelf();

        $mailModelMock->expects($this->once())
            ->method('send');


        $this->replaceByMock('model', 'oggetto_faq/questions', $model);
        $this->replaceByMock('model', 'core/email_template', $mailTemplateModelMock);
        $this->replaceByMock('model', 'core/email', $mailModelMock);

        $this->dispatch('faq/index/add');
    }

    /**
     * Tests asking questions method when adding option is enabled
     *
     * @return void
     */
    public function testAskActionChecksLayoutRenderedWithEnabledAddingOption()
    {
        $this->_replaceMockForAddingOptionData(0);

        $this->dispatch('faq/index/ask');

        $this->_assertRequestsDispatchForwardRouteAndController('ask');

        $this->assertLayoutHandleLoaded('oggetto_faq_index_ask');
        $this->assertLayoutRendered();

        $this->assertLayoutBlockCreated('ask.content');
        $this->assertLayoutBlockInstanceOf('ask.content', 'Oggetto_Faq_Block_Ask');
        $this->assertLayoutBlockParentEquals('ask.content', 'content');
        $this->assertLayoutBlockRendered('ask.content');
    }

    /**
     * Tests asking questions method when adding option is disabled
     *
     * @return void
     */
    public function testAskActionChecksRedirectToFaqPageWithDisabledAddingOption()
    {
        $this->_replaceMockForAddingOptionData(1);

        $this->dispatch('faq/index/ask');

        $this->_assertRequestsDispatchForwardRouteAndController('ask');

        $this->assertRedirectTo('faq');
    }

    /**
     * Test pack for asserting Request dispatched, not forwarded, Controller module, name and action for oggetto faq
     *
     * @param string $actionName Name of action
     *
     * @return void
     */
    private function _assertRequestsDispatchForwardRouteAndController($actionName)
    {
        $this->assertRequestDispatched();
        $this->assertRequestNotForwarded();
        $this->assertRequestControllerModule('Oggetto_Faq');
        $this->assertRequestRouteName('oggetto_faq');
        $this->assertRequestControllerName('index');
        $this->assertRequestActionName($actionName);
    }


    /**
     * Replace helper mock and set adding option data
     *
     * @param boolean $disabled Adding disabled
     *
     * @return void
     */
    private function _replaceMockForAddingOptionData($disabled)
    {
        $helperDataMock = $this->getHelperMock('oggetto_faq/data', ['isDisabledAddingOptionData']);

        $helperDataMock->expects($this->once())
            ->method('isDisabledAddingOptionData')
            ->willReturn($disabled);

        $this->replaceByMock('helper', 'oggetto_faq', $helperDataMock);
    }

    /**
     * Replace helper mock and set email and name support
     *
     * @param string $name  Support Name
     * @param string $email Support Name
     *
     * @return void
     */
    private function _replaceMockForSettingEmailAndNameSupport($name, $email)
    {
        $helperDataMock = $this->getHelperMock('oggetto_faq/data', ['getSupportName', 'getSupportEmail']);

        $helperDataMock->expects($this->once())
            ->method('getSupportName')
            ->willReturn($name);

        $helperDataMock->expects($this->once())
            ->method('getSupportEmail')
            ->willReturn($email);

        $this->replaceByMock('helper', 'oggetto_faq', $helperDataMock);
    }

}
