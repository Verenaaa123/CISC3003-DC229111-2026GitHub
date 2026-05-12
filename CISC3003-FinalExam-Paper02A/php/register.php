<?php
declare(strict_types=1);
require __DIR__ . '/functions.php';

$allowedCourses = ['php', 'mysql', 'security', 'deployment'];
$allowedModes = ['online', 'campus', 'hybrid'];
$allowedSkills = ['html', 'css', 'php', 'mysql'];
$errors = [];
$savedEntry = null;
$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';

if ($submitted) {
    $fullName = trim((string) filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $studentNumber = strtoupper(trim((string) filter_input(INPUT_POST, 'student_number', FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
    $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 16, 'max_range' => 99],
    ]);
    $course = (string) filter_input(INPUT_POST, 'course', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $studyMode = (string) filter_input(INPUT_POST, 'study_mode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $comments = trim((string) filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $rawSkills = $_POST['skills'] ?? [];
    $skills = array_values(array_intersect($allowedSkills, is_array($rawSkills) ? $rawSkills : []));
    $agree = filter_input(INPUT_POST, 'agree', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($fullName === '' || mb_strlen($fullName) > 100) {
        $errors['full_name'] = 'Full name is required and must be 100 characters or fewer.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email address is required.';
    }

    if (!preg_match('/^[A-Z0-9]{4,30}$/', $studentNumber)) {
        $errors['student_number'] = 'Student number must contain 4 to 30 letters or digits.';
    }

    if ($age === false || $age === null) {
        $errors['age'] = 'Age must be an integer from 16 to 99.';
    }

    if (!in_array($course, $allowedCourses, true)) {
        $errors['course'] = 'Please choose a valid course area.';
    }

    if (!in_array($studyMode, $allowedModes, true)) {
        $errors['study_mode'] = 'Please choose a valid study mode.';
    }

    if ($skills === []) {
        $errors['skills'] = 'Please choose at least one skill.';
    }

    if ($comments === '' || mb_strlen($comments) > 600) {
        $errors['comments'] = 'Learning goal is required and must be 600 characters or fewer.';
    }

    if ($agree !== 'yes') {
        $errors['agree'] = 'You must confirm the information before submitting.';
    }

    if ($errors === []) {
        require __DIR__ . '/connect.php';

        $skillsCsv = implode(', ', $skills);

        $sql = 'INSERT INTO scenario_a_entries
            (full_name, email, student_number, age, course, study_mode, skills, comments)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'sssissss',
            $fullName,
            $email,
            $studentNumber,
            $age,
            $course,
            $studyMode,
            $skillsCsv,
            $comments
        );
        $stmt->execute();

        $savedEntry = [
            'id' => (string) $stmt->insert_id,
            'full_name' => $fullName,
            'email' => $email,
            'student_number' => $studentNumber,
            'age' => (string) $age,
            'course' => $course,
            'study_mode' => $studyMode,
            'skills' => $skillsCsv,
            'comments' => $comments,
        ];
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scenario A Form Processing</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header class="site-header">
    <div>
        <p class="eyebrow">Scenario A</p>
        <h1>PHP Form Processing Result</h1>
    </div>
    <nav aria-label="Main navigation">
        <a href="index.php">Form</a>
        <a href="login.php">Login</a>
        <a href="dashboard.php">Dashboard</a>
    </nav>
</header>

<main class="single-column">
    <?php if (!$submitted): ?>
        <section class="panel">
            <h2>Open the form first</h2>
            <p>This page processes POST data from the Scenario A registration form.</p>
            <a class="button-link" href="index.php">Go to form</a>
        </section>
    <?php elseif ($savedEntry !== null): ?>
        <section class="panel success">
            <h2>Record inserted successfully</h2>
            <p>The submitted form data was validated with PHP filter functions and inserted with a MySQL prepared statement.</p>
            <dl class="result-list">
                <?php foreach ($savedEntry as $label => $value): ?>
                    <dt><?= e(str_replace('_', ' ', $label)) ?></dt>
                    <dd><?= e($value) ?></dd>
                <?php endforeach; ?>
            </dl>
            <pre><code>INSERT INTO scenario_a_entries
(full_name, email, student_number, age, course, study_mode, skills, comments)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)</code></pre>
            <a class="button-link" href="index.php">Submit another record</a>
        </section>
    <?php else: ?>
        <section class="panel error">
            <h2>Validation errors</h2>
            <ul>
                <?php foreach ($errors as $message): ?>
                    <li><?= e($message) ?></li>
                <?php endforeach; ?>
            </ul>
            <a class="button-link" href="index.php">Return to form</a>
        </section>
    <?php endif; ?>
</main>

<?= student_footer() ?>
</body>
</html>
