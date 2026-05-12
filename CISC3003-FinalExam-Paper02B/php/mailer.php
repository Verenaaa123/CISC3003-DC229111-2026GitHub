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

function build_mailer(array $config, array &$debugLines): PHPMailer
{
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

    return $mail;
}
