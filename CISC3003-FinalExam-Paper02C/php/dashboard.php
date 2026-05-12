<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions.php';

$user = require_login();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario C Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<header class="site-header">
    <div><p class="eyebrow">Scenario C</p><h1>User Dashboard</h1></div>
    <nav aria-label="Main navigation"><a href="index.php">Home</a><a href="forgot_password.php">Reset Password</a><a href="logout.php">Logout</a></nav>
</header>

<main class="dashboard-grid">
    <section class="dashboard-card primary">
        <h2>Welcome, <?= e($user['name']) ?></h2>
        <p>You became a user on <?= e($user['created_at']) ?>. Your email was confirmed on <?= e($user['activated_at']) ?>.</p>
    </section>

    <section class="dashboard-card">
        <h2>Account Profile</h2>
        <dl class="result-list">
            <dt>User ID</dt><dd><?= e((string) $user['id']) ?></dd>
            <dt>Name</dt><dd><?= e($user['name']) ?></dd>
            <dt>Email</dt><dd><?= e($user['email']) ?></dd>
            <dt>Status</dt><dd>Active and email confirmed</dd>
        </dl>
    </section>

    <section class="dashboard-card">
        <h2>User Services</h2>
        <div class="service-grid">
            <a href="forgot_password.php">Change password by email reset</a>
            <a href="validate_email.php?email=<?= urlencode($user['email']) ?>">Run Ajax email availability endpoint</a>
            <a href="logout.php">Sign out securely</a>
        </div>
    </section>

    <section class="dashboard-card">
        <h2>Security Features</h2>
        <ul>
            <li>Password hashes use <code>password_hash()</code> and <code>password_verify()</code>.</li>
            <li>Email activation and reset tokens are stored as SHA-256 hashes.</li>
            <li>All database writes use prepared statements.</li>
        </ul>
    </section>
</main>

<?= student_footer() ?>
</body>
</html>
