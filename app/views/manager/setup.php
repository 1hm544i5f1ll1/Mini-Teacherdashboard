<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - Manager Portal</title>
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
        <h1 class="page-title">School Setup</h1>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Current Term</h3>
                <div class="stat-value">Term 2</div>
                <div class="stat-label">Ends: March 30, 2026</div>
            </div>
            <div class="stat-card stat-success">
                <h3>Total Classes</h3>
                <div class="stat-value">18</div>
                <div class="stat-label">All grades</div>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Manage School Settings</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
                <div class="card" style="border: 1px solid #ecf0f1;">
                    <h3>Academic Calendar</h3>
                    <p style="margin: 10px 0; font-size: 14px; color: #7f8c8d;">Configure terms, holidays, and exam dates.</p>
                    <button class="btn btn-primary btn-small">Edit Calendar</button>
                </div>
                <div class="card" style="border: 1px solid #ecf0f1;">
                    <h3>Class Management</h3>
                    <p style="margin: 10px 0; font-size: 14px; color: #7f8c8d;">Add, remove, or modify class sections and subjects.</p>
                    <button class="btn btn-primary btn-small">Manage Classes</button>
                </div>
                <div class="card" style="border: 1px solid #ecf0f1;">
                    <h3>Department Setup</h3>
                    <p style="margin: 10px 0; font-size: 14px; color: #7f8c8d;">Organize teachers and staff by department.</p>
                    <button class="btn btn-primary btn-small">Configure Departments</button>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Active Classes</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Class Code</th>
                            <th>Grade</th>
                            <th>Teacher</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>3A</strong></td>
                            <td>Grade 3</td>
                            <td>Ahmed Mohamed</td>
                            <td>25</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td><button class="btn btn-secondary btn-small">Edit</button></td>
                        </tr>
                        <tr>
                            <td><strong>3B</strong></td>
                            <td>Grade 3</td>
                            <td>Ahmed Mohamed</td>
                            <td>23</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td><button class="btn btn-secondary btn-small">Edit</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
