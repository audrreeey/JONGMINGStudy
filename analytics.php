<?php
$pageTitle = 'Analytics';
require_once __DIR__ . '/includes/header.php';

$tasks = get_user_tasks($pdo, (int) $user['id']);
$total = count($tasks);
$completed = count(array_filter($tasks, fn($task) => task_status($task) === 'Completed'));
$pending = $total - $completed;
$weekStart = date('Y-m-d', strtotime('monday this week'));
$weekEnd = date('Y-m-d', strtotime('sunday this week'));
$completedThisWeek = count(array_filter($tasks, function ($task) use ($weekStart, $weekEnd) {
    return task_status($task) === 'Completed' && $task['updated_at'] >= $weekStart && $task['updated_at'] <= $weekEnd . ' 23:59:59';
}));
$completionRate = $total > 0 ? round(($completed / $total) * 100) : 0;

$courseCounts = [];
foreach ($tasks as $task) {
    $courseCounts[$task['category_name']] = ($courseCounts[$task['category_name']] ?? 0) + 1;
}
arsort($courseCounts);
$mostActiveCourse = array_key_first($courseCounts) ?: 'No data';
?>
<section class="stats-grid">
    <div class="metric-card">
        <span><i data-lucide="check-circle"></i></span>
        <p>Completed this week</p>
        <strong><?php echo $completedThisWeek; ?></strong>
    </div>
    <div class="metric-card">
        <span><i data-lucide="clock"></i></span>
        <p>Pending tasks</p>
        <strong><?php echo $pending; ?></strong>
    </div>
    <div class="metric-card">
        <span><i data-lucide="book-open"></i></span>
        <p>Most active course</p>
        <strong><?php echo e($mostActiveCourse); ?></strong>
    </div>
    <div class="metric-card">
        <span><i data-lucide="trending-up"></i></span>
        <p>Completion rate</p>
        <strong><?php echo $completionRate; ?>%</strong>
    </div>
</section>

<section class="content-grid two-columns">
    <div class="card chart-card">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Status overview</p>
                <h3>Task distribution</h3>
            </div>
        </div>
        <canvas id="statusChart"></canvas>
    </div>
    <div class="card chart-card">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Courses</p>
                <h3>Assignments by course</h3>
            </div>
        </div>
        <canvas id="courseChart"></canvas>
    </div>
</section>

<script>
window.analyticsData = {
    completed: <?php echo $completed; ?>,
    pending: <?php echo $pending; ?>,
    courseLabels: <?php echo json_encode(array_keys($courseCounts)); ?>,
    courseValues: <?php echo json_encode(array_values($courseCounts)); ?>
};
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
