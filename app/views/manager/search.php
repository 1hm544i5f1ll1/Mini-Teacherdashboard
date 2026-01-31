<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager - Search</title>
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
        <h1 class="page-title">Global Search <br><small>البحث الشامل</small></h1>

        <div class="card">
            <div style="display: flex; gap: 15px;">
                <input type="text" id="global-q" placeholder="Search students, staff, IDs, or invoices..." style="flex:1;">
                <button class="btn btn-primary" onclick="ui.showToast('Searching...')">Search Everything</button>
            </div>
        </div>

        <div class="grid-2 mt-20">
            <div class="card">
                <h2 class="card-title">Search History</h2>
                <p style="color:#7f8c8d;">Your recent searches will appear here.</p>
            </div>
            <div class="card">
                <h2 class="card-title">Quick Filters</h2>
                <div class="flex gap-10 flex-wrap">
                    <button class="btn btn-secondary btn-small">All Students</button>
                    <button class="btn btn-secondary btn-small">Pending Admissions</button>
                    <button class="btn btn-secondary btn-small">Active Staff</button>
                    <button class="btn btn-secondary btn-small">Unpaid Fees</button>
                </div>
            </div>
        </div>
    </main>

    <script src="../../../../core.js"></script>
</body>
</html>
