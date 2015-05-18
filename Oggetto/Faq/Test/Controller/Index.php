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

        $model = $this->getModelMock('oggetto_faq/questions', ['getId', 'getIsAnswered']);

        $model->expects($this->once())
            ->method('getId')
            ->willReturn($testId);

        $model->expects($this->once())
            ->method('getIsAnswered')
            ->willReturn(1);

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
        $testId = -1;
        $this->getRequest()->setParam('id', $testId);

        $modelQuestionsMock = $this->getModelMock('oggetto_faq/questions', ['getId', 'getIsAnswered']);

        $modelQuestionsMock->expects($this->once())
            ->method('getId')
            ->willReturn($testId);

        $modelQuestionsMock->expects($this->never())
            ->method('getIsAnswered');

        $this->replaceByMock('model', 'oggetto_faq/questions', $modelQuestionsMock);

        $this->dispatch('faq/index/view');

        $this->assertRequestDispatched();
        $this->assertRedirectTo('faq');
    }

    /**
     * Tests view action check request redirected with not answered question
     *
     * @return void
     */
    public function testViewActionChecksRequestRedirectedWithNotAnsweredQuestion()
    {
        $testId = 777;
        $this->getRequest()->setParam('id', $testId);

        $modelQuestionsMock = $this->getModelMock('oggetto_faq/questions', ['getId', 'getIsAnswered']);

        $modelQuestionsMock->expects($this->once())
            ->method('getId')
            ->willReturn($testId);

        $modelQuestionsMock->expects($this->once())
            ->method('getIsAnswered')
            ->willReturn(false);

        $this->replaceByMock('model', 'oggetto_faq/questions', $modelQuestionsMock);

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


        $model = $this->getModelMock('oggetto_faq/questions', ['save', 'validate', 'setNotAnswered']);

        $model->expects($this->once())
            ->method('setNotAnswered');

        $model->expects($this->once())
            ->method('save');

        $model->expects($this->once())
            ->method('validate')
            ->will($this->returnValue(true));

        $this->replaceByMock('model', 'oggetto_faq/questions', $model);


        $emailSenderMock = $this->getModelMock('oggetto_faq/emailSender', ['sendNotificationToAdmin']);

        $emailSenderMock->expects($this->once())
            ->method('sendNotificationToAdmin')
            ->with($post);

        $this->replaceByMock('model', 'oggetto_faq/emailSender', $emailSenderMock);


        $this->dispatch('faq/index/add');

        $this->assertRedirectTo('*/');
    }

    /**
     * Tests adding questions action requests with not valid form key
     *
     * @param array $post post data
     * @return void
     *
     * @dataProvider dataProvider
     */
    public function testAddActionRedirectsWithNotValidFormKey($post)
    {
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($post);

        $coreSessionMock = $this->getModelMock('core/session', ['getFormKey']);

        foreach ($this->expected('form_keys')->getData() as $index => $formKey) {
            $coreSessionMock->expects($this->at($index))
                ->method('getFormKey')
                ->willReturn($formKey);
        }

        $this->replaceByMock('model', 'core/session', $coreSessionMock);


        $model = $this->getModelMock('oggetto_faq/questions', ['save', 'validate', 'setNotAnswered']);

        $model->expects($this->never())
            ->method('setNotAnswered');

        $model->expects($this->never())
            ->method('save');

        $model->expects($this->never())
            ->method('validate');

        $this->replaceByMock('model', 'oggetto_faq/questions', $model);


        $emailSenderMock = $this->getModelMock('oggetto_faq/emailSender', ['sendNotificationToAdmin']);

        $emailSenderMock->expects($this->never())
            ->method('sendNotificationToAdmin');

        $this->replaceByMock('model', 'oggetto_faq/emailSender', $emailSenderMock);


        $this->dispatch('faq/index/add');

        $this->assertRedirectTo('*/*/');
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
}
