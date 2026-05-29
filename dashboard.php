<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

$tasks = get_user_tasks($pdo, (int) $user['id']);
$totalTasks = count($tasks);
$completedTasks = count(array_filter($tasks, fn($task) => task_status($task) === 'Completed'));
$pendingTasks = $totalTasks - $completedTasks;
$completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
$today = date('Y-m-d');
$weekAhead = date('Y-m-d', strtotime('+7 days'));

$focusTasks = array_values(array_filter($tasks, function ($task) use ($today, $weekAhead) {
    return task_status($task) !== 'Completed'
        && ($task['priority'] === 'High' || $task['deadline'] <= $weekAhead);
}));
$focusTasks = array_slice($focusTasks, 0, 4);

$upcomingTasks = array_slice(array_filter($tasks, fn($task) => task_status($task) !== 'Completed'), 0, 5);
$deadlinesThisWeek = count(array_filter($tasks, fn($task) => task_status($task) !== 'Completed' && $task['deadline'] >= $today && $task['deadline'] <= $weekAhead));
?>
<section class="dashboard-grid">
    <div class="welcome-card">
        <div>
            <p class="eyebrow">Today is <?php echo date('l, M d'); ?></p>
            <h2>Welcome back, <?php echo e($user['name']); ?></h2>
            <p>Plan your focus, keep deadlines visible, and make steady progress.</p>
        </div>
        <a href="tasks.php?action=new" class="btn btn-light">
            <i data-lucide="plus"></i>
            Quick Add Task
        </a>
    </div>

    <div class="card progress-card">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Progress summary</p>
                <h3><?php echo $completionRate; ?>% complete</h3>
            </div>
            <i data-lucide="target"></i>
        </div>
        <div class="progress-track">
            <span style="width: <?php echo $completionRate; ?>%"></span>
        </div>
        <div class="stats-row">
            <div><strong><?php echo $completedTasks; ?></strong><span>Completed</span></div>
            <div><strong><?php echo $pendingTasks; ?></strong><span>Pending</span></div>
            <div><strong><?php echo $totalTasks; ?></strong><span>Total</span></div>
        </div>
    </div>

    <div class="card quote-card">
        <p class="eyebrow">Motivation</p>
        <blockquote id="quoteWidget">Small progress every day creates serious momentum.</blockquote>
    </div>

    <?php if ($deadlinesThisWeek >= 4): ?>
        <div class="alert-card">
            <i data-lucide="alert-triangle"></i>
            <div>
                <strong>Burnout alert</strong>
                <p>You have many deadlines this week. Consider prioritizing tasks.</p>
            </div>
        </div>
    <?php endif; ?>
</section>

<section class="content-grid two-columns">
    <div class="card">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Today&apos;s focus</p>
                <h3>Priority queue</h3>
            </div>
        </div>
        <div class="task-list compact">
            <?php if (!$focusTasks): ?>
                <div class="empty-state">No urgent tasks. Nice breathing room.</div>
            <?php endif; ?>
            <?php foreach ($focusTasks as $task): ?>
                <article class="task-item">
                    <span class="priority-dot <?php echo priority_class($task['priority']); ?>"></span>
                    <div>
                        <strong><?php echo e($task['title']); ?></strong>
                        <small><?php echo e($task['category_name']); ?> &middot; <?php echo format_date($task['deadline']); ?></small>
                    </div>
                    <span class="status-pill <?php echo strtolower(str_replace(' ', '-', task_status($task))); ?>"><?php echo e(task_status($task)); ?></span>
                </article>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Upcoming deadlines</p>
                <h3>Nearest assignments</h3>
            </div>
            <a href="calendar.php" class="text-link">View calendar</a>
        </div>
        <div class="timeline-list">
            <?php if (!$upcomingTasks): ?>
                <div class="empty-state">No upcoming deadlines.</div>
            <?php endif; ?>
            <?php foreach ($upcomingTasks as $task): ?>
                <div class="timeline-item">
                    <span style="background: <?php echo e($task['color']); ?>"></span>
                    <div>
                        <strong><?php echo e($task['title']); ?></strong>
                        <small><?php echo format_date($task['deadline']); ?> &middot; <?php echo e($task['priority']); ?> priority</small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="card pomodoro-card">
    <div>
        <p class="eyebrow">Pomodoro timer</p>
        <h3 id="timerMode">Focus session</h3>
    </div>
    <div class="timer-display" id="timerDisplay">25:00</div>
    <div class="timer-actions">
        <button class="btn btn-primary" id="timerStart" type="button">Start</button>
        <button class="btn btn-secondary" id="timerPause" type="button">Pause</button>
        <button class="btn btn-ghost" id="timerReset" type="button">Reset</button>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
