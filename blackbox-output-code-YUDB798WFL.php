<?php
require_once 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php'; // Download PHPMailer

function sendDemoConfirmation($request) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = $email_config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $email_config['username'];
        $mail->Password = $email_config['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $email_config['port'];

        $mail->setFrom($email_config['username'], 'Kapil Sharma Classes');
        $mail->addAddress($request['email'], $request['name']);

        $meetLink = $request['batch_preference'] === 'Online' ? 'https://meet.google.com/your-meet-link' : '';
        $address = $request['batch_preference'] === 'Offline' ? 'Delhi, India (Exact address will be shared)' : '';

        $mail->isHTML(true);
        $mail->Subject = 'Demo Class Confirmation - Kapil Sharma Classes';
        $mail->Body = "
            <h2>Thank You for Booking Demo Class!</h2>
            <p><strong>Name:</strong> {$request['name']}</p>
            <p><strong>Course:</strong> {$request['course']}</p>
            <p><strong>Batch:</strong> {$request['batch_preference']}</p>
            
            " . ($meetLink ? "<p><strong>Google Meet Link:</strong> <a href='$meetLink'>Join Demo</a></p>" : '') . "
            " . ($address ? "<p><strong>Venue:</strong> $address<br><strong>Timing:</strong> Mon-Sat 4-8 PM<br><strong>Note:</strong> Bring ID & notebook</p>" : '') . "
            
            <p>We look forward to seeing you!</p>
            <p><strong>Kapil Sharma Classes</strong></p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function sendQREmail($request) {
    // Generate QR code (using Google Chart API)
    $qrUrl = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=" . urlencode("https://kapilsharmaclasses.com/join?student=" . $request['id']);
    
    $mail = new PHPMailer(true);
    // Similar setup as above...
    $mail->Body = "
        <h2>Thank You for Attending Demo Class!</h2>
        <p>Scan QR code below to join regular classes:</p>
        <img src='$qrUrl' alt='QR Code' style='max-width:200px;'>
        <p>Or visit: <a href='https://kapilsharmaclasses.com/join'>Join Regular Classes</a></p>
    ";
    $mail->send();
}
?>