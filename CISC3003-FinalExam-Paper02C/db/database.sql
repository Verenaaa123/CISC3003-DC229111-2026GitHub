-- CISC3003 Final Exam Paper 02C
-- Student: Li Wuyue
-- Student ID: DC229111

CREATE DATABASE IF NOT EXISTS cisc3003_final_exam_p2c
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cisc3003_final_exam_p2c;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    activation_token_hash CHAR(64) NULL,
    activated_at DATETIME NULL,
    reset_token_hash CHAR(64) NULL,
    reset_token_expires_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_activation_token_hash (activation_token_hash),
    INDEX idx_reset_token_hash (reset_token_hash),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users
    (name, email, password_hash, activation_token_hash, activated_at)
VALUES
    ('Li Wuyue', 'dc229111@example.com', '$2y$12$p2RNg5lQeXawBmUxs4en9ezJF2pDeW3pvexG2XYn.yYdA8H3GM1i.', NULL, NOW());

-- Sample login after importing:
-- email: dc229111@example.com
-- password: Password123
