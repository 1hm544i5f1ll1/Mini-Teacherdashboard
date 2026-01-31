<?php use App\Core\Auth; use App\Core\Csrf; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - Teacher Portal</title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/style.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <div class="school-logo">🏫</div>
            <div class="school-name">City School - Teacher Portal</div>
        </div>
        <div class="header-right">
            <div class="user-menu"><div class="user-avatar"><?php echo substr(Auth::user()['full_name'], 0, 1); ?></div><span><?php echo htmlspecialchars(Auth::user()['full_name']); ?></span></div>
            <a href="<?php echo APP_URL; ?>/auth/logout" class="logout-btn">Logout</a>
        </div>
    </header>

    <nav class="nav-tabs">
        <a href="<?php echo APP_URL; ?>/teacher" class="nav-tab">Teacher</a>
        <a href="<?php echo APP_URL; ?>/teacher/classes" class="nav-tab active">Class</a>
        <a href="<?php echo APP_URL; ?>/teacher/hr" class="nav-tab">HR</a>
    </nav>

    <main class="content">
        <section id="sec-mark">
            <div class="card">
                <h2 class="card-title">Take Attendance <br><small>تسجيل الحضور</small></h2>
                <form id="attendanceForm" method="POST" action="<?php echo APP_URL; ?>/teacher/attendance">
                    <?php echo Csrf::field(); ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; margin-top: 15px;">
                        <div class="form-group">
                            <label>Class</label>
                            <select id="att-class" name="class_id" onchange="loadAttendance()">
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['grade'] . ' - ' . $class['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" id="att-date" name="date" value="<?php echo date('Y-m-d'); ?>" onchange="loadAttendance()">
                        </div>
                        <div class="form-group" style="display:flex; align-items:flex-end;">
                            <button type="button" class="btn btn-primary w-100" onclick="loadAttendance()">Load List</button>
                        </div>
                    </div>
                    
                    <div id="att-list-container" class="mt-20"></div>
                    <button type="submit" class="btn btn-success w-100 mt-20">Save Attendance</button>
                </form>
            </div>
        </section>
    </main>

    <script src="<?php echo APP_URL; ?>/assets/core.js"></script>
    <script>
        async function loadAttendance() {
            const classId = document.getElementById('att-class').value;
            const date = document.getElementById('att-date').value;
            
            if (!classId) {
                document.getElementById('att-list-container').innerHTML = '<p class="text-center">Please select a class first.</p>';
                return;
            }

            try {
                const response = await fetch(`<?php echo APP_URL; ?>/api/attendance/students?class_id=${classId}`);
                const data = await response.json();

                if (data.error) {
                    ui.showToast(data.error, 'danger');
                    return;
                }

                const students = data.students;
                if (students.length === 0) {
                    document.getElementById('att-list-container').innerHTML = '<p class="text-center">No students found for this class.</p>';
                    return;
                }

                const rows = students.map(s => [
                    s.id, 
                    'Student',
                    s.full_name,
                    date,
                    `<div class="flex gap-10" style="font-size: 0.9em;">
                        <label style="margin-bottom:0;"><input type="radio" name="attendance[${s.id}]" value="present" checked> Pres.</label>
                        <label style="margin-bottom:0;"><input type="radio" name="attendance[${s.id}]" value="absent"> Abs.</label>
                        <label style="margin-bottom:0;"><input type="radio" name="attendance[${s.id}]" value="late"> Late</label>
                    </div>`,
                    `<input type="text" name="reason[${s.id}]" placeholder="Note..." style="padding: 5px;">`
                ]);
                document.getElementById('att-list-container').innerHTML = ui.createTable(['ID', 'Role', 'Full Name', 'Date', 'Status', 'Notes'], rows);
            } catch (e) {
                ui.showToast('Failed to load students', 'danger');
            }
        }

        // Initialize on load
        window.onload = loadAttendance;
    </script>
</body>
</html>
