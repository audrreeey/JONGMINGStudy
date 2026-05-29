<?php
$pageTitle = 'Tasks';
require_once __DIR__ . '/includes/header.php';

$categories = get_categories($pdo);
$tasks = get_user_tasks($pdo, (int) $user['id']);
$editingTask = null;

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = ? AND user_id = ?');
    $stmt->execute([(int) $_GET['edit'], (int) $user['id']]);
    $editingTask = $stmt->fetch();
}

$showForm = isset($_GET['action']) && $_GET['action'] === 'new' || $editingTask;
?>
<section class="page-actions">
    <div>
        <p class="eyebrow">Assignments</p>
        <h2>Manage your tasks</h2>
    </div>
    <a class="btn btn-primary" href="tasks.php?action=new">
        <i data-lucide="plus"></i>
        Add Task
    </a>
</section>

<?php if ($showForm): ?>
    <section class="card form-card">
        <div class="section-heading">
            <div>
                <p class="eyebrow"><?php echo $editingTask ? 'Edit task' : 'New task'; ?></p>
                <h3><?php echo $editingTask ? 'Update assignment details' : 'Add a new assignment'; ?></h3>
            </div>
            <a class="text-link" href="tasks.php">Close</a>
        </div>
        <form method="POST" action="php/task_actions.php" class="task-form">
            <input type="hidden" name="action" value="<?php echo $editingTask ? 'update' : 'create'; ?>">
            <?php if ($editingTask): ?>
                <input type="hidden" name="task_id" value="<?php echo (int) $editingTask['id']; ?>">
            <?php endif; ?>
            <label>
                <span>Title</span>
                <input name="title" value="<?php echo e($editingTask['title'] ?? ''); ?>" required>
            </label>
            <label>
                <span>Course</span>
                <select name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo (int) $category['id']; ?>" <?php echo ($editingTask['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo e($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="full-span">
                <span>Description</span>
                <textarea name="description" rows="3"><?php echo e($editingTask['description'] ?? ''); ?></textarea>
            </label>
            <label>
                <span>Priority</span>
                <select name="priority">
                    <?php foreach (['Low', 'Medium', 'High'] as $priority): ?>
                        <option <?php echo ($editingTask['priority'] ?? '') === $priority ? 'selected' : ''; ?>><?php echo $priority; ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                <span>Deadline</span>
                <input type="date" name="deadline" value="<?php echo e($editingTask['deadline'] ?? date('Y-m-d')); ?>" required>
            </label>
            <label>
                <span>Progress (%)</span>
                <input type="number" name="progress" min="0" max="100" value="<?php echo e((string) ($editingTask['progress'] ?? 0)); ?>">
            </label>
            <label>
                <span>Status</span>
                <select name="status">
                    <?php foreach (['Not Started', 'In Progress', 'Completed', 'Overdue'] as $status): ?>
                        <option <?php echo ($editingTask['status'] ?? '') === $status ? 'selected' : ''; ?>><?php echo $status; ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <div class="form-actions full-span">
                <button class="btn btn-primary" type="submit"><?php echo $editingTask ? 'Save Changes' : 'Create Task'; ?></button>
            </div>
        </form>
    </section>
<?php endif; ?>

<section class="task-board">
    <?php if (!$tasks): ?>
        <div class="card empty-state">No tasks yet. Add your first assignment to start planning.</div>
    <?php endif; ?>

    <?php foreach ($tasks as $task): ?>
        <?php $status = task_status($task); ?>
        <article class="task-card priority-<?php echo priority_class($task['priority']); ?>">
            <div class="task-card-top">
                <span class="category-tag" style="--tag-color: <?php echo e($task['color']); ?>">
                    <?php echo e($task['icon']); ?> <?php echo e($task['category_name']); ?>
                </span>
                <span class="status-pill <?php echo strtolower(str_replace(' ', '-', $status)); ?>"><?php echo e($status); ?></span>
            </div>
            <h3><?php echo e($task['title']); ?></h3>
            <p><?php echo e($task['description']); ?></p>
            <div class="progress-track small">
                <span style="width: <?php echo (int) $task['progress']; ?>%"></span>
            </div>
            <div class="task-meta">
                <span><i data-lucide="calendar"></i><?php echo format_date($task['deadline']); ?></span>
                <span class="priority-label <?php echo priority_class($task['priority']); ?>"><?php echo e($task['priority']); ?></span>
            </div>
            <div class="task-actions">
                <?php if ($status !== 'Completed'): ?>
                    <form method="POST" action="php/task_actions.php">
                        <input type="hidden" name="action" value="complete">
                        <input type="hidden" name="task_id" value="<?php echo (int) $task['id']; ?>">
                        <button class="icon-btn" type="submit" aria-label="Mark completed"><i data-lucide="check"></i></button>
                    </form>
                <?php endif; ?>
                <a class="icon-btn" href="tasks.php?edit=<?php echo (int) $task['id']; ?>" aria-label="Edit"><i data-lucide="pencil"></i></a>
                <form method="POST" action="php/task_actions.php" onsubmit="return confirm('Delete this task?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="task_id" value="<?php echo (int) $task['id']; ?>">
                    <button class="icon-btn danger" type="submit" aria-label="Delete"><i data-lucide="trash-2"></i></button>
                </form>
            </div>
        </article>
    <?php endforeach; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
