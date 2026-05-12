<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions.php';
$flash = take_flash();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario C Password Reset</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header class="site-header">
    <div><p class="eyebrow">Scenario C</p><h1>Secure Password Reset by Email</h1></div>
    <nav aria-label="Main navigation"><a href="index.php">Home</a><a href="login.php">Login</a></nav>
</header>

<main class="auth-shell">
    <section class="panel">
        <h2>Request password reset</h2>
        <?php if ($flash !== null): ?><div class="notice <?= e($flash['type']) ?>"><?= $flash['message'] ?></div><?php endif; ?>
        <form action="send_reset.php" method="post" class="stacked-form">
            <label for="email">Registered email</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Send Reset Email</button>
        </form>
    </section>
</main>

<?= student_footer() ?>
</body>
</html>
