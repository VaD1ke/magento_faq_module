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
 * Controller test class for faq in admin panel
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Controller
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Test_Controller_Adminhtml_Question extends Oggetto_Phpunit_Test_Case_Controller_Adminhtml
{
    /**
     * Set up initial variables
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_setUpAdminSession();
    }

    /**
     * Tests delete question action
     *
     * @return void
     */
    public function testIndexActionChecksLayoutRendered()
    {
        $this->dispatch('adminhtml/question/index');

        $this->_assertRequestsDispatchForwardAndController('index');

        $this->assertLayoutLoaded();
        $this->assertLayoutRendered();

        $this->assertLayoutBlockRendered('content');
    }

    /**
     * Tests delete question action
     *
     * @return void
     */
    public function testEditActionChecksLayoutRendered()
    {
        $this->dispatch('adminhtml/question/edit');

        $this->_assertRequestsDispatchForwardAndController('edit');

        $this->assertLayoutLoaded();
        $this->assertLayoutRendered();
    }

    /**
     * Tests save question action with is answered status
     *
     * @param array $post post data
     *
     * @return void
     *
     * @dataProvider dataProvider
     */
    public function testSaveActionSavesQuestionCheckIsAnsweredStatus($post)
    {
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($post);

        $model = $this->getModelMock('oggetto_faq/questions', ['save', 'setAnswered', 'setNotAnswered']);

        $model->expects($this->once())
            ->method('save');

        $model->expects($this->once())
            ->method('setAnswered');

        $model->expects($this->never())
            ->method('setNotAnswered');

        $this->replaceByMock('model', 'oggetto_faq/questions', $model);

        $this->dispatch('adminhtml/question/save');

        $this->_assertRequestsDispatchForwardAndController('save');
    }

    /**
     * Tests save question action with is not answered status
     *
     * @param array $post post data
     *
     * @return void
     *
     * @dataProvider dataProvider
     */
    public function testSaveActionSavesQuestionCheckIsNotAnsweredStatus($post)
    {
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($post);

        $model = $this->getModelMock('oggetto_faq/questions', ['save', 'setAnswered', 'setNotAnswered']);

        $model->expects($this->once())
            ->method('save');

        $model->expects($this->never())
            ->method('setAnswered');

        $model->expects($this->once())
            ->method('setNotAnswered');

        $this->replaceByMock('model', 'oggetto_faq/questions', $model);

        $this->dispatch('adminhtml/question/save');

        $this->_assertRequestsDispatchForwardAndController('save');
    }

    /**
     * Tests save question action with not notified status and send notification to customer email
     *
     * @param array $post post data
     *
     * @return void
     *
     * @dataProvider dataProvider
     */
    public function testSaveActionSavesQuestionCheckNotNotifiedStatusAndSendsNotificationToCustomerEmail($post)
    {
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($post);

        $testId = 777;
        $this->getRequest()->setParam('id', $testId);

        $toName  = $this->expected()->getToName();
        $toEmail = $this->expected()->getToEmail();

        $post['name_to']  = $toName;
        $post['email_to'] = $toEmail;
        $post['id']       = $testId;

        $model = $this->getModelMock('oggetto_faq/questions', [
            'save', 'setAnswered',
            'setNotAnswered', 'setNotified',
            'getWithFeedback', 'getWasNotified',
            'getName', 'getEmail'
        ]);

        $model->expects($this->once())
            ->method('getWithFeedback')
            ->willReturn(1);

        $model->expects($this->once())
            ->method('getWasNotified')
            ->willReturn(0);

        $model->expects($this->once())
            ->method('getName')
            ->willReturn($toName);

        $model->expects($this->once())
            ->method('getEmail')
            ->willReturn($toEmail);

        $model->expects($this->once())
            ->method('save');

        $model->expects($this->once())
            ->method('setAnswered');

        $model->expects($this->once())
            ->method('setNotified');

        $model->expects($this->never())
            ->method('setNotAnswered');


        $this->replaceByMock('model', 'oggetto_faq/questions', $model);


        $emailSenderMock = $this->getModelMock('oggetto_faq/emailSender', ['sendNotificationToCustomer']);

        $emailSenderMock->expects($this->once())
            ->method('sendNotificationToCustomer')
            ->with($post);

        $this->replaceByMock('model', 'oggetto_faq/emailSender', $emailSenderMock);


        $this->dispatch('adminhtml/question/save');

        $this->_assertRequestsDispatchForwardAndController('save');
    }

    /**
     * Tests save question action with not valid form key
     *
     * @param array $post post data
     * @return void
     *
     * @dataProvider dataProvider
     */
    public function testSaveActionRedirectsWithNotValidFormKey($post)
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

        $model = $this->getModelMock('oggetto_faq/questions', [
            'save', 'load'
        ]);

        $model->expects($this->never())
            ->method('load');

        $model->expects($this->never())
            ->method('save');

        $this->replaceByMock('model', 'oggetto_faq/questions', $model);


        $emailSenderMock = $this->getModelMock('oggetto_faq/emailSender', ['sendNotificationToCustomer']);

        $emailSenderMock->expects($this->never())
            ->method('sendNotificationToCustomer');

        $this->replaceByMock('model', 'oggetto_faq/emailSender', $emailSenderMock);


        $this->dispatch('adminhtml/question/save');

        $this->_assertRequestsDispatchForwardAndController('save');
    }

    /**
     * Tests delete question action
     *
     * @return void
     */
    public function testDeleteAction()
    {
        $this->getRequest()->setParam('id', '777');

        $model = $this->getModelMock('oggetto_faq/questions', ['delete', 'setId']);

        $model->expects($this->once())
            ->method('setId')
            ->willReturnSelf();
        $model->expects($this->once())
            ->method('delete');

        $this->replaceByMock('model', 'oggetto_faq/questions', $model);

        $this->dispatch('adminhtml/question/delete');

        $this->_assertRequestsDispatchForwardAndController('delete');
    }

    /**
     * Tests mass delete question action
     *
     * @param array $data questions id to delete
     *
     * @dataProvider dataProvider
     *
     * @return void
     */
    public function testMassDeleteAction($data)
    {
        $methodsMock = ['delete', 'setId'];

        $this->getRequest()->setParam('questions', $data);

        $model = $this->getModelMock('oggetto_faq/questions', $methodsMock);

        foreach ($data as $index => $id) {
            $model->expects($this->at($index * count($methodsMock)))
                ->method('setId')
                ->with($this->equalTo($id))
                ->willReturnSelf();
            $model->expects($this->at($index * count($methodsMock)))
                ->method('delete');
        }

        $this->replaceByMock('model', 'oggetto_faq/questions', $model);

        $this->dispatch('adminhtml/question/massDelete');

        $this->_assertRequestsDispatchForwardAndController('massDelete');
    }

    /**
     * Case for asserting Request dispatched, not forwarded, Controller module, name and action for oggetto_faq module
     *
     * @param string $actionName Name of action
     *
     * @return void
     */
    private function _assertRequestsDispatchForwardAndController($actionName)
    {
        $this->assertRequestDispatched();
        $this->assertRequestNotForwarded();
        $this->assertRequestControllerModule('Oggetto_Faq_Adminhtml');

        $this->assertRequestRouteName('adminhtml');
        $this->assertRequestControllerName('question');
        $this->assertRequestActionName($actionName);
    }
}
