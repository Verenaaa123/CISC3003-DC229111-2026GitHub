<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions.php';

$flash = take_flash();
$tasks = [
    'B.01' => 'create a contact form in HTML, including client-side validation',
    'B.02' => 'install and configure the PHPMailer package',
    'B.03' => 'send email using PHPMailer',
    'B.04' => 'debug problems when sending the email',
    'B.05' => 'use the post / redirect / get pattern',
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CISC3003 Final Exam Paper 02B</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/script.js" defer></script>
</head>
<body>
<header class="site-header">
    <div>
        <p class="eyebrow">Scenario B</p>
        <h1>Contact Form with PHPMailer</h1>
        <p class="lede">This project validates contact data in the browser and server, sends email with PHPMailer, records debug information, and redirects after POST.</p>
    </div>
    <nav aria-label="Main navigation">
        <a href="index.php" aria-current="page">Contact</a>
        <a href="debug.php">Debug</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
        <a href="dashboard.php">Dashboard</a>
    </nav>
</header>

<main class="layout">
    <section class="panel">
        <h2>Scenario B Task Evidence</h2>
        <ol class="task-list">
            <?php foreach ($tasks as $code => $text): ?>
                <li><strong><?= e($code) ?>:</strong> <?= e($text) ?></li>
            <?php endforeach; ?>
        </ol>
        <p class="note">PHPMailer is loaded from <code>vendor/phpmailer/phpmailer/src</code>. Edit <code>php/mail_config.php</code> or set environment variables before real email delivery.</p>
    </section>

    <section class="panel">
        <h2>Contact Li Wuyue</h2>
        <?php if ($flash !== null): ?>
            <div class="notice <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>
        <form action="send_contact.php" method="post" class="stacked-form" data-contact-form novalidate>
            <label for="name">Your name</label>
            <input type="text" id="name" name="name" autocomplete="name" maxlength="100" required>

            <label for="email">Your email</label>
            <input type="email" id="email" name="email" autocomplete="email" maxlength="120" required>

            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" minlength="4" maxlength="140" required>

            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">Please choose</option>
                <option value="coursework">Coursework</option>
                <option value="phpmailer">PHPMailer</option>
                <option value="database">Database</option>
                <option value="deployment">Deployment</option>
            </select>

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="7" minlength="10" maxlength="1500" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </section>
</main>

<?= student_footer() ?>
</body>
</html>
