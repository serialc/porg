<?php
// Filename: php/classes/mailer.php
// Purpose: Handles all email sending

namespace frakturmedia\porg;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    private $mail;

    public function __construct()
    {
        // Create an instance; passing `true` enables exceptions
        $this->mail = new PHPMailer(true);

        // Mail server settings
        // Enable verbose debug output
        //$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
        // Send using SMTP
        $this->mail->isSMTP();
        $this->mail->Host       = EMAIL_HOST;
        // Enable SMTP authentication
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = EMAIL_SENDER;
        $this->mail->Password   = EMAIL_PASSWORD;
        // Enable implicit TLS encryption
        if ( EMAIL_PORT === 465 ) {
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   // port 465
        } else {
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // port 587
        }
        // TCP port to connect to
        // Use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $this->mail->Port       = EMAIL_PORT;
        $this->mail->setFrom(EMAIL_REPLYTO, EMAIL_REPLYTONAME);
    }

    public function addStringAttachment ( $content )
    {
        $this->mail->addStringAttachment($content, 'calendar.ics', 'base64', 'text/calendar');
    }

    public function send($email, $to_name, $subject, $html, $text)
    {
        try {
            // Recipient
            $this->mail->addAddress($email, $to_name);

            // Content
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $html;
            $this->mail->AltBody = $text;

            $this->mail->send();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
