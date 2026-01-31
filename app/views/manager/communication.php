<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication - Manager Portal</title>
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
            <a href="../../../../../login.html" class="logout-btn">Logout</a>
        </div>
    </header>

    <nav class="nav-tabs">
        <a href="admissions.html" class="nav-tab">Admissions</a>
        <a href="hr.html?view=staff" class="nav-tab">Teachers</a>
        <a href="index.html" class="nav-tab">Classes</a>
        <a href="hr.html?view=hr" class="nav-tab active">HR</a>
    </nav>

    <main class="content">
        <h1 class="page-title">School Communication</h1>

        <div class="quick-actions">
            <button class="quick-btn">📢 Send School Broadcast</button>
            <button class="quick-btn">💬 Message All Staff</button>
            <button class="quick-btn">📝 Create Announcement</button>
        </div>

        <div class="card">
            <h2 class="card-title">Recent Announcements</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Subject</th>
                            <th>Target Audience</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Jan 20, 2026</td>
                            <td>Winter Trip Announcement</td>
                            <td>Parents & Students</td>
                            <td><span class="badge badge-success">Sent</span></td>
                            <td><button class="btn btn-secondary btn-small">View</button></td>
                        </tr>
                        <tr>
                            <td>Jan 18, 2026</td>
                            <td>Staff Meeting - Next Monday</td>
                            <td>All Teachers</td>
                            <td><span class="badge badge-success">Sent</span></td>
                            <td><button class="btn btn-secondary btn-small">View</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Parent-School Messages Overview</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Messages Sent Today</h3>
                    <div class="stat-value">124</div>
                    <div class="stat-label">Via WhatsApp portal</div>
                </div>
                <div class="stat-card stat-info">
                    <h3>Teacher Response Rate</h3>
                    <div class="stat-value">92%</div>
                    <div class="stat-label">Average this week</div>
                </div>
            </div>
        </div>
    </main>
    <script src="../../../../../core.js"></script>
    <script>
        function renderAnnouncements() {
            // Static for now as per design, but could be dynamic
            console.log("Communication module loaded");
        }
        renderAnnouncements();
    </script>
</body>
</html>
