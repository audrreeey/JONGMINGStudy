<?php
session_start();
require_once __DIR__ . '/db.php';

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function current_user(): ?array
{
    global $pdo;

    if (!is_logged_in()) {
        return null;
    }

    $stmt = $pdo->prepare('SELECT id, name, email, avatar_color FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

function login_user(string $name, string $password): bool
{
    global $pdo;

    $stmt = $pdo->prepare('SELECT * FROM users WHERE name = ? LIMIT 1');
    $stmt->execute([$name]);
    $user = $stmt->fetch();

    // Academic demo project: passwords are stored plainly to keep the login flow easy to learn.
    // In a real application, use password_hash() and password_verify().
    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        return true;
    }

    return false;
}

function logout_user(): void
{
    session_unset();
    session_destroy();
}
