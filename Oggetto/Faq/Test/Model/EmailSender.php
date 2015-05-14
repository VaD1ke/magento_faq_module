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
 * Email Sender model test class
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Model
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Test_Model_EmailSender extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Email Sender Model
     *
     * @var Oggetto_Faq_Model_EmailSender
     */
    protected $_emailSenderModel;

    /**
     * Set up initial variables
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_emailSenderModel = Mage::getModel('oggetto_faq/emailSender');
    }

    /**
     * Send notification to admin about adding question
     *
     * @param array $data data for sending
     * @return void
     *
     * @dataProvider dataProvider
     */
    public function testSendsNotificationToAdminAboutAddingQuestion($data)
    {
        $nameTo  = $this->expected()->getNameTo();
        $emailTo = $this->expected()->getEmailTo();

        $emailTemplateVariable = [
            'name'     => $data['name'],
            'question' => $data['question_text']
        ];

        $templateSettings = [
            'default'   => $this->expected()->getDefaultTemplate(),
            'processed' => $this->expected()->getProcessedTemplate(),
            'variable'  => $emailTemplateVariable
        ];

        $sendingSettings = [
            'toName'    => $nameTo,
            'toEmail'   => $emailTo,
            'body'      => $templateSettings['processed'],
            'subject'   => $this->expected()->getSubject(),
            'fromEmail' => $data['email'],
            'fromName'  => $data['name'],
            'type'      => $this->expected()->getType()
        ];


        $this->_replaceEmailTemplateModelForSettingDefaultTemplate($templateSettings);

        $this->_replaceHelperDataMockForSettingEmailAndNameSupport($nameTo, $emailTo);

        $this->_replaceEmailModelMockForSendingEmail($sendingSettings);

        
        $this->_emailSenderModel->sendNotificationToAdmin($data);
    }

    /**
     * Send notification to customer about answering his question
     *
     * @param array $data data for sending
     * @return void
     *
     * @dataProvider dataProvider
     */
    public function testSendsNotificationToCustomerAboutAnsweringHisQuestion($data)
    {
        $testUrl = $this->expected()->getUrl();
        $questionUrl = $testUrl . '?id=' . $data['id'];

        $fromName  = $this->expected()->getNameFrom();
        $fromEmail = $this->expected()->getEmailFrom();

        $emailTemplateVariable = [
            'question'    => $data['question_text'],
            'answer'      => $data['answer_text'],
            'questionUrl' => $questionUrl
        ];

        $templateSettings = [
            'default'   => $this->expected()->getDefaultTemplate(),
            'processed' => $this->expected()->getProcessedTemplate(),
            'variable'  => $emailTemplateVariable
        ];

        $sendingSettings = [
            'toName'    => $data['name_to'],
            'toEmail'   => $data['email_to'],
            'body'      => $templateSettings['processed'],
            'subject'   => $this->expected()->getSubject(),
            'fromEmail' => $fromEmail,
            'fromName'  => $fromName,
            'type'      => $this->expected()->getType()
        ];


        $this->_replaceUrlModelForGettingUrl($testUrl);

        $this->_replaceEmailTemplateModelForSettingDefaultTemplate($templateSettings);

        $this->_replaceHelperDataMockForSettingEmailAndNameSupport($fromName, $fromEmail);

        $this->_replaceEmailModelMockForSendingEmail($sendingSettings);

        $this->_emailSenderModel->sendNotificationToCustomer($data);
    }

    /**
     * Replace helper mock and set email and name support
     *
     * @param string $name  Support Name
     * @param string $email Support Name
     *
     * @return void
     */
    protected function _replaceHelperDataMockForSettingEmailAndNameSupport($name, $email)
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

    /**
     * Create and replace Core Email Model mock for sending email
     *
     * @param array $settings sending settings
     * @return void
     */
    protected function _replaceEmailModelMockForSendingEmail($settings)
    {
        $mailModelMock = $this->getModelMock('core/email', [
            'setToName',    'setToEmail',
            'setBody',      'setSubject',
            'setFromEmail', 'setFromName',
            'setType',      'send'
        ]);

        $mailModelMock->expects($this->once())
            ->method('setToName')
            ->with($settings['toName'])
            ->willReturnSelf();

        $mailModelMock->expects($this->once())
            ->method('setToEmail')
            ->with($settings['toEmail'])
            ->willReturnSelf();

        $mailModelMock->expects($this->once())
            ->method('setBody')
            ->with($settings['body'])
            ->willReturnSelf();

        $mailModelMock->expects($this->once())
            ->method('setSubject')
            ->with($settings['subject'])
            ->willReturnSelf();

        $mailModelMock->expects($this->once())
            ->method('setFromEmail')
            ->with($settings['fromEmail'])
            ->willReturnSelf();

        $mailModelMock->expects($this->once())
            ->method('setFromName')
            ->with($settings['fromName'])
            ->willReturnSelf();

        $mailModelMock->expects($this->once())
            ->method('setType')
            ->with($settings['type'])
            ->willReturnSelf();

        $mailModelMock->expects($this->once())
            ->method('send');

        $this->replaceByMock('model', 'core/email', $mailModelMock);
    }

    /**
     * Replace Core Email Template Model for setting template
     *
     * @param array $settings template settings
     * @return void
     */
    protected function _replaceEmailTemplateModelForSettingDefaultTemplate($settings)
    {
        $mailTemplateModelMock = $this->getModelMock('core/email_template', ['loadDefault', 'getProcessedTemplate']);

        $mailTemplateModelMock->expects($this->once())
            ->method('loadDefault')
            ->with($settings['default'])
            ->willReturnSelf();

        $mailTemplateModelMock->expects($this->once())
            ->method('getProcessedTemplate')
            ->with($settings['variable'])
            ->willReturn($settings['processed']);

        $this->replaceByMock('model', 'core/email_template', $mailTemplateModelMock);
    }

    /**
     * Replace Core Url Model for getting url
     *
     * @param string $url url to get
     * @return void
     */
    protected function _replaceUrlModelForGettingUrl($url)
    {
        $coreUrlMock = $this->getModelMock('core/url', ['getUrl']);

        $coreUrlMock->expects($this->once())
            ->method('getUrl')
            ->with('faq/index/view')
            ->willReturn($url);

        $this->replaceByMock('model', 'core/url', $coreUrlMock);
    }
}
