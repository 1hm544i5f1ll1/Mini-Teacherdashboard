<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - Teacher Portal</title>
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
        <h1 class="page-title">Students Directory <br><small>قائمة الطلاب</small></h1>

        <div class="card">
            <div style="display: flex; gap: 15px;">
                <input type="text" id="search-stu" placeholder="Search by name or ID..." style="flex:1;">
                <select id="filter-grade" style="width: auto;"><option value="All">All Grades</option><option>KG1</option><option>KG2</option></select>
                <button class="btn btn-primary" onclick="renderStudents()">Search</button>
            </div>
        </div>

        <div class="card mt-20">
            <h2 class="card-title">Class Roster - KG1-A</h2>
            <div id="stu-list"></div>
        </div>
    </main>

    <!-- Modal: Student Profile -->
    <div id="modal-stu" class="modal">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h2 class="modal-title">Student Profile <br><small>ملف الطالب</small></h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div id="stu-detail"></div>
        </div>
    </div>

    <script src="core.js"></script>
    <script>
        function closeModal() { document.getElementById('modal-stu').classList.remove('show'); }

        function renderStudents() {
            const data = storage.get();
            const term = document.getElementById('search-stu').value.toLowerCase();
            const gradeFilter = document.getElementById('filter-grade').value;
            
            const filtered = data.students.filter(s => {
                const matchesSearch = s.full_name.toLowerCase().includes(term) || s.student_code.toLowerCase().includes(term);
                const matchesGrade = gradeFilter === 'All' || s.student_code.startsWith(gradeFilter) || (s.class_id == 1 && gradeFilter === 'KG1'); // Simplified grade match
                return matchesSearch && matchesGrade;
            });
            
            const rows = filtered.map(s => [
                s.student_code, 
                s.full_name, 
                s.national_id || '-',
                s.gender, 
                s.age || '-',
                ui.statusBadge(s.status)
            ]);
            
            document.getElementById('stu-list').innerHTML = ui.createTable(
                ['Code', 'Full Name', 'National ID', 'Gender', 'Age', 'Status'], 
                rows,
                (r) => `<button class="btn btn-primary btn-small" onclick="viewProfile('${r[0]}')">View Profile</button>`
            );
        }

        function viewProfile(code) {
            const data = storage.get();
            const s = data.students.find(x => x.student_code === code);
            const g = data.guardians.find(x => x.id === 1); // Mock relationship

            document.getElementById('stu-detail').innerHTML = `
                <div class="grid-2">
                    <div>
                        <p><strong>Full Name:</strong> ${s.full_name}</p>
                        <p><strong>Code:</strong> ${s.student_code}</p>
                        <p><strong>DOB:</strong> ${s.dob}</p>
                        <p><strong>Age:</strong> ${s.age}</p>
                    </div>
                    <div>
                        <p><strong>Guardian:</strong> ${g.full_name} (${g.relationship})</p>
                        <p><strong>Contact:</strong> ${g.phone}</p>
                        <p class="alert alert-danger" style="padding: 5px 10px; font-size: 13px;">
                            <strong>Medical Alert:</strong> No known allergies.
                        </p>
                    </div>
                </div>
                <div class="mt-20 flex gap-10">
                    <button class="btn btn-secondary w-100" onclick="window.print()">Print Report Card</button>
                </div>
            `;
            document.getElementById('modal-stu').classList.add('show');
        }

        renderStudents();
    </script>
</body>
</html>
