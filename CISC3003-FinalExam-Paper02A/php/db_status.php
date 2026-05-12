<?php
declare(strict_types=1);
require __DIR__ . '/functions.php';
require __DIR__ . '/connect.php';

$databaseName = $conn->query('SELECT DATABASE() AS database_name')->fetch_assoc()['database_name'];
$columns = $conn->query('SHOW COLUMNS FROM scenario_a_entries')->fetch_all(MYSQLI_ASSOC);
$entries = $conn->query('SELECT id, full_name, email, student_number, course, study_mode, created_at FROM scenario_a_entries ORDER BY id DESC LIMIT 6')->fetch_all(MYSQLI_ASSOC);
$total = $conn->query('SELECT COUNT(*) AS total FROM scenario_a_entries')->fetch_assoc()['total'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario A Database Status</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<header class="site-header">
    <div>
        <p class="eyebrow">Scenario A</p>
        <h1>Database and SQL INSERT Evidence</h1>
        <p class="lede">This page verifies that the database, table, columns, and inserted rows exist after importing <code>db/database.sql</code> and submitting the PHP form.</p>
    </div>
    <nav aria-label="Main navigation">
        <a href="index.php">Form</a>
        <a href="db_status.php" aria-current="page">Database</a>
    </nav>
</header>

<main class="dashboard-grid">
    <section class="dashboard-card primary">
        <h2>Database Created</h2>
        <p>Current database: <?= e($databaseName) ?></p>
        <p>Total records in <code>scenario_a_entries</code>: <?= e((string) $total) ?></p>
    </section>

    <section class="dashboard-card">
        <h2>Table Columns</h2>
        <table>
            <thead><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr></thead>
            <tbody>
            <?php foreach ($columns as $column): ?>
                <tr>
                    <td><?= e($column['Field']) ?></td>
                    <td><?= e($column['Type']) ?></td>
                    <td><?= e($column['Null']) ?></td>
                    <td><?= e($column['Key']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="dashboard-card">
        <h2>Recent INSERT INTO Records</h2>
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Student</th><th>Course</th><th>Date</th></tr></thead>
            <tbody>
            <?php foreach ($entries as $entry): ?>
                <tr>
                    <td><?= e((string) $entry['id']) ?></td>
                    <td><?= e($entry['full_name']) ?></td>
                    <td><?= e($entry['email']) ?></td>
                    <td><?= e($entry['student_number']) ?></td>
                    <td><?= e($entry['course']) ?> / <?= e($entry['study_mode']) ?></td>
                    <td><?= e($entry['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</main>

<?= student_footer() ?>
</body>
</html>
