<?php
$base = rtrim(APP_URL, '/');
$r = $registration;
$ageOct = !empty($r['dob']) ? age_on_1_october($r['dob'], $r['academic_year'] ?? '2025-2026') : null;
$statusAr = ['draft' => 'مسودة', 'submitted' => 'مقدم', 'approved' => 'مقبول', 'rejected' => 'مرفوض'];
$committeesConfig = $committees_config ?? [];
$committeeResults = $committee_results ?? [];
$resultLabels = ['pending' => '—', 'accepted' => 'Accepted / مقبول', 'acceptable' => 'Acceptable / مقبول بتحفظ', 'rejected' => 'Rejected / مرفوض'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملخص التسجيل #<?= (int)$r['id'] ?> - <?= htmlspecialchars(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= $base ?>/assets/style.css">
    <style>
        @media print { .no-print { display: none !important; } }
        .summary-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .summary-table th, .summary-table td { border: 1px solid #ddd; padding: 8px 12px; text-align: right; }
        .summary-table th { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="no-print" style="margin: 15px;">
        <a href="<?= $base ?>/registration" class="btn btn-primary">العودة للقائمة</a>
        <button onclick="window.print()" class="btn btn-primary">طباعة / حفظ PDF</button>
    </div>

    <main class="content" style="max-width: 800px; margin: 20px auto;">
        <h1 class="page-title">ملخص طلب التسجيل <br><small>Registration Summary</small></h1>

        <div class="card">
            <table class="summary-table">
                <tr><th colspan="2">بيانات الطالب</th></tr>
                <tr><th>رقم الطلب</th><td><?= (int)$r['id'] ?></td></tr>
                <tr><th>الاسم الكامل</th><td><?= htmlspecialchars($r['full_name']) ?></td></tr>
                <tr><th>الجنس</th><td><?= $r['gender'] === 'female' ? 'أنثى' : 'ذكر' ?></td></tr>
                <tr><th>تاريخ الميلاد</th><td><?= htmlspecialchars($r['dob']) ?></td></tr>
                <tr><th>العمر في 1 أكتوبر</th><td><?= $ageOct !== null ? (int)$ageOct . ' سنة' : '-' ?></td></tr>
                <tr><th>الدين</th><td><?= htmlspecialchars($r['religion'] ?? '-') ?></td></tr>
                <tr><th>الصف المطلوب</th><td><?= htmlspecialchars($r['applied_grade']) ?></td></tr>
                <tr><th>العام الدراسي</th><td><?= htmlspecialchars($r['academic_year'] ?? '') ?></td></tr>
            </table>
            <table class="summary-table">
                <tr><th colspan="2">ولي الأمر</th></tr>
                <tr><th>الاسم</th><td><?= htmlspecialchars($r['guardian_name']) ?></td></tr>
                <tr><th>الهاتف</th><td><?= htmlspecialchars($r['guardian_phone']) ?></td></tr>
                <tr><th>هاتف إضافي</th><td><?= htmlspecialchars($r['guardian_phone_2'] ?? '-') ?></td></tr>
                <tr><th>العنوان</th><td><?= htmlspecialchars($r['guardian_address'] ?? '-') ?></td></tr>
            </table>
            <table class="summary-table">
                <tr><th colspan="2">الحالة والموافقات</th></tr>
                <tr><th>الحالة</th><td><?= $statusAr[$r['status']] ?? $r['status'] ?><?= !empty($r['locked_at']) ? ' (مقفل)' : '' ?></td></tr>
                <?php if (!empty($r['decision_note'])): ?>
                <tr><th>ملاحظة القرار</th><td><?= htmlspecialchars($r['decision_note']) ?></td></tr>
                <?php endif; ?>
                <?php if (!empty($r['submitted_at'])): ?>
                <tr><th>تاريخ التقديم</th><td><?= date('Y-m-d H:i', strtotime($r['submitted_at'])) ?></td></tr>
                <?php endif; ?>
                <?php if (!empty($r['decision_at'])): ?>
                <tr><th>تاريخ القرار</th><td><?= date('Y-m-d H:i', strtotime($r['decision_at'])) ?></td></tr>
                <?php endif; ?>
                <?php if (!empty($r['locked_at'])): ?>
                <tr><th>تاريخ القفل</th><td><?= date('Y-m-d H:i', strtotime($r['locked_at'])) ?></td></tr>
                <?php endif; ?>
            </table>
            <?php if (!empty($committeesConfig)): ?>
            <h3 style="margin-top:20px;">اللجان (11 لجنة) — Committees</h3>
            <?php foreach ($committeesConfig as $ctype => $cconfig):
                $cres = $committeeResults[$ctype] ?? [];
                $items = $cres['items'] ?? [];
            ?>
            <div class="card mt-10" style="padding:15px;">
                <h4><?= htmlspecialchars($cconfig['label_ar']) ?> — <?= htmlspecialchars($cconfig['label_en']) ?></h4>
                <p><strong>Test officer:</strong> <?= htmlspecialchars($cres['examiner'] ?? '-') ?> | <strong>Result:</strong> <?= $resultLabels[$cres['result'] ?? 'pending'] ?? $cres['result'] ?></p>
                <?php if (!empty($cres['deputy_opinion'])): ?><p><strong>Stage deputy's opinion:</strong> <?= htmlspecialchars($cres['deputy_opinion']) ?></p><?php endif; ?>
                <table class="summary-table">
                    <thead><tr><th>#</th><th>Question</th><th>Answer</th></tr></thead>
                    <tbody>
                    <?php foreach ($cconfig['questions'] as $idx => $qtext): ?>
                        <tr>
                            <td><?= (int)$idx ?></td>
                            <td><?= htmlspecialchars($qtext) ?></td>
                            <td><?= htmlspecialchars($items[$idx] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($documents)): ?>
            <h3 style="margin-top:20px;">المرفقات</h3>
            <ul>
                <?php foreach ($documents as $d): ?>
                <li><?= htmlspecialchars($d['file_name']) ?> (<?= htmlspecialchars($d['document_type'] ?? 'other') ?>)</li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
