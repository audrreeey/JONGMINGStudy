<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/helpers.php';
require_login();
$user = current_user();
$pageTitle = $pageTitle ?? 'StudySync';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> - StudySync</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="app-shell">
        <?php include __DIR__ . '/sidebar.php'; ?>
        <main class="main-content">
            <header class="topbar">
                <div>
                    <p class="eyebrow">StudySync workspace</p>
                    <h1><?php echo e($pageTitle); ?></h1>
                </div>
                <div class="topbar-actions">
                    <button class="icon-btn" id="themeToggle" type="button" aria-label="Toggle dark mode">
                        <i data-lucide="moon"></i>
                    </button>
                    <div class="profile-chip">
                        <span style="background: <?php echo e($user['avatar_color']); ?>"><?php echo e(substr($user['name'], 0, 1)); ?></span>
                        <div>
                            <strong><?php echo e($user['name']); ?></strong>
                            <small><?php echo e($user['email']); ?></small>
                        </div>
                    </div>
                </div>
            </header>
