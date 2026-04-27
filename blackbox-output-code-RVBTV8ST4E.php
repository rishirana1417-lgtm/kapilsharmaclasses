<?php
$host = 'localhost';
$dbname = 'kapil_classes';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Email configuration
$email_config = [
    'host' => 'smtp.gmail.com', // Update with your SMTP
    'port' => 587,
    'username' => 'your-email@gmail.com',
    'password' => 'your-app-password'
];
?>