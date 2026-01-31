<?php use App\Core\Auth; $base = rtrim(APP_URL, '/'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Services - Teacher Portal</title>
    <link rel="stylesheet" href="<?php echo $base; ?>/assets/style.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <div class="school-logo">🏫</div>
            <div class="school-name">City School - Teacher Portal</div>
        </div>
        <div class="header-right">
            <div class="user-menu"><div class="user-avatar"><?php echo substr(Auth::user()['full_name'] ?? 'U', 0, 1); ?></div><span><?php echo htmlspecialchars(Auth::user()['full_name'] ?? ''); ?></span></div>
            <a href="<?php echo $base; ?>/auth/logout" class="logout-btn">Logout</a>
        </div>
    </header>

    <nav class="nav-tabs">
        <a href="<?php echo $base; ?>/registration" class="nav-tab">التسجيل / Registration</a>
        <a href="<?php echo $base; ?>/teacher" class="nav-tab">Teacher</a>
        <a href="<?php echo $base; ?>/teacher/classes" class="nav-tab">Class</a>
        <a href="<?php echo $base; ?>/teacher/hr" class="nav-tab active">HR</a>
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

    <script src="<?php echo $base; ?>/assets/core.js"></script>
    <script>
        function switchTab(id) {
            document.querySelectorAll('main > section').forEach(s => s.style.display = 'none');
            var el = document.getElementById('sec-' + id);
            if (el) el.style.display = 'block';
            document.querySelectorAll('.sub-nav-item').forEach(i => i.classList.remove('active'));
            if (event && event.target && event.target.tagName === 'A') event.target.classList.add('active');
            renderTrack();
        }

        function renderTrack() {
            var data = (typeof storage !== 'undefined' && storage.get) ? storage.get() : { exit_permissions: [], leave_requests: [] };
            var exitRows = (data.exit_permissions || []).map(function(p) { return [p.start, p.end, p.reason, (typeof ui !== 'undefined' && ui.statusBadge) ? ui.statusBadge(p.status) : p.status]; });
            var leaveRows = (data.leave_requests || []).map(function(l) { return [l.from, l.to, l.type, (typeof ui !== 'undefined' && ui.statusBadge) ? ui.statusBadge(l.status) : l.status]; });
            var exitEl = document.getElementById('exit-track');
            var leaveEl = document.getElementById('leave-track');
            if (exitEl && typeof ui !== 'undefined' && ui.createTable) exitEl.innerHTML = ui.createTable(['Start', 'End', 'Reason', 'Status'], exitRows); else if (exitEl) exitEl.innerHTML = exitRows.length ? '<p>No exit permissions yet.</p>' : '<p>No data.</p>';
            if (leaveEl && typeof ui !== 'undefined' && ui.createTable) leaveEl.innerHTML = ui.createTable(['From', 'To', 'Type', 'Status'], leaveRows); else if (leaveEl) leaveEl.innerHTML = leaveRows.length ? '<p>No leave requests yet.</p>' : '<p>No data.</p>';
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
