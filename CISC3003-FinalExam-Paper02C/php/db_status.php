<?php
declare(strict_types=1);
require __DIR__ . '/functions.php';
require __DIR__ . '/connect.php';

$databaseName = $conn->query('SELECT DATABASE() AS database_name')->fetch_assoc()['database_name'];
$users = $conn->query('SELECT id, name, email, activated_at, reset_token_expires_at, created_at FROM users ORDER BY id DESC LIMIT 8')->fetch_all(MYSQLI_ASSOC);
$total = $conn->query('SELECT COUNT(*) AS total FROM users')->fetch_assoc()['total'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario C Database Status</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<header class="site-header">
    <div>
        <p class="eyebrow">Scenario C</p>
        <h1>Signup Data Saved to MySQL</h1>
        <p class="lede">This page reads the MySQL users table after registration, activation, and reset testing.</p>
    </div>
    <nav aria-label="Main navigation">
        <a href="register.php">Signup</a>
        <a href="login.php">Login</a>
        <a href="db_status.php" aria-current="page">Database</a>
    </nav>
</header>

<main class="dashboard-grid">
    <section class="dashboard-card primary">
        <h2>Database Created</h2>
        <p>Current database: <?= e($databaseName) ?></p>
        <p>Total users: <?= e((string) $total) ?></p>
    </section>

    <section class="dashboard-card">
        <h2>Recent Registered Users</h2>
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Activated</th><th>Reset Expires</th><th>Created</th></tr></thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= e((string) $user['id']) ?></td>
                    <td><?= e($user['name']) ?></td>
                    <td><?= e($user['email']) ?></td>
                    <td><?= e($user['activated_at'] ?? 'Not yet') ?></td>
                    <td><?= e($user['reset_token_expires_at'] ?? 'None') ?></td>
                    <td><?= e($user['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?= student_footer() ?>
</body>
</html>
