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
 * Email Sender Model
 *
 * @category   Oggetto
 * @package    Oggetto_Faq
 * @subpackage Model
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Oggetto_Faq_Model_EmailSender extends Mage_Core_Model_Abstract
{
    /**
     * Send notification about added question to admin
     *
     * @param array $data data to send
     *
     * @return void
     */
    public function sendNotificationToAdmin($data)
    {
        /** @var Mage_Core_Model_Email_Template $emailTemplate */
        $emailTemplate = Mage::getModel('core/email_template')->loadDefault('add_question_email_template');

        $emailTemplateVariables = [];
        $emailTemplateVariables['name'] = $data['name'];
        $emailTemplateVariables['question'] = $data['question_text'];


        $emailTo = Mage::helper('oggetto_faq')->getSupportEmail();
        $nameTo = Mage::helper('oggetto_faq')->getSupportName();

        $emailFrom = $data['email'];
        $nameFrom = $data['name'];

        $subject = 'Question was added';

        $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);

        $sendingSettings = [
            'toName'    => $nameTo,
            'toEmail'   => $emailTo,
            'body'      => $processedTemplate,
            'subject'   => $subject,
            'fromEmail' => $emailFrom,
            'fromName'  => $nameFrom,
            'type'      => 'html'
        ];

        $this->_send($sendingSettings);
    }

    /**
     * Send notification to customer that admin answered his question
     *
     * @param array $data data to send
     * @return void
     */
    public function sendNotificationToCustomer($data)
    {
        $emailTemplate = Mage::getModel('core/email_template')
            ->loadDefault('answer_question_email_template');

        $questionUrl = Mage::getUrl('faq/index/view') . '?id=' . $data['id'];


        $emailTemplateVariables = [];
        $emailTemplateVariables['question']    = $data['question_text'];
        $emailTemplateVariables['answer']      = $data['answer_text'];
        $emailTemplateVariables['questionUrl'] = $questionUrl;

        $nameTo  = $data['name_to'];
        $emailTo = $data['email_to'];

        $emailFrom = Mage::helper('oggetto_faq')->getSupportEmail();
        $nameFrom  = Mage::helper('oggetto_faq')->getSupportName();

        $subject = 'Admin answered your question';
        $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);

        $sendingSettings = [
            'toName'    => $nameTo,
            'toEmail'   => $emailTo,
            'body'      => $processedTemplate,
            'subject'   => $subject,
            'fromEmail' => $emailFrom,
            'fromName'  => $nameFrom,
            'type'      => 'html'
        ];

        $this->_send($sendingSettings);
    }

    /**
     * Send email
     *
     * @param array $settings sending settings
     * @return void
     */
    protected function _send($settings)
    {
        $mail = Mage::getModel('core/email')
            ->setToName($settings['toName'])
            ->setToEmail($settings['toEmail'])
            ->setBody($settings['body'])
            ->setSubject($settings['subject'])
            ->setFromEmail($settings['fromEmail'])
            ->setFromName($settings['fromName'])
            ->setType($settings['type']);

        try {
            $mail->send();
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
}
