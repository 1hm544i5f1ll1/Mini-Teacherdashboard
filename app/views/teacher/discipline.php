<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discipline - Teacher Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <div class="school-logo">🏫</div>
            <div class="school-name">City School - Teacher Portal</div>
        </div>
        <div class="header-right">
            <div class="user-menu"><div class="user-avatar">AM</div><span>Ahmed Mohamed</span></div>
            <a href="login.html" class="logout-btn">Logout</a>
        </div>
    </header>

    <nav class="nav-tabs">
        <a href="index.html" class="nav-tab">Teacher</a>
        <a href="classes.html" class="nav-tab active">Class</a>
        <a href="requests.html" class="nav-tab">HR</a>
    </nav>

    <main class="content">
        <h1 class="page-title">Discipline Logs <br><small>سجل الانضباط</small></h1>

        <div class="card">
            <h2 class="card-title">New Incident Log</h2>
            <form onsubmit="saveIncident(event)">
                <div class="grid-2">
                    <div class="form-group"><label>Student</label><select id="disc-stu" required></select></div>
                    <div class="form-group"><label>Incident Type</label><select id="disc-type"><option>Behavior</option><option>Late</option><option>Homework</option></select></div>
                    <div class="form-group"><label>Severity</label><select id="disc-sev"><option>Low</option><option>Medium</option><option>High</option></select></div>
                    <div class="form-group"><label>Date & Time</label><input type="datetime-local" id="disc-date" required></div>
                </div>
                <div class="form-group"><label>Description</label><textarea id="disc-desc" required></textarea></div>
                <div class="form-group"><label>Action Taken</label><input type="text" id="disc-action" required></div>
                <button type="submit" class="btn btn-danger w-100">Save Incident Log</button>
            </form>
        </div>

        <div class="card mt-20">
            <h2 class="card-title">Recent Logs</h2>
            <div id="disc-list"></div>
        </div>
    </main>

    <script src="core.js"></script>
    <script>
        function renderLogs() {
            const data = storage.get();
            document.getElementById('disc-stu').innerHTML = data.students.map(s => `<option value="${s.id}">${s.full_name}</option>`).join('');
            
            const rows = data.discipline_logs.map(l => [
                l.date, l.student_name, l.type, ui.statusBadge(l.severity), l.action
            ]);
            document.getElementById('disc-list').innerHTML = ui.createTable(['Date', 'Student', 'Type', 'Severity', 'Action'], rows);
        }

        function saveIncident(e) {
            e.preventDefault();
            const data = storage.get();
            const stu = data.students.find(s => s.id == document.getElementById('disc-stu').value);
            data.discipline_logs.unshift({
                date: document.getElementById('disc-date').value,
                student_name: stu.full_name,
                type: document.getElementById('disc-type').value,
                severity: document.getElementById('disc-sev').value,
                action: document.getElementById('disc-action').value
            });
            storage.save(data);
            ui.showToast('Incident logged successfully');
            renderLogs();
            e.target.reset();
        }

        renderLogs();
    </script>
</body>
</html>
