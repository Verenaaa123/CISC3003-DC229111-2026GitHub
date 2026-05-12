<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions.php';

$token = (string) ($_GET['token'] ?? '');
$message = 'Invalid activation token.';
$type = 'error';

if ($token !== '') {
    require __DIR__ . '/connect.php';
    $tokenHash = hash('sha256', $token);
    $stmt = $conn->prepare('SELECT id, name, email FROM users WHERE activation_token_hash = ? AND activated_at IS NULL LIMIT 1');
    $stmt->bind_param('s', $tokenHash);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user !== null) {
        $update = $conn->prepare('UPDATE users SET activated_at = NOW(), activation_token_hash = NULL WHERE id = ?');
        $update->bind_param('i', $user['id']);
        $update->execute();
        $message = 'Email confirmed. You can now login.';
        $type = 'success';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario C Account Activation</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header class="site-header">
    <div><p class="eyebrow">Scenario C</p><h1>Email Confirmation</h1></div>
    <nav aria-label="Main navigation"><a href="index.php">Home</a><a href="login.php">Login</a></nav>
</header>
<main class="auth-shell">
    <section class="panel">
        <div class="notice <?= e($type) ?>"><?= e($message) ?></div>
        <a class="button-link" href="login.php">Go to login</a>
    </section>
</main>
<?= student_footer() ?>
</body>
</html>
