<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string) filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $password = (string) ($_POST['password'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($errors === []) {
        require __DIR__ . '/connect.php';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
        try {
            $stmt->bind_param('sss', $name, $email, $hash);
            $stmt->execute();
            flash('success', 'Account created. Please login.');
            redirect_to('login.php');
        } catch (mysqli_sql_exception) {
            $errors[] = 'This email is already registered.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario B Register</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header class="site-header">
    <div><p class="eyebrow">Scenario B</p><h1>Register</h1></div>
    <nav aria-label="Main navigation"><a href="index.php">Contact</a><a href="login.php">Login</a></nav>
</header>
<main class="auth-shell">
    <section class="panel">
        <h2>Create account</h2>
        <?php if ($errors !== []): ?><div class="notice error"><?= e(implode(' ', $errors)) ?></div><?php endif; ?>
        <form method="post" class="stacked-form">
            <label for="name">Full name</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" minlength="8" required>
            <button type="submit">Register</button>
        </form>
    </section>
</main>
<?= student_footer() ?>
</body>
</html>
