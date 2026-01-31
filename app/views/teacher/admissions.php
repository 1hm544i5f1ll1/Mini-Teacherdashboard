<?php use App\Core\Auth; use App\Core\Csrf; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admissions - Teacher Portal</title>
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
        <a href="<?php echo APP_URL; ?>/teacher/classes" class="nav-tab">Class</a>
        <a href="<?php echo APP_URL; ?>/teacher/hr" class="nav-tab">HR</a>
    </nav>

    <main class="content">
        <h1 class="page-title">Assigned Committee Evaluations <br><small>تقييم لجان القبول</small></h1>

        <div class="card">
            <h2 class="card-title">Candidates for Review</h2>
            <div id="candidate-list">
                <?php if (empty($admissions)): ?>
                    <p class="text-center">No candidates found.</p>
                <?php else: ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Candidate</th>
                                    <th>Grade</th>
                                    <th>Test Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($admissions as $a): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($a['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($a['grade']); ?></td>
                                        <td><?php echo htmlspecialchars($a['test_datetime_from']); ?></td>
                                        <td><span class="badge badge-warning"><?php echo htmlspecialchars($a['status']); ?></span></td>
                                        <td>
                                            <button class="btn btn-primary btn-small" onclick="openEval(<?php echo $a['id']; ?>, 'Educational')">Edu Eval</button>
                                            <button class="btn btn-warning btn-small" onclick="openEval(<?php echo $a['id']; ?>, 'Behavioral')">Beh Eval</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Modal: Evaluation Form -->
    <div id="modal-eval" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h2 class="modal-title" id="eval-title">Committee Rubric</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div id="eval-form-container"></div>
        </div>
    </div>

    <script src="<?php echo APP_URL; ?>/assets/core.js"></script>
    <script>
        function closeModal() { document.getElementById('modal-eval').classList.remove('show'); }

        function openEval(admissionId, type) {
            const isBeh = type === 'Behavioral';
            const criteria = isBeh ? [
                'Pronunciation of letters', 'Ability to recite Al-Fatiha', 'Recognizing pictures',
                'Recognizing some behaviors', 'Language development', 'Age-appropriate development',
                'Response to visual tools'
            ] : [
                'Letter articulation & pronunciation', 'Identifying body parts', 'Identifying family members',
                'Identifying vegetables & fruits', 'Shape matching', 'Motor coordination', 'Interaction with adults',
                'Response to teacher', 'Identify colors', 'Identify birds'
            ];

            const ratings = isBeh ? 
                ['Excellent', 'Very Good', 'Good', 'Acceptable', 'Not Acceptable'] : 
                ['Pass', 'Good', 'Very Good', 'Excellent'];

            let html = `
                <form action="<?php echo APP_URL; ?>/teacher/admissions/result" method="POST">
                    <?php echo Csrf::field(); ?>
                    <input type="hidden" name="admission_id" value="${admissionId}">
                    <input type="hidden" name="committee_name" value="${type}">
                    <div style="max-height: 400px; overflow-y: auto;">
                        ${criteria.map((c, i) => `
                            <div class="form-group" style="border-bottom: 1px solid #eee; padding-bottom: 10px;">
                                <label>${i+1}. ${c}</label>
                                <div class="flex gap-10 flex-wrap">
                                    ${ratings.map(r => `<label style="font-weight:normal; font-size:12px;"><input type="radio" name="c_${i}" value="${r}" required> ${r}</label>`).join('')}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    <div class="grid-2 mt-20">
                        <div class="form-group"><label>Examiner</label><input type="text" name="examiner" value="<?php echo htmlspecialchars(Auth::user()['full_name']); ?>" readonly></div>
                        <div class="form-group"><label>Overall Score (0-100)</label><input type="number" name="score" required></div>
                    </div>
                    <div class="form-group">
                        <label>Result</label>
                        <select name="result" required>
                            <option value="accepted">Accepted</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="w-100" style="height:80px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Submit Evaluation</button>
                </form>
            `;
            document.getElementById('eval-title').textContent = type + ' Committee Evaluation';
            document.getElementById('eval-form-container').innerHTML = html;
            document.getElementById('modal-eval').classList.add('show');
        }
    </script>
</body>
</html>
