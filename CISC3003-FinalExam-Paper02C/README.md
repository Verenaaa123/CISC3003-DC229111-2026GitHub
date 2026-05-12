# CISC3003 Final Exam Paper 02C

Student: Li Wuyue
Student ID: DC229111

This Scenario C project demonstrates:

- signup page
- PHP server-side signup validation
- MySQL storage with prepared statements
- login and logout
- JavaScript browser validation
- Ajax email availability validation through `php/validate_email.php`
- secure password reset by email
- email confirmation before login
- user dashboard after login

Import `db/database.sql` in phpMyAdmin before testing, then open `php/index.php`. The root `index.html` redirects to `php/index.php`. The sample activated account is:

- Email: `dc229111@example.com`
- Password: `Password123`

Edit `php/mail_config.php` before live email sending. If SMTP is not configured, the local activation/reset links are shown for testing.
