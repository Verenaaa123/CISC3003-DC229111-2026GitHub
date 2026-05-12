<?php
declare(strict_types=1);
require __DIR__ . '/functions.php';

$config = require __DIR__ . '/mail_config.php';
$logFile = dirname(__DIR__) . '/logs/email-debug.log';
$debugLog = is_file($logFile) ? file_get_contents($logFile) : 'No debug log has been written yet.';
$phpmailerInstalled = is_file(dirname(__DIR__) . '/vendor/phpmailer/phpmailer/src/PHPMailer.php') || is_file(dirname(__DIR__) . '/vendor/autoload.php');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario B Email Debug</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header class="site-header">
    <div>
        <p class="eyebrow">Scenario B</p>
        <h1>PHPMailer Debug Page</h1>
    </div>
    <nav aria-label="Main navigation">
        <a href="index.php">Contact</a>
        <a href="debug.php" aria-current="page">Debug</a>
        <a href="dashboard.php">Dashboard</a>
    </nav>
</header>

<main class="single-column">
    <section class="panel">
        <h2>Configuration Checklist</h2>
        <dl class="result-list">
            <dt>PHPMailer package</dt>
            <dd><?= $phpmailerInstalled ? 'Installed' : 'Missing: run composer install or add PHPMailer under vendor/phpmailer/phpmailer' ?></dd>
            <dt>SMTP host</dt>
            <dd><?= e($config['host']) ?>:<?= e((string) $config['port']) ?></dd>
            <dt>SMTP user</dt>
            <dd><?= e($config['username']) ?></dd>
            <dt>Recipient</dt>
            <dd><?= e($config['recipient_email']) ?></dd>
            <dt>Debug mode</dt>
            <dd><?= $config['debug'] ? 'Enabled' : 'Disabled' ?></dd>
        </dl>
    </section>
    <section class="panel">
        <h2>Latest Email Debug Log</h2>
        <pre><code><?= e($debugLog) ?></code></pre>
    </section>
</main>

<?= student_footer() ?>
</body>
</html>
