<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions.php';

$user = $_SESSION['scenario_b_user'] ?? null;
$messages = [];

if ($user !== null) {
    require __DIR__ . '/connect.php';
    $result = $conn->query('SELECT name, email, subject, category, mail_status, created_at FROM contact_messages ORDER BY created_at DESC LIMIT 8');
    $messages = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario B Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<header class="site-header">
    <div><p class="eyebrow">Scenario B</p><h1>Dashboard</h1></div>
    <nav aria-label="Main navigation"><a href="index.php">Contact</a><a href="debug.php">Debug</a><?php if ($user !== null): ?><a href="logout.php">Logout</a><?php endif; ?></nav>
</header>

<main class="dashboard-grid">
    <?php if ($user === null): ?>
        <section class="dashboard-card"><h2>Login required</h2><p>Please login before viewing saved contact messages.</p><a class="button-link" href="login.php">Login</a></section>
    <?php else: ?>
        <section class="dashboard-card primary"><h2>Welcome, <?= e($user['name']) ?></h2><p>You became a user on <?= e($user['created_at']) ?>.</p></section>
        <section class="dashboard-card wide">
            <h2>Recent contact messages</h2>
            <table>
                <thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Category</th><th>Mail status</th><th>Date</th></tr></thead>
                <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?= e($message['name']) ?></td>
                        <td><?= e($message['email']) ?></td>
                        <td><?= e($message['subject']) ?></td>
                        <td><?= e($message['category']) ?></td>
                        <td><?= e($message['mail_status']) ?></td>
                        <td><?= e($message['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php endif; ?>
</main>

<?= student_footer() ?>
</body>
</html>
