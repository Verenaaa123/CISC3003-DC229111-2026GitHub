<?php
declare(strict_types=1);
require __DIR__ . '/functions.php';

$scenarioTasks = [
    'A.01' => 'create a form in HTML using best practices',
    'A.02' => 'create form controls for simple text input',
    'A.03' => 'use multi-line text input with the textarea element',
    'A.04' => 'use select lists, radio buttons and checkboxes',
    'A.05' => 'process the submitted form data using PHP',
    'A.06' => 'validate the form data using filter functions',
    'A.07' => 'avoid an SQL injection attack',
    'A.08' => 'use a prepared statement to insert a new record into a database',
    'A.09' => 'create a database and table using phpMyAdmin',
    'A.10' => 'use an SQL INSERT INTO statement to insert a record',
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CISC3003 Final Exam Paper 02A</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/script.js" defer></script>
</head>
<body>
<header class="site-header">
    <div>
        <p class="eyebrow">Scenario A</p>
        <h1>Dynamic Web Form with PHP and MySQL</h1>
        <p class="lede">This page demonstrates form best practices, PHP validation, SQL injection prevention, and prepared statement insertion.</p>
    </div>
    <nav aria-label="Main navigation">
        <a href="index.php" aria-current="page">Form</a>
        <a href="login.php">Login</a>
        <a href="dashboard.php">Dashboard</a>
    </nav>
</header>

<main class="layout">
    <section class="panel task-panel" aria-labelledby="task-heading">
        <h2 id="task-heading">Scenario A Task Evidence</h2>
        <ol class="task-list">
            <?php foreach ($scenarioTasks as $code => $text): ?>
                <li><strong><?= e($code) ?>:</strong> <?= e($text) ?></li>
            <?php endforeach; ?>
        </ol>
    </section>

    <section class="panel" aria-labelledby="form-heading">
        <h2 id="form-heading">Student Service Registration Form</h2>
        <form class="stacked-form" action="register.php" method="post" novalidate data-enhanced-form>
            <fieldset>
                <legend>Basic Information</legend>

                <label for="full_name">Full name</label>
                <input type="text" id="full_name" name="full_name" autocomplete="name" maxlength="100" required>

                <label for="email">Email address</label>
                <input type="email" id="email" name="email" autocomplete="email" maxlength="120" required>

                <label for="student_number">Student number</label>
                <input type="text" id="student_number" name="student_number" maxlength="30" pattern="[A-Za-z0-9]{4,30}" required>

                <label for="age">Age</label>
                <input type="number" id="age" name="age" min="16" max="99" required>
            </fieldset>

            <fieldset>
                <legend>Course Request</legend>

                <label for="course">Course area</label>
                <select id="course" name="course" required>
                    <option value="">Please choose one option</option>
                    <option value="php">PHP Programming</option>
                    <option value="mysql">MySQL Database</option>
                    <option value="security">Web Security</option>
                    <option value="deployment">Web Deployment</option>
                </select>

                <p class="control-label">Study mode</p>
                <div class="choice-row" role="radiogroup" aria-label="Study mode">
                    <label><input type="radio" name="study_mode" value="online" required> Online</label>
                    <label><input type="radio" name="study_mode" value="campus"> Campus</label>
                    <label><input type="radio" name="study_mode" value="hybrid"> Hybrid</label>
                </div>

                <p class="control-label">Skills to improve</p>
                <div class="choice-grid">
                    <label><input type="checkbox" name="skills[]" value="html"> HTML</label>
                    <label><input type="checkbox" name="skills[]" value="css"> CSS</label>
                    <label><input type="checkbox" name="skills[]" value="php"> PHP</label>
                    <label><input type="checkbox" name="skills[]" value="mysql"> MySQL</label>
                </div>

                <label for="comments">Learning goal</label>
                <textarea id="comments" name="comments" rows="6" maxlength="600" required></textarea>
            </fieldset>

            <label class="terms"><input type="checkbox" name="agree" value="yes" required> I confirm that the submitted information is correct.</label>

            <div class="button-row">
                <button type="submit">Submit Form</button>
                <button type="reset" class="secondary">Reset</button>
            </div>
        </form>
    </section>
</main>

<?= student_footer() ?>
</body>
</html>
