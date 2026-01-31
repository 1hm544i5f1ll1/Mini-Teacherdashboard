<?php use App\Core\Auth; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Portal - Dashboard</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/style.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <div class="school-logo">🏫</div>
            <div class="school-name">City School - Teacher Portal <br><small>بوابة المعلم</small></div>
        </div>
        <div class="header-right">
            <div class="user-menu"><div class="user-avatar"><?php echo substr(Auth::user()['full_name'], 0, 1); ?></div><span><?php echo htmlspecialchars(Auth::user()['full_name']); ?></span></div>
            <a href="<?php echo APP_URL; ?>/auth/logout" class="logout-btn">Logout</a>
        </div>
    </header>

    <nav class="nav-tabs">
        <a href="<?php echo APP_URL; ?>/registration" class="nav-tab">التسجيل / Registration</a>
        <a href="<?php echo APP_URL; ?>/teacher" class="nav-tab active">Teacher</a>
        <a href="<?php echo APP_URL; ?>/teacher/classes" class="nav-tab">Class</a>
        <a href="<?php echo APP_URL; ?>/teacher/hr" class="nav-tab">HR</a>
    </nav>

    <main class="content">
        <h1 class="page-title">Welcome Back, <?php echo explode(' ', Auth::user()['full_name'])[0]; ?>! <br><small>أهلاً بك</small></h1>
        
        <div class="stats-grid">
            <div class="stat-card"><h3>Today's Classes</h3><div class="stat-value">3</div></div>
            <div class="stat-card stat-warning"><h3>Pending Tasks</h3><div class="stat-value" id="pending-tasks">1</div></div>
            <div class="stat-card stat-info"><h3>Active Polls</h3><div class="stat-value">1</div></div>
        </div>

        <div class="grid-2 mt-20">
            <div class="card">
                <h2 class="card-title">Today's Schedule</h2>
                <table>
                    <thead><tr><th>Time</th><th>Class</th><th>Subject</th></tr></thead>
                    <tbody>
                        <tr><td>08:00 AM</td><td>KG1-A</td><td>Mathematics</td></tr>
                        <tr><td>10:30 AM</td><td>KG1-A</td><td>Art & Crafts</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card">
                <h2 class="card-title">Quick Actions</h2>
                <div class="flex-column gap-10">
                    <a href="<?php echo APP_URL; ?>/teacher/attendance" class="btn btn-primary">Take Class Attendance</a>
                    <a href="<?php echo APP_URL; ?>/teacher/exams" class="btn btn-success">Enter Exam Marks</a>
                    <a href="<?php echo APP_URL; ?>/teacher/hr" class="btn btn-secondary">Request Exit Permission</a>
                </div>
            </div>
        </div>
    </main>
    <script src="<?php echo APP_URL; ?>/assets/core.js"></script>
</body>
</html>
