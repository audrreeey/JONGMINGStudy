<?php
$activePage = basename($_SERVER['PHP_SELF']);
$navItems = [
    ['file' => 'dashboard.php', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
    ['file' => 'tasks.php', 'label' => 'Tasks', 'icon' => 'check-square'],
    ['file' => 'calendar.php', 'label' => 'Calendar', 'icon' => 'calendar-days'],
    ['file' => 'analytics.php', 'label' => 'Analytics', 'icon' => 'bar-chart-3'],
    ['file' => 'settings.php', 'label' => 'Settings', 'icon' => 'settings'],
];
?>
<aside class="sidebar">
    <a href="dashboard.php" class="sidebar-brand">
        <span>S</span>
        <strong>StudySync</strong>
    </a>
    <nav>
        <?php foreach ($navItems as $item): ?>
            <a class="<?php echo $activePage === $item['file'] ? 'active' : ''; ?>" href="<?php echo e($item['file']); ?>">
                <i data-lucide="<?php echo e($item['icon']); ?>"></i>
                <?php echo e($item['label']); ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <a class="logout-link" href="php/logout.php">
        <i data-lucide="log-out"></i>
        Logout
    </a>
</aside>
