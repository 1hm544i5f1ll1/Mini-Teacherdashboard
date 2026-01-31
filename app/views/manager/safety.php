<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safety - Manager Portal</title>
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

    <div class="sub-nav">
        <a href="#" class="sub-nav-item" onclick="switchSection('gate-logs')">Gate Control</a>
        <a href="#" class="sub-nav-item active" onclick="switchSection('exit-logs')">Student Exit Log</a>
    </div>

    <main class="content">
        <!-- Section: Gate Control -->
        <section id="section-gate-logs" style="display: none;">
            <h1 class="page-title">Safety & Gate Control</h1>
            <div class="card">
                <h2 class="card-title">Recent Gate Entry/Exit Logs</h2>
                <div class="table-container">
                    <table>
                        <thead><tr><th>Time</th><th>Person Name</th><th>Role</th><th>Action Type</th></tr></thead>
                        <tbody>
                            <tr><td>08:15 AM</td><td>Ahmed Mahmoud</td><td>Student</td><td>Entry</td></tr>
                            <tr><td>08:10 AM</td><td>Ahmed Mohamed</td><td>Teacher</td><td>Entry</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Section: Student Exit Log (Image 6) -->
        <section id="section-exit-logs">
            <h1 class="page-title">Student Exit Log</h1>
            
            <div class="card">
                <h2 class="card-title">Search Exit Logs</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-top: 15px;">
                    <div class="form-group"><label>Academic Year</label><select><option>2025–2026</option></select></div>
                    <div class="form-group"><label>Class</label><select id="exit-filter-class"><option value="All">All Classes</option><option value="3A">3A</option><option value="3B">3B</option></select></div>
                    <div class="form-group" style="display: flex; align-items: flex-end;"><button class="btn btn-primary w-100" onclick="renderExitLogs()">View</button></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Exit Logs Table</h2>
                    <button class="btn btn-success btn-small" onclick="openExitModal()">➕ Record New Student Exit</button>
                </div>
                <div id="exit-logs-table"></div>
                <div style="margin-top: 15px; display: flex; justify-content: flex-end;">
                    <button class="btn btn-secondary btn-small" onclick="exportExitLogs()">Export to CSV</button>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal: Record Exit -->
    <div id="modal-exit" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Record Student Exit</h2>
                <button class="close-btn" onclick="closeModal('modal-exit')">&times;</button>
            </div>
            <form onsubmit="saveExitLog(event)">
                <div class="form-group"><label>Student Name</label><select id="exit-student" required></select></div>
                <div class="form-group"><label>Time</label><input type="time" id="exit-time" required></div>
                <div class="form-group"><label>Reason</label><input type="text" id="exit-reason" required placeholder="Reason for leaving"></div>
                <div class="form-group"><label>Picked up by</label><input type="text" id="exit-pickup" required placeholder="Name of guardian"></div>
                <div class="form-group"><label>Staff Member</label><input type="text" id="exit-staff" value="Admin User" readonly></div>
                <button type="submit" class="btn btn-primary w-100">Save Exit Log</button>
            </form>
        </div>
    </div>

    <script src="../../../../../core.js"></script>
    <script>
        function switchSection(sectionId) {
            document.querySelectorAll('main > section').forEach(s => s.style.display = 'none');
            const target = document.getElementById('section-' + sectionId);
            if (target) target.style.display = 'block';
            document.querySelectorAll('.sub-nav-item').forEach(i => i.classList.remove('active'));
            if (event.target.tagName === 'A') event.target.classList.add('active');
            if (sectionId === 'exit-logs') renderExitLogs();
        }

        function closeModal(id) { document.getElementById(id).classList.remove('show'); }

        function openExitModal() {
            const students = storage.get().students;
            document.getElementById('exit-student').innerHTML = students.map(s => `<option value="${s.id}">${s.full_name} (${s.student_code})</option>`).join('');
            document.getElementById('exit-time').value = new Date().toTimeString().slice(0, 5);
            document.getElementById('modal-exit').classList.add('show');
        }

        function saveExitLog(e) {
            e.preventDefault();
            const data = storage.get();
            const sid = document.getElementById('exit-student').value;
            const s = data.students.find(x => x.id == sid);
            
            if (!data.exit_logs) data.exit_logs = [];
            
            data.exit_logs.unshift({
                id: Date.now(),
                name: s.full_name,
                time: document.getElementById('exit-time').value,
                reason: document.getElementById('exit-reason').value,
                picked_by: document.getElementById('exit-pickup').value,
                staff: document.getElementById('exit-staff').value,
                date: new Date().toLocaleDateString(),
                status: 'Completed'
            });
            storage.save(data);
            ui.showToast('Student exit log saved successfully');
            closeModal('modal-exit');
            renderExitLogs();
        }

        function renderExitLogs() {
            const data = storage.get();
            const logs = data.exit_logs || [];
            const rows = logs.map(l => [l.date, l.name, l.time || '-', l.reason, l.picked_by || '-', l.staff || 'Admin']);
            document.getElementById('exit-logs-table').innerHTML = ui.createTable(['Date', 'Student Name', 'Exit Time', 'Reason', 'Picked Up By', 'Staff Member'], rows);
        }

        function exportExitLogs() {
            const data = storage.get().exit_logs || [];
            let csv = "Date,Student Name,Exit Time,Reason,Picked Up By,Staff Member\n";
            data.forEach(l => {
                csv += `"${l.date}","${l.name}","${l.time || ''}","${l.reason}","${l.picked_by || ''}","${l.staff || 'Admin'}"\n`;
            });
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', 'student_exit_logs.csv');
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        renderExitLogs();
    </script>
</body>
</html>
