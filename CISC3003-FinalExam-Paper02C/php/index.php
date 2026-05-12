<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions.php';

$flash = take_flash();
$tasks = [
    'C.01' => 'create a signup page',
    'C.02' => 'validate the signup data on the server in PHP',
    'C.03' => 'save the signup data to a MySQL database using PHP',
    'C.04' => 'create login and logout pages',
    'C.05' => 'validate the data in the browser using JavaScript',
    'C.06' => 'validate the email using an Ajax request',
    'C.07' => 'create secure password reset by email',
    'C.08' => 'require the user to confirm their email address before login',
    'C.09' => 'create the user dashboard after login with various services under user control',
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CISC3003 Final Exam Paper 02C</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/script.js" defer></script>
</head>
<body>
<header class="site-header">
    <div>
        <p class="eyebrow">Scenario C</p>
        <h1>Secure Signup, Login, Activation, and Password Reset</h1>
        <p class="lede">This project implements a PHP and MySQL account system with browser validation, Ajax email checking, PHPMailer email workflows, and a protected dashboard.</p>
    </div>
    <nav aria-label="Main navigation">
        <a href="index.php" aria-current="page">Home</a>
        <a href="register.php">Signup</a>
        <a href="login.php">Login</a>
        <a href="forgot_password.php">Reset</a>
        <a href="dashboard.php">Dashboard</a>
    </nav>
</header>

<main class="scenario-c-grid">
    <section class="panel">
        <h2>Scenario C Task Evidence</h2>
        <ol class="task-list">
            <?php foreach ($tasks as $code => $text): ?>
                <li><strong><?= e($code) ?>:</strong> <?= e($text) ?></li>
            <?php endforeach; ?>
        </ol>
    </section>

    <section class="auth-card" aria-labelledby="auth-heading">
        <div class="auth-tabs" role="tablist" aria-label="Authentication forms">
            <button type="button" class="tab-button active" data-show-panel="signup-panel">Sign Up</button>
            <button type="button" class="tab-button" data-show-panel="signin-panel">Sign In</button>
        </div>

        <?php if ($flash !== null): ?>
            <div class="notice <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <div id="signup-panel" class="auth-panel active">
            <h2 id="auth-heading">Create Account</h2>
            <form action="register.php" method="post" class="stacked-form" data-register-form novalidate>
                <label for="name">Full name</label>
                <input type="text" id="name" name="name" maxlength="100" required>

                <label for="register_email">Email</label>
                <input type="email" id="register_email" name="email" maxlength="120" data-email-check required>
                <p class="field-hint" data-email-message></p>

                <label for="register_password">Password</label>
                <input type="password" id="register_password" name="password" minlength="8" required>

                <label for="password_confirmation">Confirm password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" required>

                <label class="terms"><input type="checkbox" name="terms" value="yes" required> I agree to create this account.</label>
                <button type="submit">Sign Up</button>
            </form>
        </div>

        <div id="signin-panel" class="auth-panel">
            <h2>Sign In</h2>
            <form action="login.php" method="post" class="stacked-form">
                <label for="login_email">Email</label>
                <input type="email" id="login_email" name="email" required>

                <label for="login_password">Password</label>
                <input type="password" id="login_password" name="password" required>

                <button type="submit">Login</button>
                <a href="forgot_password.php">Forgot password?</a>
            </form>
        </div>
    </section>
</main>

<?= student_footer() ?>
</body>
</html>
