<?php
$user = \App\Core\Auth::user();
$base = rtrim(APP_URL, '/');
$r = $registration;
$isEdit = $r !== null;
$canEdit = isset($can_edit) ? $can_edit : !$isEdit;
$errors = $_SESSION['form_errors'] ?? [];
$old = $_SESSION['form_old'] ?? $_POST;
unset($_SESSION['form_errors'], $_SESSION['form_old']);
$ageOct = $r && !empty($r['dob']) ? age_on_1_october($r['dob'], $r['academic_year'] ?? '2025-2026') : null;
$documents = $isEdit && isset($r['student_id']) ? (new \App\Modules\Registration\RegistrationRepo())->getDocuments($r['student_id']) : [];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'تعديل التسجيل' : 'تسجيل جديد' ?> - <?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= $base ?>/assets/style.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <div class="school-logo">🏫</div>
            <div class="school-name"><?= htmlspecialchars(APP_NAME) ?></div>
        </div>
        <div class="header-right">
            <span><?= htmlspecialchars($user['full_name'] ?? '') ?></span>
            <a href="<?= $base ?>/registration" class="logout-btn">العودة للقائمة</a>
        </div>
    </header>

    <main class="content">
        <h1 class="page-title"><?= $isEdit ? 'تعديل طلب التسجيل' : 'تسجيل طالب جديد' ?> <br><small>Registration Form</small></h1>

        <?php if (!empty($_GET['error'])): ?>
        <div class="card" style="background:#f8d7da;">خطأ: <?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        <?php if (!empty($_GET['success'])): ?>
        <div class="card" style="background:#d4edda;">تم رفع الملف.</div>
        <?php endif; ?>

        <div class="card">
            <form method="post" action="<?= $isEdit ? $base . '/registration/update' : $base . '/registration/store' ?>">
                <?= \App\Core\Csrf::field() ?>
                <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><?php endif; ?>

                <h2 class="card-title">بيانات الطالب</h2>
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:15px;">
                    <div class="form-group">
                        <label>الاسم الكامل (عربي) *</label>
                        <input type="text" name="full_name" value="<?= htmlspecialchars($old['full_name'] ?? $r['full_name'] ?? '') ?>" required <?= !$canEdit ? 'readonly' : '' ?>>
                        <?php if (isset($errors['full_name'])): ?><small style="color:red;"><?= htmlspecialchars($errors['full_name']) ?></small><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>الجنس *</label>
                        <select name="gender" required <?= !$canEdit ? 'readonly disabled' : '' ?>>
                            <option value="male" <?= ($old['gender'] ?? $r['gender'] ?? '') === 'male' ? 'selected' : '' ?>>ذكر</option>
                            <option value="female" <?= ($old['gender'] ?? $r['gender'] ?? '') === 'female' ? 'selected' : '' ?>>أنثى</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>تاريخ الميلاد *</label>
                        <input type="date" name="dob" value="<?= htmlspecialchars($old['dob'] ?? $r['dob'] ?? '') ?>" required <?= !$canEdit ? 'readonly' : '' ?>>
                    </div>
                    <?php if ($ageOct !== null): ?>
                    <div class="form-group">
                        <label>العمر في 1 أكتوبر</label>
                        <input type="text" value="<?= (int)$ageOct ?> سنة" readonly disabled>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label>الدين</label>
                        <input type="text" name="religion" value="<?= htmlspecialchars($old['religion'] ?? $r['religion'] ?? '') ?>" <?= !$canEdit ? 'readonly' : '' ?>>
                    </div>
                    <div class="form-group">
                        <label>الصف المطلوب *</label>
                        <select name="applied_grade" required <?= !$canEdit ? 'readonly disabled' : '' ?>>
                            <option value="PRE_KG" <?= ($old['applied_grade'] ?? $r['applied_grade'] ?? '') === 'PRE_KG' ? 'selected' : '' ?>>تمهيدي</option>
                            <option value="KG1" <?= ($old['applied_grade'] ?? $r['applied_grade'] ?? '') === 'KG1' ? 'selected' : '' ?>>KG1</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>العام الدراسي</label>
                        <input type="text" name="academic_year" value="<?= htmlspecialchars($old['academic_year'] ?? $r['academic_year'] ?? '2025-2026') ?>" <?= !$canEdit ? 'readonly' : '' ?>>
                    </div>
                </div>

                <h2 class="card-title mt-20">ولي الأمر</h2>
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:15px;">
                    <div class="form-group">
                        <label>اسم ولي الأمر *</label>
                        <input type="text" name="guardian_name" value="<?= htmlspecialchars($old['guardian_name'] ?? $r['guardian_name'] ?? '') ?>" required <?= !$canEdit ? 'readonly' : '' ?>>
                    </div>
                    <div class="form-group">
                        <label>هاتف ولي الأمر *</label>
                        <input type="text" name="guardian_phone" value="<?= htmlspecialchars($old['guardian_phone'] ?? $r['guardian_phone'] ?? '') ?>" required <?= !$canEdit ? 'readonly' : '' ?>>
                    </div>
                    <div class="form-group">
                        <label>هاتف إضافي</label>
                        <input type="text" name="guardian_phone_2" value="<?= htmlspecialchars($old['guardian_phone_2'] ?? $r['guardian_phone_2'] ?? '') ?>" <?= !$canEdit ? 'readonly' : '' ?>>
                    </div>
                    <div class="form-group">
                        <label>رقم الهوية (اختياري)</label>
                        <input type="text" name="guardian_national_id" value="<?= htmlspecialchars($old['guardian_national_id'] ?? $r['guardian_national_id'] ?? '') ?>" <?= !$canEdit ? 'readonly' : '' ?>>
                    </div>
                    <div class="form-group">
                        <label>صلة القرابة</label>
                        <input type="text" name="guardian_relationship" value="<?= htmlspecialchars($old['guardian_relationship'] ?? $r['guardian_relationship'] ?? '') ?>" <?= !$canEdit ? 'readonly' : '' ?>>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>العنوان (اختياري)</label>
                        <input type="text" name="guardian_address" value="<?= htmlspecialchars($old['guardian_address'] ?? $r['guardian_address'] ?? '') ?>" <?= !$canEdit ? 'readonly' : '' ?>>
                    </div>
                </div>

                <?php if ($canEdit): ?>
                <div class="mt-20">
                    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'حفظ التعديلات' : 'إنشاء التسجيل' ?></button>
                </div>
                <?php endif; ?>
            </form>
        </div>

        <?php if ($isEdit && !empty($r['student_id'])): ?>
        <div class="card mt-20">
            <h2 class="card-title">المرفقات (شهادة ميلاد، صور، مسوح لجان)</h2>
            <p>الأنواع المسموحة: PDF, JPG, PNG. الحد الأقصى 5 ميجا.</p>
            <?php if ($canEdit): ?>
            <form method="post" action="<?= $base ?>/documents/upload" enctype="multipart/form-data" style="margin-top:15px;">
                <?= \App\Core\Csrf::field() ?>
                <input type="hidden" name="student_id" value="<?= (int)$r['student_id'] ?>">
                <input type="hidden" name="admission_id" value="<?= (int)$r['id'] ?>">
                <select name="document_type">
                    <option value="birth_certificate">شهادة ميلاد</option>
                    <option value="photo">صورة</option>
                    <option value="committee_scan">مسح لجنة</option>
                    <option value="other">أخرى</option>
                </select>
                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
                <button type="submit" class="btn btn-primary">رفع</button>
            </form>
            <?php endif; ?>
            <ul style="margin-top:15px;">
                <?php foreach ($documents as $d): ?>
                <li>
                    <?= htmlspecialchars($d['file_name']) ?> (<?= htmlspecialchars($d['document_type'] ?? 'other') ?>)
                    <a href="<?= $base ?>/documents/download?id=<?= (int)$d['id'] ?>">تحميل</a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php if (empty($documents)): ?><p style="color:#666;">لا توجد مرفقات.</p><?php endif; ?>
        </div>
        <?php endif; ?>
    </main>
</body>
</html>
