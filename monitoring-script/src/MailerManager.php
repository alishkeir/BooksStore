<?php

/**
 * Class MailerManager
 *
 * This class is responsible for configuring and sending emails using the PHPMailer library.
 */
namespace monitoring\src;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * MailerManager class.
 */
class MailerManager
{
    /**
     * @var \PHPMailer\PHPMailer\PHPMailer
     */
    private $phpmailer;

    /**
     * Constructor.
     *
     * @param PHPMailer $phpmailer The PHPMailer instance.
     */
    public function __construct(PHPMailer $phpmailer)
    {
        $this->phpmailer = $phpmailer;
    }

    /**
     * Configures and sends an email.
     *
     * @param string $from    The email sender.
     * @param array  $to      The email recipients.
     * @param string $subject The email subject.
     * @param string $body    The email body.
     */
    public function configureAndSend($from, $to, $subject, $body, $storeName)
    {
        // Configure PHPMailer settings
        $this->phpmailer->isSMTP();
        $this->phpmailer->isHTML();
        $this->phpmailer->SMTPAuth = true;
        $this->phpmailer->CharSet = 'UTF-8';
        $this->phpmailer->Encoding = 'base64';

        $this->phpmailer->Host = $_ENV[strtoupper($storeName) . '_MAIL_HOST'];
        $this->phpmailer->Port = $_ENV[strtoupper($storeName) . '_MAIL_PORT'];
        $this->phpmailer->Username = $_ENV[strtoupper($storeName) . '_MAIL_USERNAME'];
        $this->phpmailer->Password = $_ENV[strtoupper($storeName) . '_MAIL_PASSWORD'];

        // Set email properties
        $this->phpmailer->setFrom($from);
        foreach ($to as $address) {
            $this->phpmailer->addAddress($address);
        }
        $this->phpmailer->Subject = $subject;
        $this->phpmailer->Body = $body;

        // Send the email
        if (!$this->phpmailer->send()) {
            echo 'Message could not be sent.' . PHP_EOL;
            echo 'Mailer Error: ' . $this->phpmailer->ErrorInfo . PHP_EOL;
            logEvent("'Message could not be sent ($storeName)");
            logEvent("Mailer Error ($storeName): " . $this->phpmailer->ErrorInfo);
        } else {
            echo "Message has been sent ($storeName)" . PHP_EOL;
            logEvent("Message has been sent ($storeName)");

        }

    }
}