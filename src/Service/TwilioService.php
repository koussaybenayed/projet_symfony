<?php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioService
{
    private Client $client;

    public function __construct(string $accountSid, string $authToken)
    {
        $this->client = new Client($accountSid, $authToken);
    }

    public function sendSms(string $to, string $message): void
    {
        $this->client->messages->create($to, [
            'from' => $_ENV['TWILIO_PHONE_NUMBER'],
            'body' => $message,
        ]);
    }
}