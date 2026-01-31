<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exams - Teacher Portal</title>
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
        <h1 class="page-title">Exam Marks Entry <br><small>رصد الدرجات</small></h1>

        <div class="card">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div class="form-group"><label>Exam Term</label><select><option>Mid-term Quiz</option><option>Final Exam</option></select></div>
                <div class="form-group"><label>Subject</label><select><option>Mathematics</option><option>English</option></select></div>
                <div class="form-group"><label>Class</label><select><option value="1">KG1 - A</option></select></div>
                <div class="form-group" style="display:flex; align-items:flex-end;"><button class="btn btn-primary w-100" onclick="loadExamList()">View Mark Sheet</button></div>
            </div>
        </div>

        <div class="card mt-20">
            <div class="card-header">
                <h2 class="card-title">Mark Sheet</h2>
                <button class="btn btn-secondary btn-small" onclick="window.print()">🖨️ Print Report</button>
            </div>
            <div id="mark-sheet-container"></div>
            <button class="btn btn-success w-100 mt-20" onclick="saveMarks()">Save Marks</button>
        </div>
    </main>

    <script src="core.js"></script>
    <script>
        function loadExamList() {
            const data = storage.get();
            const students = data.students.filter(s => s.class_id == 1);
            const rows = students.map(s => [
                s.student_code, 
                s.full_name,
                `<input type="number" class="w-100" placeholder="/20" id="mark_${s.id}">`,
                `<input type="text" class="w-100" placeholder="Notes..." id="note_${s.id}">`
            ]);
            document.getElementById('mark-sheet-container').innerHTML = ui.createTable(['Code', 'Name', 'Mark', 'Notes'], rows);
        }

        function saveMarks() {
            ui.showToast('Marks saved successfully');
        }

        loadExamList();
    </script>
</body>
</html>
