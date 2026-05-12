<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$email = trim((string) filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['available' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

require __DIR__ . '/connect.php';

$stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$exists = $stmt->get_result()->fetch_assoc() !== null;

echo json_encode([
    'available' => !$exists,
    'message' => $exists ? 'This email is already registered.' : 'This email is available.',
]);
