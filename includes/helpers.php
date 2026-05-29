<?php
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function task_status(array $task): string
{
    if ($task['status'] === 'Completed') {
        return 'Completed';
    }

    if (strtotime($task['deadline']) < strtotime(date('Y-m-d'))) {
        return 'Overdue';
    }

    return $task['status'];
}

function priority_class(string $priority): string
{
    return strtolower($priority);
}

function format_date(string $date): string
{
    return date('M d, Y', strtotime($date));
}

function get_user_tasks(PDO $pdo, int $userId): array
{
    $stmt = $pdo->prepare(
        'SELECT tasks.*, categories.name AS category_name, categories.icon, categories.color
         FROM tasks
         JOIN categories ON tasks.category_id = categories.id
         WHERE tasks.user_id = ?
         ORDER BY tasks.deadline ASC'
    );
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function get_categories(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
}
