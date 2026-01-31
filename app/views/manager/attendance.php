<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Attendance</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <div class="school-logo">🏫</div>
            <div class="school-name">City School - Manager Portal</div>
        </div>
        <div class="header-right">
            <div class="user-menu"><div class="user-avatar">AD</div><span>Admin Manager</span></div>
            <a href="../../../../../login.html" class="logout-btn">Logout</a>
        </div>
    </header>

    <nav class="nav-tabs">
        <a href="admissions.html" class="nav-tab">Admissions</a>
        <a href="hr.html?view=staff" class="nav-tab">Teachers</a>
        <a href="index.html" class="nav-tab active">Classes</a>
        <a href="hr.html?view=hr" class="nav-tab">HR</a>
    </nav>

    <div class="sub-nav">
        <a href="#" class="sub-nav-item active" onclick="switchTab('audit')">Attendance Audit</a>
        <a href="#" class="sub-nav-item" onclick="switchTab('exit')">Student Exit Logs</a>
    </div>

    <main class="content">
        <!-- Section: Audit -->
        <section id="sec-audit">
            <h1 class="page-title">Attendance Audit <br><small>تدقيق الحضور</small></h1>
    <div class="card">
        <div style="display: flex; gap: 15px;">
            <select id="a-class"><option value="1">KG1 - A</option></select>
            <input type="date" id="a-date" value="2026-01-22">
            <button class="btn btn-primary" onclick="renderAudit()">Generate Report</button>
        </div>
    </div>
            <div class="card mt-20">
                <h2 class="card-title">Daily Statistics</h2>
                <div class="stats-grid">
                    <div class="stat-card stat-success"><h3>Present</h3><div class="stat-value">94%</div></div>
                    <div class="stat-card stat-danger"><h3>Absent</h3><div class="stat-value">12</div></div>
                </div>
            </div>
        </section>

        <!-- Section: Exit Logs -->
        <section id="sec-exit" style="display:none;">
            <h1 class="page-title">Student Exit Logs</h1>
            <div class="card">
                <div style="display: flex; gap: 15px;">
                    <input type="text" placeholder="Search student name..." id="e-name">
                    <button class="btn btn-primary" onclick="renderExits()">Filter</button>
                    <button class="btn btn-secondary" onclick="ui.showToast('Exporting CSV...')">Export CSV</button>
                </div>
            </div>
            <div id="exit-list" class="mt-20"></div>
        </section>
    </main>

    <script src="../../../../../core.js"></script>
    <script>
        function switchTab(id) {
            document.querySelectorAll('main > section').forEach(s => s.style.display = 'none');
            document.getElementById('sec-' + id).style.display = 'block';
            document.querySelectorAll('.sub-nav-item').forEach(i => i.classList.remove('active'));
            if(event.target.tagName === 'A') event.target.classList.add('active');
            
            if(id === 'audit') renderAudit();
            if(id === 'exit') renderExits();
        }

        function renderAudit() {
            const data = storage.get();
            const classId = document.getElementById('a-class').value;
            const date = document.getElementById('a-date').value;
            
            // Filter attendance records for this class and date
            const records = (data.attendance_records || []).filter(r => {
                const s = data.students.find(x => x.id === r.student_id);
                return s && s.class_id == classId && r.date === date;
            });

            const rows = records.map(r => {
                const s = data.students.find(x => x.id === r.student_id);
                return [
                    s.student_code,
                    s.role || 'Student',
                    s.full_name,
                    r.date,
                    ui.statusBadge(r.status),
                    r.note || '-'
                ];
            });

            const auditContainer = document.createElement('div');
            auditContainer.id = 'audit-list';
            auditContainer.className = 'mt-20';
            auditContainer.innerHTML = `<h3>Audit Results for ${date}</h3>` + ui.createTable(['ID', 'Role', 'Name', 'Date', 'Status', 'Note'], rows);
            
            const existing = document.getElementById('audit-list');
            if (existing) existing.remove();
            document.getElementById('sec-audit').appendChild(auditContainer);
        }

        function renderExits() {
            const data = storage.get();
            const rows = data.exit_logs.map(l => [l.date, l.name, l.picked_by, l.reason]);
            document.getElementById('exit-list').innerHTML = ui.createTable(['Date', 'Student', 'Picked By', 'Reason'], rows);
        }

        // Initialize
        renderAudit();
    </script>
</body>
</html>
