<?php use App\Core\Auth; $base = rtrim(APP_URL, '/'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes - Teacher Portal</title>
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <div class="school-logo">🏫</div>
            <div class="school-name">City School - Teacher Portal</div>
        </div>
        <div class="header-right">
            <div class="user-menu">
                <div class="user-avatar"><?php echo substr(Auth::user()['full_name'] ?? 'U', 0, 1); ?></div>
                <span><?php echo htmlspecialchars(Auth::user()['full_name'] ?? ''); ?></span>
            </div>
            <a href="<?php echo $base; ?>/auth/logout" class="logout-btn">Logout</a>
        </div>
    </header>

    <!-- Navigation Tabs -->
    <nav class="nav-tabs">
        <a href="<?php echo $base; ?>/registration" class="nav-tab">التسجيل / Registration</a>
        <a href="<?php echo $base; ?>/teacher" class="nav-tab">Teacher</a>
        <a href="<?php echo $base; ?>/teacher/classes" class="nav-tab active">Class</a>
        <a href="<?php echo $base; ?>/teacher/hr" class="nav-tab">HR</a>
    </nav>

    <!-- Main Content -->
    <main class="content">
        <h1 class="page-title">My Classes</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Classes</h3>
                <div class="stat-value">5</div>
                <div class="stat-label">Regular classes</div>
            </div>
            <div class="stat-card stat-success">
                <h3>Students</h3>
                <div class="stat-value">120</div>
                <div class="stat-label">Total students</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Class List</h2>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Class Code</th>
                            <th>Class Name</th>
                            <th>Subject</th>
                            <th>Students</th>
                            <th>Schedule</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>3A</strong></td>
                            <td>Grade 3 - A</td>
                            <td>Mathematics</td>
                            <td>25</td>
                            <td>Sun, Tue, Thu</td>
                            <td>
                                <a href="<?php echo $base; ?>/teacher/students?class_id=1" class="btn btn-primary btn-small">View Students</a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>3B</strong></td>
                            <td>Grade 3 - B</td>
                            <td>Mathematics</td>
                            <td>23</td>
                            <td>Mon, Wed</td>
                            <td>
                                <a href="<?php echo $base; ?>/teacher/students?class_id=2" class="btn btn-primary btn-small">View Students</a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>3C</strong></td>
                            <td>Grade 3 - C</td>
                            <td>Mathematics</td>
                            <td>27</td>
                            <td>Sun, Tue, Wed</td>
                            <td>
                                <a href="<?php echo $base; ?>/teacher/students?class_id=3" class="btn btn-primary btn-small">View Students</a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>4A</strong></td>
                            <td>Grade 4 - A</td>
                            <td>Advanced Math</td>
                            <td>22</td>
                            <td>Mon, Thu</td>
                            <td>
                                <a href="<?php echo $base; ?>/teacher/students?class_id=4" class="btn btn-primary btn-small">View Students</a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>4B</strong></td>
                            <td>Grade 4 - B</td>
                            <td>Advanced Math</td>
                            <td>23</td>
                            <td>Tue, Wed</td>
                            <td>
                                <a href="<?php echo $base; ?>/teacher/students?class_id=5" class="btn btn-primary btn-small">View Students</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script src="core.js"></script>
</body>
</html>
