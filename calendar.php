<?php
$pageTitle = 'Calendar';
require_once __DIR__ . '/includes/header.php';

$tasks = get_user_tasks($pdo, (int) $user['id']);
$month = isset($_GET['month']) ? (int) $_GET['month'] : (int) date('m');
$year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');
$firstDay = strtotime("$year-$month-01");
$daysInMonth = (int) date('t', $firstDay);
$startWeekday = (int) date('N', $firstDay);
$prevMonth = $month === 1 ? 12 : $month - 1;
$prevYear = $month === 1 ? $year - 1 : $year;
$nextMonth = $month === 12 ? 1 : $month + 1;
$nextYear = $month === 12 ? $year + 1 : $year;

$tasksByDate = [];
foreach ($tasks as $task) {
    $tasksByDate[$task['deadline']][] = $task;
}
?>
<section class="page-actions">
    <div>
        <p class="eyebrow">Monthly planner</p>
        <h2><?php echo date('F Y', $firstDay); ?></h2>
    </div>
    <div class="month-nav">
        <a class="icon-btn" href="calendar.php?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>" aria-label="Previous month"><i data-lucide="chevron-left"></i></a>
        <a class="icon-btn" href="calendar.php?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>" aria-label="Next month"><i data-lucide="chevron-right"></i></a>
    </div>
</section>

<section class="card calendar-card">
    <div class="calendar-grid calendar-weekdays">
        <?php foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day): ?>
            <strong><?php echo $day; ?></strong>
        <?php endforeach; ?>
    </div>
    <div class="calendar-grid">
        <?php for ($blank = 1; $blank < $startWeekday; $blank++): ?>
            <div class="calendar-day muted"></div>
        <?php endfor; ?>

        <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
            <?php
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $dayTasks = $tasksByDate[$date] ?? [];
            $isToday = $date === date('Y-m-d');
            ?>
            <button class="calendar-day <?php echo $isToday ? 'today' : ''; ?>" type="button" data-date="<?php echo $date; ?>">
                <span><?php echo $day; ?></span>
                <?php foreach (array_slice($dayTasks, 0, 3) as $task): ?>
                    <small class="<?php echo priority_class($task['priority']); ?>"><?php echo e($task['title']); ?></small>
                <?php endforeach; ?>
                <?php if (count($dayTasks) > 3): ?>
                    <em>+<?php echo count($dayTasks) - 3; ?> more</em>
                <?php endif; ?>
            </button>
        <?php endfor; ?>
    </div>
</section>

<section class="card" id="selectedDatePanel">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Selected date</p>
            <h3 id="selectedDateTitle">Click a date to view tasks</h3>
        </div>
    </div>
    <div class="task-list compact" id="selectedDateTasks">
        <div class="empty-state">Deadline details will appear here.</div>
    </div>
</section>

<script>
window.calendarTasks = <?php echo json_encode($tasksByDate); ?>;
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
