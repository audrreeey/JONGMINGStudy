<?php
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';

    if (login_user($name, $password)) {
        header('Location: dashboard.php');
        exit;
    }

    $error = 'Nama atau password salah. Coba akun Nadine, Audrey, atau Richard.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudySync Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="login-body">
    <main class="login-shell">
        <section class="login-hero">
            <div class="brand-mark">S</div>
            <p class="eyebrow">Student productivity workspace</p>
            <h1>StudySync</h1>
            <p>Manage assignments, deadlines, focus sessions, and progress in one calm dashboard.</p>
            <div class="login-preview">
                <div>
                    <span>Today Focus</span>
                    <strong>Database Report</strong>
                </div>
                <div>
                    <span>Progress</span>
                    <strong>72%</strong>
                </div>
            </div>
        </section>

        <section class="login-card">
            <div class="login-card-header">
                <h2>Welcome back</h2>
                <button class="icon-btn" id="themeToggle" type="button" aria-label="Toggle dark mode">
                    <i data-lucide="moon"></i>
                </button>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" class="form-stack">
                <label>
                    <span>Name</span>
                    <select name="name" required>
                        <option value="">Choose account</option>
                        <option value="Nadine">Nadine</option>
                        <option value="Audrey">Audrey</option>
                        <option value="Richard">Richard</option>
                    </select>
                </label>
                <label>
                    <span>Password</span>
                    <input type="password" name="password" placeholder="Example: nadine123" required>
                </label>
                <button class="btn btn-primary" type="submit">Sign in</button>
            </form>

            <div class="demo-users">
                <p>Demo accounts</p>
                <span>Nadine / nadine123</span>
                <span>Audrey / audrey123</span>
                <span>Richard / richard123</span>
            </div>
        </section>
    </main>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
