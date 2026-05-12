<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    } else {
        require __DIR__ . '/connect.php';
        $stmt = $conn->prepare('SELECT id, full_name, email, created_at FROM scenario_a_entries WHERE email = ? ORDER BY id DESC LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user === null) {
            $errors[] = 'No submitted Scenario A record was found for this email.';
        } else {
            $_SESSION['scenario_a_user'] = $user;
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario A Login</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header class="site-header">
    <div>
        <p class="eyebrow">Scenario A</p>
        <h1>Login to View Submitted Data</h1>
    </div>
    <nav aria-label="Main navigation">
        <a href="index.php">Form</a>
        <a href="login.php" aria-current="page">Login</a>
        <a href="dashboard.php">Dashboard</a>
    </nav>
</header>

<main class="auth-shell">
    <section class="panel">
        <h2>Sign in with submitted email</h2>
        <?php if ($errors !== []): ?>
            <div class="notice error"><?= e(implode(' ', $errors)) ?></div>
        <?php endif; ?>
        <form method="post" class="stacked-form">
            <label for="email">Email address from Scenario A form</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Login</button>
        </form>
    </section>
</main>

<?= student_footer() ?>
</body>
</html>
