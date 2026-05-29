<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();

$userId = (int) $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'create') {
    $stmt = $pdo->prepare(
        'INSERT INTO tasks (user_id, category_id, title, description, priority, deadline, progress, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $userId,
        (int) $_POST['category_id'],
        trim($_POST['title']),
        trim($_POST['description']),
        $_POST['priority'],
        $_POST['deadline'],
        (int) $_POST['progress'],
        $_POST['status'],
    ]);
}

if ($action === 'update') {
    $stmt = $pdo->prepare(
        'UPDATE tasks
         SET category_id = ?, title = ?, description = ?, priority = ?, deadline = ?, progress = ?, status = ?
         WHERE id = ? AND user_id = ?'
    );
    $stmt->execute([
        (int) $_POST['category_id'],
        trim($_POST['title']),
        trim($_POST['description']),
        $_POST['priority'],
        $_POST['deadline'],
        (int) $_POST['progress'],
        $_POST['status'],
        (int) $_POST['task_id'],
        $userId,
    ]);
}

if ($action === 'complete') {
    $stmt = $pdo->prepare('UPDATE tasks SET status = "Completed", progress = 100 WHERE id = ? AND user_id = ?');
    $stmt->execute([(int) $_POST['task_id'], $userId]);
}

if ($action === 'delete') {
    $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = ? AND user_id = ?');
    $stmt->execute([(int) $_POST['task_id'], $userId]);
}

header('Location: ../tasks.php');
exit;
