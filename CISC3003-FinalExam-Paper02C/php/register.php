<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions.php';
require __DIR__ . '/mailer.php';

$errors = [];
$activationLink = null;
$mailResult = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string) filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $password = (string) ($_POST['password'] ?? '');
    $confirmation = (string) ($_POST['password_confirmation'] ?? '');
    $terms = filter_input(INPUT_POST, 'terms', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($name === '' || mb_strlen($name) > 100) {
        $errors[] = 'Full name is required and must be 100 characters or fewer.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }

    if (!password_is_strong($password)) {
        $errors[] = 'Password must be at least 8 characters and include uppercase, lowercase, and a number.';
    }

    if ($password !== $confirmation) {
        $errors[] = 'Password confirmation does not match.';
    }

    if ($terms !== 'yes') {
        $errors[] = 'You must accept the signup confirmation.';
    }

    if ($errors === []) {
        require __DIR__ . '/connect.php';

        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();

        if ($stmt->get_result()->fetch_assoc() !== null) {
            $errors[] = 'This email is already registered.';
        } else {
            $token = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $token);
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $insert = $conn->prepare('INSERT INTO users (name, email, password_hash, activation_token_hash) VALUES (?, ?, ?, ?)');
            $insert->bind_param('ssss', $name, $email, $passwordHash, $tokenHash);
            $insert->execute();

            $activationLink = app_url('activate.php?token=' . urlencode($token));
            $html = '<h1>Confirm your email</h1><p>Hello ' . e($name) . ',</p><p>Please confirm your account:</p><p><a href="' . e($activationLink) . '">Activate account</a></p>';
            $text = "Hello {$name},\n\nConfirm your account:\n{$activationLink}";
            $mailResult = send_project_email($email, $name, 'Activate your CISC3003 account', $html, $text);

            if ($mailResult['sent']) {
                flash('success', 'Account created. Please check your email and activate the account before login.');
                redirect_to('login.php');
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario C Signup</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/script.js" defer></script>
</head>
<body>
<header class="site-header">
    <div><p class="eyebrow">Scenario C</p><h1>Signup Page</h1></div>
    <nav aria-label="Main navigation"><a href="index.php">Home</a><a href="login.php">Login</a><a href="dashboard.php">Dashboard</a></nav>
</header>

<main class="auth-shell">
    <section class="panel">
        <h2>Create account</h2>
        <?php if ($errors !== []): ?><div class="notice error"><?= e(implode(' ', $errors)) ?></div><?php endif; ?>
        <?php if ($activationLink !== null && ($mailResult === null || !$mailResult['sent'])): ?>
            <div class="notice warning">
                Account created, but SMTP is not configured for live sending. For localhost testing, open:
                <a href="<?= e($activationLink) ?>"><?= e($activationLink) ?></a>
            </div>
        <?php endif; ?>
        <form method="post" class="stacked-form" data-register-form novalidate>
            <label for="name">Full name</label>
            <input type="text" id="name" name="name" maxlength="100" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" maxlength="120" data-email-check required>
            <p class="field-hint" data-email-message></p>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" minlength="8" required>

            <label for="password_confirmation">Confirm password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" required>

            <label class="terms"><input type="checkbox" name="terms" value="yes" required> I agree to create this account.</label>

            <button type="submit">Sign Up</button>
        </form>
    </section>
</main>

<?= student_footer() ?>
</body>
</html>
