<?php use App\Core\Auth; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Portal - Dashboard</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/style.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <div class="school-logo">🏫</div>
            <div class="school-name">City School - Manager Portal <br><small>بوابة المدير</small></div>
        </div>
        <div class="header-right">
            <div class="user-menu"><div class="user-avatar"><?php echo substr(Auth::user()['full_name'], 0, 1); ?></div><span><?php echo htmlspecialchars(Auth::user()['full_name']); ?></span></div>
            <a href="<?php echo APP_URL; ?>/auth/logout" class="logout-btn">Logout</a>
        </div>
    </header>

    <nav class="nav-tabs">
        <a href="<?php echo APP_URL; ?>/registration" class="nav-tab">التسجيل / Registration</a>
        <a href="<?php echo APP_URL; ?>/manager/admissions" class="nav-tab">Admissions</a>
        <a href="<?php echo APP_URL; ?>/manager/teachers" class="nav-tab">Teachers</a>
        <a href="<?php echo APP_URL; ?>/manager" class="nav-tab active">Classes</a>
        <a href="<?php echo APP_URL; ?>/manager/hr" class="nav-tab">HR</a>
    </nav>

    <main class="content">
        <h1 class="page-title">School Overview <br><small>نظرة عامة على المدرسة</small></h1>
        
        <div class="stats-grid">
            <div class="stat-card"><h3>Total Students</h3><div class="stat-value">450</div></div>
            <div class="stat-card stat-success"><h3>Revenue (Term)</h3><div class="stat-value">£125k</div></div>
            <div class="stat-card stat-warning"><h3>Pending Admissions</h3><div class="stat-value">12</div></div>
            <div class="stat-card stat-danger"><h3>Unpaid Invoices</h3><div class="stat-value">8</div></div>
        </div>

        <div class="grid-2 mt-20">
            <div class="card">
                <h2 class="card-title">Recent Activity</h2>
                <div id="activity-log"></div>
            </div>
            <div class="card">
                <h2 class="card-title">Manager Quick Actions</h2>
                <div class="flex-column gap-10">
                    <a href="<?php echo APP_URL; ?>/registration" class="btn btn-primary">التسجيل / Registration List</a>
                    <a href="<?php echo APP_URL; ?>/manager/admissions" class="btn btn-primary">Admissions (Legacy)</a>
                    <a href="<?php echo APP_URL; ?>/manager/hr" class="btn btn-warning">Approve Exit Permissions</a>
                    <a href="<?php echo APP_URL; ?>/manager/finance" class="btn btn-success">Generate Term Invoices</a>
                </div>
            </div>
        </div>
    </main>
    <script src="<?php echo APP_URL; ?>/assets/core.js"></script>
</body>
</html>
