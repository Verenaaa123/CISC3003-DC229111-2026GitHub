-- Import this file into the InfinityFree phpMyAdmin database for Scenario A.
-- Database should be: if0_41895031_paper02a

DROP TABLE IF EXISTS scenario_a_entries;

CREATE TABLE scenario_a_entries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    student_number VARCHAR(30) NOT NULL,
    age TINYINT UNSIGNED NOT NULL,
    course ENUM('php', 'mysql', 'security', 'deployment') NOT NULL,
    study_mode ENUM('online', 'campus', 'hybrid') NOT NULL,
    skills VARCHAR(255) NOT NULL,
    comments TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO scenario_a_entries
    (full_name, email, student_number, age, course, study_mode, skills, comments)
VALUES
    ('Li Wuyue', 'dc229111@example.com', 'DC229111', 21, 'php', 'hybrid', 'html, css, php, mysql', 'This sample record demonstrates SQL INSERT INTO before testing the PHP prepared statement.');
