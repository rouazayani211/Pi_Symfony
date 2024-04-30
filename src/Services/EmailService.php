<?php

namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
private $mailer;
    private $logger;

public function __construct(MailerInterface $mailer,LoggerInterface $logger)
{
$this->mailer = $mailer;
    $this->logger = $logger;
}


    public function sendEmail(String $to, String $subject, String $message): void
{
$email = (new Email())
->from('challakhihichem95@gmail.com')
->to($to)
->subject($subject)
->text($message);


    try {
        $this->mailer->send($email);
    } catch (TransportExceptionInterface $e) {
        $this->logger->error(sprintf('Email could not be sent: %s', $e->getMessage()));
        // Consider re-throwing the exception or handling it as needed
    }

}
}
