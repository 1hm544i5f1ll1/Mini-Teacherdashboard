<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks - Teacher Portal</title>
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
        <a href="classes.html" class="nav-tab">Class</a>
        <a href="requests.html" class="nav-tab">HR</a>
    </nav>

    <main class="content">
        <h1 class="page-title">Tasks & Polls <br><small>المهام والاستبيانات</small></h1>

        <div class="grid-2">
            <div class="card">
                <h2 class="card-title">Assigned Tasks</h2>
                <div id="task-list"></div>
            </div>
            <div class="card">
                <h2 class="card-title">Active Polls</h2>
                <div id="poll-list"></div>
            </div>
        </div>

        <div class="card mt-20">
            <h2 class="card-title">Daily Teacher Checklist</h2>
            <div class="flex-column gap-10 mt-20">
                <label><input type="checkbox"> Classroom setup complete</label>
                <label><input type="checkbox"> Attendance recorded for all periods</label>
                <label><input type="checkbox"> Homework assigned and logged</label>
                <label><input type="checkbox"> Classroom clean and locked</label>
                <button class="btn btn-primary" onclick="ui.showToast('Checklist submitted')">Submit Daily Checklist</button>
            </div>
        </div>
    </main>

    <script src="core.js"></script>
    <script>
        function renderTasks() {
            const data = storage.get();
            const rows = data.tasks.map(t => [t.title, t.due, ui.statusBadge(t.status)]);
            document.getElementById('task-list').innerHTML = ui.createTable(['Task', 'Due Date', 'Status'], rows);
            
            const polls = data.polls.map(p => `
                <div style="padding:10px; border:1px solid #eee; border-radius:5px; margin-bottom:10px;">
                    <strong>${p.title}</strong>
                    <div class="flex-column gap-10 mt-20">
                        ${p.options.map(o => `<button class="btn btn-secondary btn-small" onclick="ui.showToast('Vote cast!')">${o}</button>`).join('')}
                    </div>
                </div>
            `).join('');
            document.getElementById('poll-list').innerHTML = polls || '<p>No active polls</p>';
        }
        renderTasks();
    </script>
</body>
</html>
