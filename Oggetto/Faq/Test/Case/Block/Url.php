<?php

class Oggetto_Faq_Test_Case_Block_Url extends EcomDev_PHPUnit_Test_Case
{
    protected function createAndReplaceMockForGettingUrl($action, $testValue)
    {
        $coreUrl = $this->getModelMock('core/url', ['getUrl']);

        $coreUrl->expects($this->once())
            ->method('getUrl')
            ->with('faq/index/' . $action)
            ->willReturn($testValue);

        $this->replaceByMock('model', 'core/url', $coreUrl);
    }
}
