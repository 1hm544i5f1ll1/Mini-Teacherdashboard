<?php
$user = \App\Core\Auth::user();
$isManager = \App\Core\Auth::isManager();
$base = rtrim(APP_URL, '/');
$errors = ['created' => 'تم الإنشاء', 'updated' => 'تم التحديث', 'submitted' => 'تم التقديم', 'approved' => 'تم القبول', 'rejected' => 'تم الرفض', 'locked' => 'تم القفل'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التسجيل - <?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= $base ?>/assets/style.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <div class="school-logo">🏫</div>
            <div class="school-name"><?= htmlspecialchars(APP_NAME) ?> - التسجيل</div>
        </div>
        <div class="header-right">
            <div class="user-menu"><span><?= htmlspecialchars($user['full_name'] ?? '') ?> (<?= $isManager ? 'مدير' : 'موظف' ?>)</span></div>
            <a href="<?= $base ?>/auth/logout" class="logout-btn">تسجيل الخروج</a>
        </div>
    </header>

    <nav class="nav-tabs">
        <a href="<?= $base ?>/registration" class="nav-tab active">قائمة التسجيل</a>
        <a href="<?= $base ?>/registration/create" class="nav-tab">تسجيل جديد</a>
        <?php if ($isManager): ?>
        <a href="<?= $base ?>/manager" class="nav-tab">لوحة المدير</a>
        <?php else: ?>
        <a href="<?= $base ?>/teacher" class="nav-tab">لوحة الموظف</a>
        <?php endif; ?>
    </nav>

    <main class="content">
        <h1 class="page-title">قائمة طلبات التسجيل <br><small>Registration List</small></h1>

        <?php if (!empty($_GET['success']) && isset($errors[$_GET['success']])): ?>
        <div class="card" style="background:#d4edda; border-color:#c3e6cb;">
            <?= htmlspecialchars($errors[$_GET['success']]) ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($_GET['error'])): ?>
        <div class="card" style="background:#f8d7da; border-color:#f5c6cb;">
            خطأ: <?= htmlspecialchars($_GET['error']) ?>
        </div>
        <?php endif; ?>

        <div class="card">
            <h2 class="card-title">بحث وفلترة</h2>
            <form method="get" action="<?= $base ?>/registration" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap:15px; margin-top:15px;">
                <input type="hidden" name="search" value="">
                <div class="form-group">
                    <label>البحث (اسم / هاتف / رقم)</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="اسم أو هاتف أو رقم">
                </div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status">
                        <option value="">الكل</option>
                        <option value="draft" <?= ($filters['status'] ?? '') === 'draft' ? 'selected' : '' ?>>مسودة</option>
                        <option value="submitted" <?= ($filters['status'] ?? '') === 'submitted' ? 'selected' : '' ?>>مقدم</option>
                        <option value="approved" <?= ($filters['status'] ?? '') === 'approved' ? 'selected' : '' ?>>مقبول</option>
                        <option value="rejected" <?= ($filters['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>مرفوض</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>الصف</label>
                    <select name="grade">
                        <option value="">الكل</option>
                        <option value="PRE_KG" <?= ($filters['grade'] ?? '') === 'PRE_KG' ? 'selected' : '' ?>>تمهيدي</option>
                        <option value="KG1" <?= ($filters['grade'] ?? '') === 'KG1' ? 'selected' : '' ?>>KG1</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>من تاريخ</label>
                    <input type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>إلى تاريخ</label>
                    <input type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>">
                </div>
                <div class="form-group" style="display:flex; align-items:flex-end;">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </form>
        </div>

        <div class="card mt-20">
            <h2 class="card-title">الطلبات (<?= count($registrations ?? []) ?>)</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>رقم</th>
                            <th>الاسم</th>
                            <th>الصف</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations ?? [] as $r): 
                            $statusAr = ['draft' => 'مسودة', 'submitted' => 'مقدم', 'approved' => 'مقبول', 'rejected' => 'مرفوض'];
                            $statusLabel = $statusAr[$r['status']] ?? $r['status'];
                            $locked = !empty($r['locked_at']);
                        ?>
                        <tr>
                            <td><?= (int)$r['id'] ?></td>
                            <td><?= htmlspecialchars($r['full_name']) ?></td>
                            <td><?= htmlspecialchars($r['applied_grade']) ?></td>
                            <td><?= $locked ? 'مقفل' : $statusLabel ?></td>
                            <td><?= date('Y-m-d', strtotime($r['created_at'])) ?></td>
                            <td>
                                <a href="<?= $base ?>/registration/summary?id=<?= (int)$r['id'] ?>" class="btn btn-primary btn-small">عرض / طباعة</a>
                                <?php if (!$locked): ?>
                                    <?php if ($r['status'] === 'draft'): ?>
                                    <a href="<?= $base ?>/registration/edit?id=<?= (int)$r['id'] ?>" class="btn btn-primary btn-small">تعديل</a>
                                    <form method="post" action="<?= $base ?>/registration/submit" style="display:inline;">
                                        <?= \App\Core\Csrf::field() ?>
                                        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-success btn-small">تقديم</button>
                                    </form>
                                    <?php elseif ($r['status'] === 'submitted' && $isManager): ?>
                                    <form method="post" action="<?= $base ?>/registration/approve" style="display:inline;">
                                        <?= \App\Core\Csrf::field() ?>
                                        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <input type="text" name="note" placeholder="ملاحظة" style="width:100px;">
                                        <button type="submit" class="btn btn-success btn-small">قبول</button>
                                    </form>
                                    <form method="post" action="<?= $base ?>/registration/reject" style="display:inline;">
                                        <?= \App\Core\Csrf::field() ?>
                                        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <input type="text" name="note" placeholder="ملاحظة" style="width:100px;">
                                        <button type="submit" class="btn btn-danger btn-small">رفض</button>
                                    </form>
                                    <?php elseif (in_array($r['status'], ['approved', 'rejected'], true) && $isManager): ?>
                                    <form method="post" action="<?= $base ?>/registration/lock" style="display:inline;">
                                        <?= \App\Core\Csrf::field() ?>
                                        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-primary btn-small">قفل السجل</button>
                                    </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (empty($registrations)): ?>
            <p style="padding:20px; color:#666;">لا توجد طلبات.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
