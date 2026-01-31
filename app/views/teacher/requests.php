<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Services - Teacher Portal</title>
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
        <a href="requests.html" class="nav-tab active">HR</a>
    </nav>

    <div class="sub-nav">
        <a href="#" class="sub-nav-item active" onclick="switchTab('leave')">Leave & Holiday Request</a>
        <a href="#" class="sub-nav-item" onclick="switchTab('exit')">Short Exit Permission</a>
    </div>

    <main class="content">
        <!-- Section: Leave & Holiday Request -->
        <section id="sec-leave">
            <div class="grid-2">
                <div class="card">
                    <h2 class="card-title">New Leave / Holiday Request <br><small>طلب إجازة أو عطلة</small></h2>
                    <form onsubmit="saveReq(event, 'leave')">
                        <div class="form-group"><label>Type</label><select id="l-type"><option>Annual Holiday</option><option>Sick Leave</option><option>Personal Leave</option><option>Unpaid Holiday</option></select></div>
                        <div class="form-group"><label>From Date</label><input type="date" id="l-from" required></div>
                        <div class="form-group"><label>To Date</label><input type="date" id="l-to" required></div>
                        <div class="form-group"><label>Reason / Details</label><textarea id="l-reason" required placeholder="Provide details about your holiday/leave..."></textarea></div>
                        <button type="submit" class="btn btn-success w-100">Submit Holiday Request</button>
                    </form>
                </div>
                <div class="card">
                    <h2 class="card-title">My Leave History</h2>
                    <div id="leave-track"></div>
                </div>
            </div>
        </section>

        <!-- Section: Exit Permission -->
        <section id="sec-exit" style="display:none;">
            <div class="grid-2">
                <div class="card">
                    <h2 class="card-title">New Short Exit Permission <br><small>طلب إذن خروج مؤقت</small></h2>
                    <form onsubmit="saveReq(event, 'exit')">
                        <div class="form-group"><label>Start Time</label><input type="time" id="e-start" required></div>
                        <div class="form-group"><label>End Time</label><input type="time" id="e-end" required></div>
                        <div class="form-group"><label>Reason</label><textarea id="e-reason" required placeholder="Short reason for temporary exit..."></textarea></div>
                        <button type="submit" class="btn btn-primary w-100">Submit Exit Request</button>
                    </form>
                </div>
                <div class="card">
                    <h2 class="card-title">My Recent Permissions</h2>
                    <div id="exit-track"></div>
                </div>
            </div>
        </section>
    </main>

    <script src="core.js"></script>
    <script>
        function switchTab(id) {
            document.querySelectorAll('main > section').forEach(s => s.style.display = 'none');
            document.getElementById('sec-' + id).style.display = 'block';
            document.querySelectorAll('.sub-nav-item').forEach(i => i.classList.remove('active'));
            if(event.target.tagName === 'A') event.target.classList.add('active');
            renderTrack();
        }

        function renderTrack() {
            const data = storage.get();
            const exitRows = data.exit_permissions.map(p => [p.start, p.end, p.reason, ui.statusBadge(p.status)]);
            document.getElementById('exit-track').innerHTML = ui.createTable(['Start', 'End', 'Reason', 'Status'], exitRows);
            
            const leaveRows = data.leave_requests.map(l => [l.from, l.to, l.type, ui.statusBadge(l.status)]);
            document.getElementById('leave-track').innerHTML = ui.createTable(['From', 'To', 'Type', 'Status'], leaveRows);
        }

        function saveReq(e, type) {
            e.preventDefault();
            const data = storage.get();
            if (type === 'exit') {
                data.exit_permissions.unshift({
                    start: document.getElementById('e-start').value,
                    end: document.getElementById('e-end').value,
                    reason: document.getElementById('e-reason').value,
                    status: 'Pending'
                });
            } else {
                data.leave_requests.unshift({
                    from: document.getElementById('l-from').value,
                    to: document.getElementById('l-to').value,
                    type: document.getElementById('l-type').value,
                    status: 'Pending'
                });
            }
            storage.save(data);
            ui.showToast(type === 'exit' ? 'Exit permission requested' : 'Holiday request submitted');
            e.target.reset();
            renderTrack();
        }

        renderTrack();
    </script>
</body>
</html>
