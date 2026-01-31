<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes - Teacher Portal</title>
    <link rel="stylesheet" href="style.css">
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
                <div class="user-avatar">AM</div>
                <span>Ahmed Mohamed</span>
            </div>
            <a href="login.html" class="logout-btn">Logout</a>
        </div>
    </header>

    <!-- Navigation Tabs -->
    <nav class="nav-tabs">
        <a href="index.html" class="nav-tab">Teacher</a>
        <a href="classes.html" class="nav-tab active">Class</a>
        <a href="requests.html" class="nav-tab">HR</a>
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
                                <a href="students.html?class=3A" class="btn btn-primary btn-small">View Students</a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>3B</strong></td>
                            <td>Grade 3 - B</td>
                            <td>Mathematics</td>
                            <td>23</td>
                            <td>Mon, Wed</td>
                            <td>
                                <a href="students.html?class=3B" class="btn btn-primary btn-small">View Students</a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>3C</strong></td>
                            <td>Grade 3 - C</td>
                            <td>Mathematics</td>
                            <td>27</td>
                            <td>Sun, Tue, Wed</td>
                            <td>
                                <a href="students.html?class=3C" class="btn btn-primary btn-small">View Students</a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>4A</strong></td>
                            <td>Grade 4 - A</td>
                            <td>Advanced Math</td>
                            <td>22</td>
                            <td>Mon, Thu</td>
                            <td>
                                <a href="students.html?class=4A" class="btn btn-primary btn-small">View Students</a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>4B</strong></td>
                            <td>Grade 4 - B</td>
                            <td>Advanced Math</td>
                            <td>23</td>
                            <td>Tue, Wed</td>
                            <td>
                                <a href="students.html?class=4B" class="btn btn-primary btn-small">View Students</a>
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
