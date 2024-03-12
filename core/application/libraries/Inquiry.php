<?php
use MailerSend\Exceptions\MailerSendAssertException;
use MailerSend\Exceptions\MailerSendException;
use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;
use Psr\Http\Client\ClientExceptionInterface;

class Inquiry
{
    private array $items = [];
    private array $company = [];
    private array $recipient = [];
    private string $subject = 'Inquiry received on your Website';
    private string $templateId = '0r83ql3n6zx4zw1j';

    public function setItems($items)
    {
        $this->items = $items;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setTemplate($templateId)
    {
        $this->templateId = $templateId;
    }

    /**
     * @throws MailerSendException
     * @throws ClientExceptionInterface
     * @throws MailerSendAssertException
     * @throws \JsonException
     */
    public function send()
    {
        if(!count($this->recipient) || !count($this->items) || !count($this->company)) {
            throw new MailerSendException('Recipient, items and company are required');
        }

        $apiKey = "mlsn.e16948c4a38c3a5d258dbb9ec4382acc61bbb6e0713ee2e5a7065a5d1a11adbd";

        $obj = new MailerSend(['api_key' => $apiKey]);

        $personalization = [
            new Personalization($this->recipient['email'], [
                'items' => $this->items,
                'company' => $this->company,
            ])
        ];

        $recipients = [
            new Recipient($this->recipient['email'], $this->recipient['name']),
        ];

        $emailParams = (new EmailParams())
            ->setFrom('no-reply@inntechfuture.com')
            ->setFromName(CORE_APP_TITLE)
            ->setRecipients($recipients)
            ->setSubject($this->subject)
            ->setTemplateId($this->templateId)
            ->setPersonalization($personalization);

        $obj->email->send($emailParams);
    }
}
