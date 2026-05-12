<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/functions.php';

$user = $_SESSION['scenario_a_user'] ?? null;
$recentEntries = [];

if ($user !== null) {
    require __DIR__ . '/connect.php';
    $stmt = $conn->prepare('SELECT full_name, email, course, study_mode, created_at FROM scenario_a_entries WHERE email = ? ORDER BY created_at DESC LIMIT 5');
    $stmt->bind_param('s', $user['email']);
    $stmt->execute();
    $recentEntries = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario A Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<header class="site-header">
    <div>
        <p class="eyebrow">Scenario A</p>
        <h1>Dashboard</h1>
    </div>
    <nav aria-label="Main navigation">
        <a href="index.php">Form</a>
        <a href="dashboard.php" aria-current="page">Dashboard</a>
        <?php if ($user !== null): ?><a href="logout.php">Logout</a><?php endif; ?>
    </nav>
</header>

<main class="dashboard-grid">
    <?php if ($user === null): ?>
        <section class="panel">
            <h2>Login required</h2>
            <p>Use an email address that has already been submitted through the Scenario A form.</p>
            <a class="button-link" href="login.php">Go to login</a>
        </section>
    <?php else: ?>
        <section class="dashboard-card primary">
            <h2>Welcome, <?= e($user['full_name']) ?></h2>
            <p>You became a Scenario A service user on <?= e($user['created_at']) ?>.</p>
        </section>
        <section class="dashboard-card">
            <h2>Recent form records</h2>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Mode</th>
                    <th>Submitted</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($recentEntries as $entry): ?>
                    <tr>
                        <td><?= e($entry['full_name']) ?></td>
                        <td><?= e($entry['email']) ?></td>
                        <td><?= e($entry['course']) ?></td>
                        <td><?= e($entry['study_mode']) ?></td>
                        <td><?= e($entry['created_at']) ?></td>
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
