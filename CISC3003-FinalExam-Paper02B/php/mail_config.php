<?php
declare(strict_types=1);

$localConfig = is_file(__DIR__ . '/config.local.php')
    ? require __DIR__ . '/config.local.php'
    : [];

$secure = getenv('CISC3003_SMTP_SECURE');
$secure = $localConfig['smtp_secure'] ?? ($secure === false ? 'tls' : $secure);
$secure = strtolower($secure) === 'none' ? '' : $secure;
$auth = $localConfig['smtp_auth'] ?? getenv('CISC3003_SMTP_AUTH');

return [
    'host' => $localConfig['smtp_host'] ?? getenv('CISC3003_SMTP_HOST') ?: 'smtp.gmail.com',
    'port' => (int) ($localConfig['smtp_port'] ?? getenv('CISC3003_SMTP_PORT') ?: 587),
    'auth' => $auth === false ? true : filter_var($auth, FILTER_VALIDATE_BOOLEAN),
    'username' => $localConfig['smtp_username'] ?? getenv('CISC3003_SMTP_USERNAME') ?: 'your_um_email@example.com',
    'password' => $localConfig['smtp_password'] ?? getenv('CISC3003_SMTP_PASSWORD') ?: 'your_app_password',
    'secure' => $secure,
    'from_email' => $localConfig['from_email'] ?? getenv('CISC3003_FROM_EMAIL') ?: 'your_um_email@example.com',
    'from_name' => 'Li Wuyue Contact Form',
    'recipient_email' => $localConfig['contact_to'] ?? getenv('CISC3003_CONTACT_TO') ?: 'dc229111@example.com',
    'recipient_name' => 'Li Wuyue',
    'debug' => filter_var(getenv('CISC3003_SMTP_DEBUG') ?: '1', FILTER_VALIDATE_BOOLEAN),
];
