<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks & Polls - Manager Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <div class="school-logo">🏫</div>
            <div class="school-name">City School - Manager Portal</div>
        </div>
        <div class="header-right">
            <div class="user-menu">
                <div class="user-avatar">AD</div>
                <span>Admin User</span>
            </div>
            <a href="../../../../login.html" class="logout-btn">Logout</a>
        </div>
    </header>

    <nav class="nav-tabs">
        <a href="admissions.html" class="nav-tab">Admissions</a>
        <a href="hr.html?view=staff" class="nav-tab">Teachers</a>
        <a href="index.html" class="nav-tab">Classes</a>
        <a href="hr.html?view=hr" class="nav-tab active">HR</a>
    </nav>

    <main class="content">
        <h1 class="page-title">Tasks & Polls Oversight</h1>

        <div class="stats-grid">
            <div class="stat-card stat-warning">
                <h3>Pending Teacher Tasks</h3>
                <div class="stat-value">42</div>
                <div class="stat-label">Across all departments</div>
            </div>
            <div class="stat-card stat-success">
                <h3>Active Polls</h3>
                <div class="stat-value">3</div>
                <div class="stat-label">Current school-wide polls</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">School-wide Polls</h2>
                <button class="btn btn-primary btn-small">Create New Poll</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Poll Title</th>
                            <th>End Date</th>
                            <th>Total Votes</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Preferred day for school trip?</strong></td>
                            <td>Jan 25, 2026</td>
                            <td>48/450 parents</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td><button class="btn btn-secondary btn-small">Results</button></td>
                        </tr>
                        <tr>
                            <td><strong>School Uniform Feedback</strong></td>
                            <td>Jan 15, 2026</td>
                            <td>312/450 parents</td>
                            <td><span class="badge badge-info">Closed</span></td>
                            <td><button class="btn btn-secondary btn-small">Results</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Staff Task Monitoring</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Teacher Name</th>
                            <th>Task Description</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ahmed Mohamed</td>
                            <td>Grade Algebra Quiz - 3A</td>
                            <td>Today</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                        </tr>
                        <tr>
                            <td>Fatma Hassan</td>
                            <td>Submit Science Project Grades</td>
                            <td>Jan 25, 2026</td>
                            <td><span class="badge badge-info">In Progress</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
