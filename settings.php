<?php
$pageTitle = 'Settings';
require_once __DIR__ . '/includes/header.php';

$tasks = get_user_tasks($pdo, (int) $user['id']);
$completed = count(array_filter($tasks, fn($task) => task_status($task) === 'Completed'));
?>
<section class="content-grid two-columns">
    <div class="card profile-card-large">
        <div class="profile-avatar" style="background: <?php echo e($user['avatar_color']); ?>"><?php echo e(substr($user['name'], 0, 1)); ?></div>
        <h2><?php echo e($user['name']); ?></h2>
        <p><?php echo e($user['email']); ?></p>
        <div class="stats-row">
            <div><strong><?php echo count($tasks); ?></strong><span>Total tasks</span></div>
            <div><strong><?php echo $completed; ?></strong><span>Completed</span></div>
        </div>
    </div>

    <div class="card settings-card">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Preferences</p>
                <h3>Appearance</h3>
            </div>
        </div>
        <div class="setting-row">
            <div>
                <strong>Dark mode</strong>
                <p>Switch to a low-light interface. Preference is saved in your browser.</p>
            </div>
            <button class="btn btn-secondary" id="settingsThemeToggle" type="button">Toggle Theme</button>
        </div>
        <div class="setting-row">
            <div>
                <strong>Session</strong>
                <p>You are signed in with a predefined academic demo account.</p>
            </div>
            <a class="btn btn-ghost" href="php/logout.php">Logout</a>
        </div>
    </div>
</section>

<section class="card">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Course categories</p>
            <h3>Available subjects</h3>
        </div>
    </div>
    <div class="category-grid">
        <?php foreach (get_categories($pdo) as $category): ?>
            <div class="category-card" style="--tag-color: <?php echo e($category['color']); ?>">
                <span><?php echo e($category['icon']); ?></span>
                <strong><?php echo e($category['name']); ?></strong>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
