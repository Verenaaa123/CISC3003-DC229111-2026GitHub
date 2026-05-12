<?php
declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function student_footer(): string
{
    return '<footer>CISC3003 Web Programming: Li Wuyue + DC229111 + 2026</footer>';
}

function selected(string $actual, string $expected): string
{
    return $actual === $expected ? ' selected' : '';
}

function checked(array|string|null $actual, string $expected): string
{
    if (is_array($actual)) {
        return in_array($expected, $actual, true) ? ' checked' : '';
    }

    return $actual === $expected ? ' checked' : '';
}

function old(string $key, array $source, string $default = ''): string
{
    return isset($source[$key]) ? (string) $source[$key] : $default;
}
