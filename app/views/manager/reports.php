<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Manager Portal</title>
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
        <a href="index.html" class="nav-tab active">Classes</a>
        <a href="hr.html?view=hr" class="nav-tab">HR</a>
    </nav>

    <main class="content">
        <h1 class="page-title">Analytical Reports</h1>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div class="card">
                <h2 class="card-title">Attendance Reports</h2>
                <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 10px;">
                    <button class="btn btn-primary" style="text-align: left;">📅 Monthly Student Attendance</button>
                    <button class="btn btn-primary" style="text-align: left;">👨‍🏫 Staff Presence Summary</button>
                    <button class="btn btn-primary" style="text-align: left;">⚠️ Low Attendance Alert Report</button>
                </div>
            </div>

            <div class="card">
                <h2 class="card-title">Academic Reports</h2>
                <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 10px;">
                    <button class="btn btn-success" style="text-align: left;">📚 Grade Distribution by Class</button>
                    <button class="btn btn-success" style="text-align: left;">📈 Student Performance Trends</button>
                    <button class="btn btn-success" style="text-align: left;">🏆 Top Performers List</button>
                </div>
            </div>

            <div class="card">
                <h2 class="card-title">System & Usage</h2>
                <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 10px;">
                    <button class="btn btn-secondary" style="text-align: left;">📱 WhatsApp Portal Usage</button>
                    <button class="btn btn-secondary" style="text-align: left;">🔒 Gate Security Logs (Export)</button>
                    <button class="btn btn-secondary" style="text-align: left;">📋 Task Completion Analytics</button>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Custom Report Generator</h2>
            <div style="display: flex; gap: 15px; margin-top: 15px; align-items: flex-end;">
                <div style="flex: 1;">
                    <label>Report Type</label>
                    <select>
                        <option>Choose report type...</option>
                        <option>Attendance</option>
                        <option>Grades</option>
                        <option>Security</option>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label>Date Range</label>
                    <div style="display: flex; gap: 5px; align-items: center;">
                        <input type="date"> to <input type="date">
                    </div>
                </div>
                <button class="btn btn-primary">Download PDF</button>
            </div>
        </div>
    </main>
</body>
</html>
