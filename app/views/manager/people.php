<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Students</title>
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
        <a href="#" class="sub-nav-item active" onclick="switchSection('list')">Directory</a>
        <a href="#" class="sub-nav-item" onclick="switchSection('reg')">New Registration</a>
        <a href="#" class="sub-nav-item" onclick="switchSection('promo')">Promotions</a>
    </div>

    <main class="content">
        <!-- Directory -->
        <section id="sec-list">
            <h1 class="page-title">Student Directory</h1>
            <div class="card">
                <div id="stu-list"></div>
            </div>
        </section>

        <!-- New Registration -->
        <section id="sec-reg" style="display:none;">
            <h1 class="page-title">Register New Student</h1>
            <div class="card">
                <form onsubmit="saveStudent(event)">
                    <div class="grid-2">
                        <div class="form-group"><label>Full Name</label><input type="text" required></div>
                        <div class="form-group"><label>National ID</label><input type="text" required maxlength="14"></div>
                        <div class="form-group"><label>Gender</label><select><option>Male</option><option>Female</option></select></div>
                        <div class="form-group"><label>DOB</label><input type="date" required></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register Student</button>
                </form>
            </div>
        </section>

        <!-- Promotions -->
        <section id="sec-promo" style="display:none;">
            <h1 class="page-title">Student Promotions <br><small>ترقية الطلاب</small></h1>
            <div class="card">
                <p>Bulk promotion from KG1 to KG2 for next academic year.</p>
                <button class="btn btn-success mt-20" onclick="ui.showToast('Promotion processing...')">Execute Bulk Promotion</button>
            </div>
        </section>
    </main>

    <script src="../../../../../core.js"></script>
    <script>
        function switchSection(id) {
            document.querySelectorAll('main > section').forEach(s => s.style.display = 'none');
            document.getElementById('sec-' + id).style.display = 'block';
            document.querySelectorAll('.sub-nav-item').forEach(i => i.classList.remove('active'));
            if(event.target.tagName === 'A') event.target.classList.add('active');
            
            if(id === 'list') renderStudents();
        }

        function renderStudents() {
            const data = storage.get();
            const rows = data.students.map(s => [
                s.student_code, 
                s.full_name, 
                s.national_id, 
                s.gender,
                s.age || '-',
                ui.statusBadge(s.status)
            ]);
            document.getElementById('stu-list').innerHTML = ui.createTable(
                ['Code', 'Name', 'National ID', 'Gender', 'Age', 'Status'], 
                rows, 
                (r) => `<button class="btn btn-primary btn-small">Edit Profile</button>`
            );
        }

        function saveStudent(e) {
            e.preventDefault();
            ui.showToast('Student registered successfully');
            e.target.reset();
        }

        renderStudents();
    </script>
</body>
</html>
