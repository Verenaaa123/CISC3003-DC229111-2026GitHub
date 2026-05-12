<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions.php';
require __DIR__ . '/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('forgot_password.php');
}

$email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$message = 'If the email is registered and activated, a reset link has been sent.';

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    require __DIR__ . '/connect.php';
    $stmt = $conn->prepare('SELECT id, name, email, activated_at FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user !== null && $user['activated_at'] !== null) {
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiresAt = date('Y-m-d H:i:s', time() + 3600);
        $update = $conn->prepare('UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE id = ?');
        $update->bind_param('ssi', $tokenHash, $expiresAt, $user['id']);
        $update->execute();

        $resetLink = app_url('reset_password.php?token=' . urlencode($token));
        $html = '<h1>Password reset</h1><p>Hello ' . e($user['name']) . ',</p><p>Reset your password within 60 minutes:</p><p><a href="' . e($resetLink) . '">Reset password</a></p>';
        $text = "Hello {$user['name']},\n\nReset your password within 60 minutes:\n{$resetLink}";
        $mailResult = send_project_email($user['email'], $user['name'], 'Reset your CISC3003 password', $html, $text);

        if (!$mailResult['sent']) {
            $message .= ' SMTP is not configured, so for localhost testing use this reset link: <a href="' . e($resetLink) . '">' . e($resetLink) . '</a>';
        }
    }
}

flash('success', $message);
redirect_to('forgot_password.php');
