<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function load_phpmailer(): bool
{
    $base = dirname(__DIR__) . '/vendor/phpmailer/phpmailer/src';
    $autoload = dirname(__DIR__) . '/vendor/autoload.php';

    if (is_file($autoload)) {
        require_once $autoload;
        return class_exists(PHPMailer::class);
    }

    $required = [
        $base . '/Exception.php',
        $base . '/PHPMailer.php',
        $base . '/SMTP.php',
    ];

    foreach ($required as $file) {
        if (!is_file($file)) {
            return false;
        }
    }

    foreach ($required as $file) {
        require_once $file;
    }

    return class_exists(PHPMailer::class);
}

function send_project_email(string $toEmail, string $toName, string $subject, string $htmlBody, string $textBody): array
{
    $config = require __DIR__ . '/mail_config.php';
    $debugLines = [];
    $sent = false;
    $error = null;

    try {
        if (!load_phpmailer()) {
            throw new RuntimeException('PHPMailer package was not found under vendor/phpmailer/phpmailer/src.');
        }

        if ($config['auth'] && ($config['username'] === 'your_um_email@example.com' || $config['password'] === 'your_app_password')) {
            throw new RuntimeException('SMTP username/password are placeholders. Configure php/mail_config.php before live sending.');
        }

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $config['host'];
        $mail->Port = $config['port'];
        $mail->SMTPAuth = $config['auth'];
        if ($config['auth']) {
            $mail->Username = $config['username'];
            $mail->Password = $config['password'];
        }
        $mail->SMTPSecure = $config['secure'];
        $mail->CharSet = 'UTF-8';

        if ($config['debug']) {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Debugoutput = static function (string $line, int $level) use (&$debugLines): void {
                $debugLines[] = '[' . $level . '] ' . $line;
            };
        }

        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = $textBody;
        $mail->send();
        $sent = true;
        $debugLines[] = 'Message accepted by SMTP server.';
    } catch (Throwable $exception) {
        $error = $exception->getMessage();
        $debugLines[] = $error;
    }

    $log = '[' . date('Y-m-d H:i:s') . '] ' . ($sent ? 'sent' : 'not_sent') . ' ' . $subject . PHP_EOL
        . implode(PHP_EOL, $debugLines) . PHP_EOL . PHP_EOL;
    file_put_contents(dirname(__DIR__) . '/logs/email-debug.log', $log, FILE_APPEND);

    return [
        'sent' => $sent,
        'error' => $error,
        'debug' => implode(PHP_EOL, $debugLines),
    ];
}
