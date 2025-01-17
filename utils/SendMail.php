<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './vendor/autoload.php';

class Mail {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.hostinger.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'your username';
        $this->mailer->Password = 'your password';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port = 465;
        $this->mailer->setFrom('your email', 'Payment Status');
        $this->mailer->isHTML(true);
    }

    public function sendPaymentConfirmation($to, $name, $transactionId, $amount) {
        try {
            // Set recipient and subject
            $this->mailer->addAddress($to);
            $this->mailer->Subject = 'Payment Confirmation';

            // Email body content (HTML version)
            $this->mailer->Body = "
                <h1>Payment Confirmation</h1>
                <p>Dear {$name},</p>
                <p>Thank you for your payment. Your transaction has been successfully processed.</p>
                <p><strong>Transaction ID:</strong> {$transactionId}</p>
                <p><strong>Amount Paid:</strong> {$amount}</p>
                <p>Best regards,<br>Your Name</p>
            ";

            // Send the email
            if ($this->mailer->send()) {
                return "Email sent successfully!";
            } else {
                return "Failed to send email.";
            }
        } catch (Exception $e) {
            return "Mailer Error: " . $this->mailer->ErrorInfo;
        }
    }
}

// Test email sending with sample values
// $mailer = new Mail();
// $response = $mailer->sendPaymentConfirmation('adentup@gmail.com', 'ashjutosh', '63276473264', '100');
// $response;
