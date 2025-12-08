<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Import PHPMailer classes manually
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "<script>alert('Please fill in all required fields.'); window.location.href = 'contact.html';</script>";
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Please enter a valid email address.'); window.location.href = 'contact.html';</script>";
        exit;
    }
    
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        // Server settings for GMAIL SMTP
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'njokibrianmacharia@gmail.com';                 // SMTP username (YOUR GMAIL)
        $mail->Password   = 'voag uvxg mkup bfsg';                    // SMTP password (APP PASSWORD)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
        $mail->Port       = 587;                                    // TCP port to connect to
        
        // Debugging (optional - remove in production)
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        
        // Recipients
        $mail->setFrom('njokibrianmacharia@gmail.com', 'Portfolio Website'); // FROM address
        $mail->addAddress('njokibrianmacharia@gmail.com', 'Brian Macharia Njoki');     // TO address (WHERE YOU WANT EMAILS)
        $mail->addReplyTo($email, $name);                           // Reply-to address (person who filled form)
        
        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = "Portfolio Contact: $subject";
        
        // HTML Email Body
        $mail->Body = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #3498db; color: white; padding: 20px; text-align: center; }
                    .content { background: #f9f9f9; padding: 20px; }
                    .field { margin-bottom: 15px; }
                    .field-label { font-weight: bold; color: #333; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>New Portfolio Website Message</h2>
                    </div>
                    <div class='content'>
                        <div class='field'>
                            <span class='field-label'>Name:</span> $name
                        </div>
                        <div class='field'>
                            <span class='field-label'>Email:</span> $email
                        </div>
                        <div class='field'>
                            <span class='field-label'>Subject:</span> $subject
                        </div>
                        <div class='field'>
                            <span class='field-label'>Message:</span><br>
                            <p>$message</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        ";
        
        // Plain text version for email clients that don't support HTML
        $mail->AltBody = "
            New Portfolio Website Message
            
            Name: $name
            Email: $email
            Subject: $subject
            
            Message:
            $message
        ";
        
        // Send email
        $mail->send();
        
        // Success message
        echo "<script>
            alert('Thank you for your message, $name! I will get back to you soon.');
            window.location.href = 'contact.html';
        </script>";
        
    } catch (Exception $e) {
        // Error message
        echo "<script>
            alert('Message could not be sent. Please try again later or email me directly. Error: {$mail->ErrorInfo}');
            window.location.href = 'contact.html';
        </script>";
    }
} else {
    // If not a POST request, redirect to contact page
    header('Location: contact.html');
    exit;
}
?>
