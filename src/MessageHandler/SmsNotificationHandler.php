<?php

namespace App\MessageHandler;

use App\Message\SmsNotification;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SmsNotificationHandler
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }


    public function __invoke(SmsNotification $message): void
    {
        sleep(10);
        $this->logger->critical('WALDI: '.print_r($message, true),
            [
                // include extra "context" info in your logs
                'cause' => 'in_hurry',
            ]
        );
        // ... do some work - like sending an SMS message!
    }
}
