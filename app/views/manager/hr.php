<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - HR</title>
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
            <a href="../../../../login.html" class="logout-btn">Logout</a>
        </div>
    </header>

    <nav class="nav-tabs">
        <a href="admissions.html" class="nav-tab" id="nav-admissions">Admissions</a>
        <a href="hr.html?view=staff" class="nav-tab" id="nav-teachers">Teachers</a>
        <a href="index.html" class="nav-tab" id="nav-classes">Classes</a>
        <a href="hr.html?view=hr" class="nav-tab" id="nav-hr">HR</a>
    </nav>

    <div class="sub-nav">
        <a href="#" class="sub-nav-item active" onclick="switchTab('exit')">Exit Permissions</a>
        <a href="#" class="sub-nav-item" onclick="switchTab('leave')">Leave Requests</a>
        <a href="#" class="sub-nav-item" onclick="switchTab('staff')">Staff Directory</a>
    </div>

    <main class="content">
        <!-- Section: Exit Permissions (Image 8 style) -->
        <section id="sec-exit">
            <h1 class="page-title">Employee Exit Permissions <br><small>أذونات خروج الموظفين</small></h1>
            <div class="card">
                <div class="sub-nav" style="padding:0; margin-bottom:20px;">
                    <a href="#" class="sub-nav-item active" onclick="filterExit('Accepted')">Accepted</a>
                    <a href="#" class="sub-nav-item" onclick="filterExit('Pending')">Pending</a>
                    <a href="#" class="sub-nav-item" onclick="filterExit('Rejected')">Rejected</a>
                </div>
                <div id="exit-list"></div>
            </div>
        </section>

        <!-- Section: Leave Requests -->
        <section id="sec-leave" style="display:none;">
            <h1 class="page-title">Leave Approvals</h1>
            <div class="card">
                <div id="leave-list"></div>
            </div>
        </section>

        <!-- Section: Staff Directory -->
        <section id="sec-staff" style="display:none;">
            <h1 class="page-title">Staff Directory</h1>
            <div class="card">
                <div id="staff-list"></div>
            </div>
        </section>
    </main>

    <script src="../../../../core.js"></script>
    <script>
        function switchTab(id) {
            document.querySelectorAll('main > section').forEach(s => s.style.display = 'none');
            document.getElementById('sec-' + id).style.display = 'block';
            
            // Handle sub-nav active state
            document.querySelectorAll('.sub-nav .sub-nav-item').forEach(i => {
                i.classList.remove('active');
                if (i.getAttribute('onclick') && i.getAttribute('onclick').includes(`'${id}'`)) {
                    i.classList.add('active');
                }
            });
            
            if(id === 'exit') filterExit('Accepted');
            if(id === 'staff') renderStaff();
        }

        function filterExit(status) {
            const data = storage.get();
            const filtered = data.exit_permissions.filter(p => p.status === status);
            const rows = filtered.map(p => {
                const emp = data.employees.find(e => e.id === p.employee_id);
                return [emp.name, p.start, p.end, ui.statusBadge(p.status)];
            });
            
            document.getElementById('exit-list').innerHTML = ui.createTable(
                ['Employee Name', 'Start Time', 'End Time', 'Status'], 
                rows,
                (r) => status === 'Pending' ? `
                    <button class="btn btn-success btn-small">Approve</button>
                    <button class="btn btn-danger btn-small">Reject</button>
                ` : `<button class="btn btn-secondary btn-small">View</button>`
            );
        }

        function renderStaff() {
            const data = storage.get();
            const rows = data.employees.map(e => [e.code, e.name, e.job, e.dept]);
            document.getElementById('staff-list').innerHTML = ui.createTable(['Code', 'Name', 'Job Title', 'Department'], rows);
        }

        const urlParams = new URLSearchParams(window.location.search);
        const view = urlParams.get('view');
        if (view === 'staff') {
            document.getElementById('nav-teachers').classList.add('active');
            switchTab('staff');
        } else {
            document.getElementById('nav-hr').classList.add('active');
            switchTab('exit');
        }
    </script>
</body>
</html>
