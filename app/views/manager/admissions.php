<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Admissions</title>
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
        <a href="admissions.html" class="nav-tab active">Admissions</a>
        <a href="hr.html?view=staff" class="nav-tab">Teachers</a>
        <a href="index.html" class="nav-tab">Classes</a>
        <a href="hr.html?view=hr" class="nav-tab">HR</a>
    </nav>

    <main class="content">
        <h1 class="page-title">KG Admission Management <br><small>إدارة قبول رياض الأطفال</small></h1>

        <!-- Search (Image 5) -->
        <div class="card">
            <h2 class="card-title">Admission Tests Search</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-top: 15px;">
                <div class="form-group"><label>Gender</label><select id="f-gen"><option>All</option><option>Male</option><option>Female</option></select></div>
                <div class="form-group"><label>Grade</label><select id="f-grade"><option>KG1</option><option>KG2</option></select></div>
                <div class="form-group"><label>Student Name</label><input type="text" id="f-name"></div>
                <div class="form-group"><label>National ID</label><input type="text" id="f-id"></div>
                <div class="form-group" style="display:flex; align-items:flex-end;"><button class="btn btn-primary w-100" onclick="renderTests()">View Results</button></div>
            </div>
        </div>

        <div class="card mt-20">
            <h2 class="card-title">Test Results Table</h2>
            <div id="test-list"></div>
        </div>
    </main>

    <!-- Modal: Result Form (Image 1 & 2) -->
    <div id="modal-res" class="modal">
        <div class="modal-content" style="max-width: 950px;">
            <div class="modal-header">
                <h2 class="modal-title">Admission Test Result Form – Kindergarten</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div id="res-form-content"></div>
        </div>
    </div>

    <script src="../../../../../core.js"></script>
    <script>
        function closeModal() { document.getElementById('modal-res').classList.remove('show'); }

        function renderTests() {
            const data = storage.get();
            const term = document.getElementById('f-name').value.toLowerCase();
            const filtered = data.admission_tests.filter(t => {
                const s = data.students.find(x => x.id === t.student_id);
                return s && (s.full_name.toLowerCase().includes(term) || s.student_code.toLowerCase().includes(term));
            });

            const rows = filtered.map(t => {
                const s = data.students.find(x => x.id === t.student_id);
                return [s.full_name, s.national_id, t.applied_grade, t.test_date, ui.statusBadge(t.overall_status)];
            });
            document.getElementById('test-list').innerHTML = ui.createTable(
                ['Student', 'National ID', 'Grade', 'Test Date', 'Status'], 
                rows,
                (r) => `<button class="btn btn-primary btn-small" onclick="viewFullForm('${r[1]}')">View Form</button>`
            );
        }

        function viewFullForm(nid) {
            const data = storage.get();
            const s = data.students.find(x => x.national_id === nid);
            const t = data.admission_tests.find(x => x.student_id === s.id);
            const committees = data.committees;

            let html = `
                <div style="border: 2px solid #333; padding: 20px;">
                    <div class="grid-2" style="border-bottom: 1px solid #eee; padding-bottom: 10px;">
                        <div>
                            <p><strong>Academic Year:</strong> 2025–2026</p>
                            <p><strong>Student Name:</strong> ${s.full_name}</p>
                            <p><strong>Gender:</strong> ${s.gender}</p>
                            <p><strong>Religion:</strong> ${s.religion}</p>
                        </div>
                        <div>
                            <p><strong>National ID:</strong> ${s.national_id}</p>
                            <p><strong>Place of Birth:</strong> Cairo</p>
                            <p><strong>Date of Birth:</strong> ${s.dob}</p>
                            <p><strong>Age:</strong> ${s.age}</p>
                        </div>
                    </div>
                    <div class="mt-20">
                        <h3 class="text-center">Committees Results Table</h3>
                        <table>
                            <thead><tr><th>Committee</th><th>Score</th><th>Result</th><th>Examiner</th><th>Notes</th></tr></thead>
                            <tbody>
                                ${committees.map(c => `
                                    <tr><td>${c} Committee</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-20 p-20" style="background:#f9f9f9; border-radius:10px;">
                        <h3>Final Decision</h3>
                        <div class="flex gap-10 mt-20">
                            <button class="btn btn-success" onclick="ui.showToast('Final Decision: Accepted')">Accept Student</button>
                            <button class="btn btn-danger" onclick="ui.showToast('Final Decision: Rejected')">Reject Student</button>
                            <button class="btn btn-secondary" onclick="window.print()">Print Form</button>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('res-form-content').innerHTML = html;
            document.getElementById('modal-res').classList.add('show');
        }

        renderTests();
    </script>
</body>
</html>
