<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions.php';

$flash = take_flash();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $password = (string) ($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $errors[] = 'Please enter your email and password.';
    } else {
        require __DIR__ . '/connect.php';
        $stmt = $conn->prepare('SELECT id, name, email, password_hash, activated_at, created_at FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user !== null && password_verify($password, $user['password_hash'])) {
            if ($user['activated_at'] === null) {
                $errors[] = 'Please confirm your email address before login.';
            } else {
                session_regenerate_id(true);
                $_SESSION['scenario_c_user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'created_at' => $user['created_at'],
                    'activated_at' => $user['activated_at'],
                ];
                redirect_to('dashboard.php');
            }
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario C Login</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header class="site-header">
    <div><p class="eyebrow">Scenario C</p><h1>Login Page</h1></div>
    <nav aria-label="Main navigation"><a href="index.php">Home</a><a href="register.php">Signup</a><a href="forgot_password.php">Reset</a></nav>
</header>

<main class="auth-shell">
    <section class="panel">
        <h2>Sign in</h2>
        <?php if ($flash !== null): ?><div class="notice <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
        <?php if ($errors !== []): ?><div class="notice error"><?= e(implode(' ', $errors)) ?></div><?php endif; ?>
        <form method="post" class="stacked-form">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
            <a href="forgot_password.php">Forgot password?</a>
        </form>
    </section>
</main>

<?= student_footer() ?>
</body>
</html>
