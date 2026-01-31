<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Finance</title>
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
        <a href="admissions.html" class="nav-tab">Admissions</a>
        <a href="hr.html?view=staff" class="nav-tab">Teachers</a>
        <a href="index.html" class="nav-tab">Classes</a>
        <a href="hr.html?view=hr" class="nav-tab active">HR</a>
    </nav>

    <main class="content">
        <h1 class="page-title">Finance Summary <br><small>الإدارة المالية</small></h1>

        <div class="stats-grid">
            <div class="stat-card stat-success"><h3>Total Collected</h3><div class="stat-value">£85,000</div></div>
            <div class="stat-card stat-warning"><h3>Pending Invoices</h3><div class="stat-value">£12,450</div></div>
            <div class="stat-card stat-danger"><h3>Overdue</h3><div class="stat-value">£3,200</div></div>
        </div>

        <div class="grid-2 mt-20">
            <div class="card">
                <h2 class="card-title">Fee Plans</h2>
                <div id="fee-plans"></div>
                <button class="btn btn-primary mt-20 w-100">Create New Plan</button>
            </div>
            <div class="card">
                <h2 class="card-title">Recent Payments</h2>
                <div id="payment-list"></div>
            </div>
        </div>
    </main>

    <script src="../../../../core.js"></script>
    <script>
        function renderFinance() {
            const planRows = [
                ['KG1 Standard', '£5,000', 'Annual'],
                ['KG2 Standard', '£5,500', 'Annual']
            ];
            document.getElementById('fee-plans').innerHTML = ui.createTable(['Plan Name', 'Amount', 'Cycle'], planRows);
            
            const payRows = [
                ['Jan 22', 'Ahmed Mahmoud', '£2,500', ui.statusBadge('Accepted')],
                ['Jan 21', 'Sara Ahmed', '£1,200', ui.statusBadge('Accepted')]
            ];
            document.getElementById('payment-list').innerHTML = ui.createTable(['Date', 'Student', 'Amount', 'Status'], payRows);
        }
        renderFinance();
    </script>
</body>
</html>
