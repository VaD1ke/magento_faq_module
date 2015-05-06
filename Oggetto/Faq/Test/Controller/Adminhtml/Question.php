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

        $model = $this->getModelMock('oggetto_faq/questions', array('save', 'setAnswered', 'setNotAnswered'));

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
        $this->getRequest()->setParam('questions', $data);

        $model = $this->getModelMock('oggetto_faq/questions', ['delete', 'setId']);

        foreach ($data as $index => $id) {
            $model->expects($this->at($index * 2))
                ->method('setId')
                ->with($this->equalTo($id))
                ->willReturnSelf();
            $model->expects($this->at($index * 2))
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
