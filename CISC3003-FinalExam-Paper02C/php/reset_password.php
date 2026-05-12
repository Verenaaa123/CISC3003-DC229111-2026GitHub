<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions.php';

$token = (string) ($_GET['token'] ?? $_POST['token'] ?? '');
$tokenHash = $token !== '' ? hash('sha256', $token) : '';
$errors = [];
$validUser = null;

if ($tokenHash !== '') {
    require __DIR__ . '/connect.php';
    $stmt = $conn->prepare('SELECT id, name, email FROM users WHERE reset_token_hash = ? AND reset_token_expires_at > NOW() LIMIT 1');
    $stmt->bind_param('s', $tokenHash);
    $stmt->execute();
    $validUser = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validUser !== null) {
    $password = (string) ($_POST['password'] ?? '');
    $confirmation = (string) ($_POST['password_confirmation'] ?? '');

    if (!password_is_strong($password)) {
        $errors[] = 'Password must be at least 8 characters and include uppercase, lowercase, and a number.';
    }

    if ($password !== $confirmation) {
        $errors[] = 'Password confirmation does not match.';
    }

    if ($errors === []) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $update = $conn->prepare('UPDATE users SET password_hash = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = ?');
        $update->bind_param('si', $hash, $validUser['id']);
        $update->execute();
        flash('success', 'Password updated. Please login with the new password.');
        redirect_to('login.php');
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario C Set New Password</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/script.js" defer></script>
</head>
<body>
<header class="site-header">
    <div><p class="eyebrow">Scenario C</p><h1>Set New Password</h1></div>
    <nav aria-label="Main navigation"><a href="index.php">Home</a><a href="login.php">Login</a></nav>
</header>

<main class="auth-shell">
    <section class="panel">
        <?php if ($validUser === null): ?>
            <div class="notice error">Reset token is invalid or expired.</div>
            <a class="button-link" href="forgot_password.php">Request another reset link</a>
        <?php else: ?>
            <h2>Reset password for <?= e($validUser['email']) ?></h2>
            <?php if ($errors !== []): ?><div class="notice error"><?= e(implode(' ', $errors)) ?></div><?php endif; ?>
            <form method="post" class="stacked-form" data-password-reset-form>
                <input type="hidden" name="token" value="<?= e($token) ?>">
                <label for="password">New password</label>
                <input type="password" id="password" name="password" minlength="8" required>

                <label for="password_confirmation">Confirm new password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" required>

                <button type="submit">Update Password</button>
            </form>
        <?php endif; ?>
    </section>
</main>

<?= student_footer() ?>
</body>
</html>
