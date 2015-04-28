<?php

class Oggetto_Faq_Test_Controller_Admin extends Oggetto_Phpunit_Test_Case_Controller_Adminhtml
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
    public function testIndexAction()
    {
        $this->dispatch('adminhtml/question/index');

        $this->assertRequestsDispatchForwardAndController('index');

        $this->assertLayoutLoaded();
        $this->assertLayoutRendered();

        $this->assertLayoutBlockRendered('content');
    }

    /**
     * Tests delete question action
     *
     * @return void
     */
    public function testEditAction()
    {
        $this->dispatch('adminhtml/question/edit');

        $this->assertRequestsDispatchForwardAndController('edit');

        $this->assertLayoutLoaded();
        $this->assertLayoutRendered();
    }

    /**
     * Tests save question action
     *
     * @param array $post post data
     *
     * @return void
     *
     * @dataProvider dataProvider
     */
    public function testSaveAction($post)
    {

        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($post);

        $model = $this->getModelMock('oggetto_faq/questions', array('save'));

        $model->expects($this->once())
            ->method('save');

        $this->replaceByMock('model', 'oggetto_faq/questions', $model);

        $this->dispatch('adminhtml/question/save');

        $this->assertRequestsDispatchForwardAndController('save');
    }

    /**
     * Tests delete question action
     *
     * @return void
     */
    public function testDeleteAction()
    {
        $this->getRequest()->setParam('id', '777');

        $model = $this->getModelMock('oggetto_faq/questions', array('delete', 'setId'));

        $model->expects($this->once())
            ->method('setId')
            ->willReturnSelf();
        $model->expects($this->once())
            ->method('delete');

        $this->replaceByMock('model', 'oggetto_faq/questions', $model);

        $this->dispatch('adminhtml/question/delete');

        $this->assertRequestsDispatchForwardAndController('delete');
    }

    /**
     * Tests delete question action
     *
     * @return void
     */
    public function testMassDeleteAction()
    {
        $quantityOfQuestionsToDelete = 3;
        $questions_id = [];
        for ($i = 1; $i <= $quantityOfQuestionsToDelete; $i++) {
            $questions_id[] = strval($i);
        }

        $this->getRequest()->setParam('questions', $questions_id);

        $model = $this->getModelMock('oggetto_faq/questions', array('delete', 'setId'));

        $model->expects($this->exactly($quantityOfQuestionsToDelete))
            ->method('setId')
            ->willReturnSelf();
        $model->expects($this->exactly($quantityOfQuestionsToDelete))
            ->method('delete');

        $this->replaceByMock('model', 'oggetto_faq/questions', $model);

        $this->dispatch('adminhtml/question/massDelete');

        $this->assertRequestsDispatchForwardAndController('massDelete');
    }

    /**
     * Case for asserting Request dispatched, not forwarded, Controller module, name and action
     *
     * @param string $actionName Name of action
     *
     * @return void
     */
    private function assertRequestsDispatchForwardAndController($actionName)
    {
        $this->assertRequestDispatched();
        $this->assertRequestNotForwarded();
        $this->assertRequestControllerModule('Oggetto_Faq_Adminhtml');
        $this->assertRequestControllerName('question');
        $this->assertRequestActionName($actionName);
    }
}
