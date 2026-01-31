<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Teacher Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <div class="school-logo">🏫</div>
            <div class="school-name">City School - Teacher Portal</div>
        </div>
        <div class="header-right">
            <div class="user-menu">
                <div class="user-avatar">AM</div>
                <span>Ahmed Mohamed</span>
            </div>
            <a href="login.html" class="logout-btn">Logout</a>
        </div>
    </header>

    <nav class="nav-tabs">
        <a href="index.html" class="nav-tab">Teacher</a>
        <a href="classes.html" class="nav-tab">Class</a>
        <a href="requests.html" class="nav-tab">HR</a>
    </nav>

    <main class="content">
        <h1 class="page-title">WhatsApp Messages</h1>

        <div class="quick-actions">
            <button class="quick-btn" onclick="openMessageModal('student')">
                💬 Message Student
            </button>
            <button class="quick-btn" onclick="openMessageModal('class')">
                👥 Message Class
            </button>
        </div>

        <!-- Message History -->
        <div class="card">
            <h2 class="card-title">Message History</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Recipient</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Jan 22, 2026 - 09:15 AM</td>
                            <td>Ahmed Mahmoud's Parent<br><small>+20 123 456 7890</small></td>
                            <td>Your son Ahmed is absent today. Please confirm if he is sick.</td>
                            <td><span class="badge badge-success">✓ Delivered</span></td>
                            <td><button class="btn btn-primary btn-small">View</button></td>
                        </tr>
                        <tr>
                            <td>Jan 22, 2026 - 08:30 AM</td>
                            <td>Class 3A (25 students)</td>
                            <td>Reminder: Algebra homework is due tomorrow. Please complete pages 45-50.</td>
                            <td><span class="badge badge-success">✓ Sent to 25</span></td>
                            <td><button class="btn btn-primary btn-small">View</button></td>
                        </tr>
                        <tr>
                            <td>Jan 21, 2026 - 02:45 PM</td>
                            <td>Sara Ali's Parent<br><small>+20 123 456 7891</small></td>
                            <td>Sara performed excellently on the math quiz today! Well done!</td>
                            <td><span class="badge badge-success">✓ Delivered</span></td>
                            <td><button class="btn btn-primary btn-small">View</button></td>
                        </tr>
                        <tr>
                            <td>Jan 21, 2026 - 11:20 AM</td>
                            <td>Class 3B (23 students)</td>
                            <td>Next week's exam schedule has been posted. Check the school portal for details.</td>
                            <td><span class="badge badge-success">✓ Sent to 23</span></td>
                            <td><button class="btn btn-primary btn-small">View</button></td>
                        </tr>
                        <tr>
                            <td>Jan 20, 2026 - 03:00 PM</td>
                            <td>Mohamed Hassan's Parent<br><small>+20 123 456 7892</small></td>
                            <td>Mohamed needs to improve his behavior in class. Please discuss with him at home.</td>
                            <td><span class="badge badge-success">✓ Delivered</span></td>
                            <td><button class="btn btn-primary btn-small">View</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Send Message Modal -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Send WhatsApp Message</h2>
                <button class="close-btn" onclick="closeMessageModal()">&times;</button>
            </div>
            <form id="messageForm" onsubmit="sendMessage(event)">
                <div class="form-group" id="studentSelectGroup" style="display: none;">
                    <label>Select Student</label>
                    <select id="studentSelect">
                        <option value="">Choose a student...</option>
                        <option value="1">Ahmed Mahmoud - 3A (+20 123 456 7890)</option>
                        <option value="2">Fatma Ali - 3A (+20 123 456 7891)</option>
                        <option value="3">Mohamed Hassan - 3B (+20 123 456 7892)</option>
                        <option value="4">Sara Ahmed - 3B (+20 123 456 7893)</option>
                    </select>
                </div>

                <div class="form-group" id="classSelectGroup" style="display: none;">
                    <label>Select Class</label>
                    <select id="classSelect">
                        <option value="">Choose a class...</option>
                        <option value="3A">Class 3A (25 students)</option>
                        <option value="3B">Class 3B (23 students)</option>
                        <option value="3C">Class 3C (27 students)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Message</label>
                    <textarea id="messageText" rows="6" placeholder="Type your message here..." required></textarea>
                </div>

                <div class="alert alert-info">
                    <strong>💡 Tip:</strong> Messages are sent via WhatsApp to parent phone numbers. Keep messages professional and clear.
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" onclick="closeMessageModal()">Cancel</button>
                    <button type="submit" class="btn btn-success">Send via WhatsApp</button>
                </div>
            </form>
        </div>
    </div>

    <script src="core.js"></script>
    <script>
        function openMessageModal(type) {
            const modal = document.getElementById('messageModal');
            const studentGroup = document.getElementById('studentSelectGroup');
            const classGroup = document.getElementById('classSelectGroup');
            const title = document.getElementById('modalTitle');

            if (type === 'student') {
                title.textContent = 'Send Message to Student';
                studentGroup.style.display = 'block';
                classGroup.style.display = 'none';
            } else {
                title.textContent = 'Send Message to Class';
                studentGroup.style.display = 'none';
                classGroup.style.display = 'block';
            }

            modal.classList.add('show');
        }

        function closeMessageModal() {
            document.getElementById('messageModal').classList.remove('show');
            document.getElementById('messageForm').reset();
        }

        function sendMessage(event) {
            event.preventDefault();
            alert('Message sent via WhatsApp successfully!');
            closeMessageModal();
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('show');
            }
        }
    </script>
</body>
</html>
