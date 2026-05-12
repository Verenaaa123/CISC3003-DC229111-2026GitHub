-- Import this file into the InfinityFree phpMyAdmin database for Scenario B.
-- Database should be: if0_41895031_paper02b

DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    subject VARCHAR(140) NOT NULL,
    category ENUM('coursework', 'phpmailer', 'database', 'deployment') NOT NULL,
    message TEXT NOT NULL,
    mail_status ENUM('sent', 'not_sent', 'debug_required') NOT NULL DEFAULT 'not_sent',
    debug_log TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_mail_status (mail_status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (name, email, password_hash)
VALUES ('Li Wuyue', 'dc229111@example.com', '$2y$12$p2RNg5lQeXawBmUxs4en9ezJF2pDeW3pvexG2XYn.yYdA8H3GM1i.');

INSERT INTO contact_messages (name, email, subject, category, message, mail_status, debug_log)
VALUES ('Li Wuyue', 'dc229111@example.com', 'PHPMailer test', 'phpmailer', 'This sample contact message is stored for dashboard testing.', 'debug_required', 'Sample debug row for Scenario B.');
