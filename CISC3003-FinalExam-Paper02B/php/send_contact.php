<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('index.php');
}

$name = trim((string) filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$subject = trim((string) filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$category = trim((string) filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$message = trim((string) filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$allowedCategories = ['coursework', 'phpmailer', 'database', 'deployment'];
$errors = [];

if ($name === '' || mb_strlen($name) > 100) {
    $errors[] = 'Name is required.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid email is required.';
}

if (mb_strlen($subject) < 4 || mb_strlen($subject) > 140) {
    $errors[] = 'Subject must be from 4 to 140 characters.';
}

if (!in_array($category, $allowedCategories, true)) {
    $errors[] = 'Please choose a valid category.';
}

if (mb_strlen($message) < 10 || mb_strlen($message) > 1500) {
    $errors[] = 'Message must be from 10 to 1500 characters.';
}

if ($errors !== []) {
    flash('error', implode(' ', $errors));
    redirect_to('index.php?status=validation-error');
}

require __DIR__ . '/connect.php';

$mailStatus = 'not_sent';
$debugLog = [];
$config = require __DIR__ . '/mail_config.php';

try {
    require __DIR__ . '/mailer.php';

    if (!load_phpmailer()) {
        throw new RuntimeException('PHPMailer package was not found under vendor/phpmailer/phpmailer/src.');
    }

    if ($config['auth'] && ($config['username'] === 'your_um_email@example.com' || $config['password'] === 'your_app_password')) {
        throw new RuntimeException('SMTP username/password are placeholders. Configure php/mail_config.php before live sending.');
    }

    $mail = build_mailer($config, $debugLog);
    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($config['recipient_email'], $config['recipient_name']);
    $mail->addReplyTo($email, $name);
    $mail->isHTML(true);
    $mail->Subject = '[CISC3003 Contact] ' . $subject;
    $mail->Body = '<h1>Contact Form Message</h1>'
        . '<p><strong>Name:</strong> ' . e($name) . '</p>'
        . '<p><strong>Email:</strong> ' . e($email) . '</p>'
        . '<p><strong>Category:</strong> ' . e($category) . '</p>'
        . '<p>' . nl2br(e($message)) . '</p>';
    $mail->AltBody = "Name: {$name}\nEmail: {$email}\nCategory: {$category}\n\n{$message}";
    $mail->send();
    $mailStatus = 'sent';
    $debugLog[] = 'Message accepted by SMTP server.';
} catch (Throwable $exception) {
    $mailStatus = 'debug_required';
    $debugLog[] = $exception->getMessage();
}

$debugText = implode(PHP_EOL, $debugLog);
$logFile = dirname(__DIR__) . '/logs/email-debug.log';
file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] ' . $mailStatus . PHP_EOL . $debugText . PHP_EOL . PHP_EOL, FILE_APPEND);

$stmt = $conn->prepare('INSERT INTO contact_messages (name, email, subject, category, message, mail_status, debug_log) VALUES (?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param('sssssss', $name, $email, $subject, $category, $message, $mailStatus, $debugText);
$stmt->execute();

if ($mailStatus === 'sent') {
    flash('success', 'Message sent successfully. This page used the post / redirect / get pattern.');
    redirect_to('index.php?status=sent');
}

flash('warning', 'Message saved, but email sending needs SMTP configuration. Open the Debug page for details.');
redirect_to('index.php?status=debug-required');
