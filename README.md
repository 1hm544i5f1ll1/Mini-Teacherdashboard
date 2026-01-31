# City School Portal - Multi-Page System

## Overview
Complete school management system with separate **Teacher** and **Manager** interfaces.

## Login Credentials

### Access the System
1. Open `login.html` in your browser
2. Choose user type (Teacher or Manager)
3. Enter credentials:

**Teacher Login:**
- Username: `teacher`
- Password: `teacher`
- Access: Teacher Portal (7 tabs)

**Manager Login:**
- Username: `admin`
- Password: `admin`
- Access: Manager Portal (8 tabs)

---

## Teacher Interface (7 Simple Tabs)

### 🟦 Tab 1: Home
**File:** `teacher/index.html`
- Today's class schedule
- Quick stats (classes, students, tasks, alerts)
- Today's alerts (absent students, early dismissals, messages)
- Today's tasks checklist
- Quick action buttons

### 🟦 Tab 2: Classes
**File:** `teacher/classes.html` (to be created)
- My classes list
- Student list per class
- Class timetable (read-only)
- Break times (read-only)

### 🟦 Tab 3: Attendance
**File:** `teacher/attendance.html` (to be created)
- Take student attendance (P/A/L)
- Edit same day only
- View attendance history (read-only)

### 🟦 Tab 4: Students
**File:** `teacher/students.html` (to be created)
- Student profiles (view only)
- Parent contacts (view only)
- Student IDs with QR/barcode (view only)
- Entry/exit log (view only)

### 🟦 Tab 5: Messages
**File:** `teacher/messages.html` ✅ COMPLETED
- Send WhatsApp to individual student
- Send WhatsApp to entire class
- View message history
- Professional messaging interface

### 🟦 Tab 6: Tasks & Polls
**File:** `teacher/tasks.html` (to be created)
- View daily tasks
- Mark tasks as done
- Add notes to tasks
- View polls
- Vote in polls

### 🟦 Tab 7: Requests
**File:** `teacher/requests.html` (to be created)
- Request holiday/leave
- Request permission slip
- Request early dismissal
- View request status (pending/approved/rejected)

---

## Manager Interface (8 Comprehensive Tabs)

### 🟥 Tab 1: Dashboard
**File:** `manager/index.html` ✅ COMPLETED
- School overview stats
- Priority alerts
- Today's attendance summary (students + staff)
- Pending approvals count
- Recent activity log
- Classes overview table
- Quick actions

### 🟥 Tab 2: Setup
**File:** `manager/setup.html` (to be created)
**Features:**
- Manage classes (add/edit/delete)
- Set timetables
- Configure breaks
- Set school holidays
- Academic calendar

### 🟥 Tab 3: People
**File:** `manager/people.html` (to be created)
**Features:**
- **Students:**
  - Add/edit/delete students
  - Student ID, DOB, address
  - Phone, WhatsApp, parent info
  - Assign to classes
  - Generate student IDs (QR/barcode)
  
- **Teachers & Staff:**
  - Add/edit/delete staff
  - Assign classes to teachers
  - Set permissions
  - View staff profiles

### 🟥 Tab 4: Attendance
**File:** `manager/attendance.html` (to be created)
**Features:**
- View all student attendance
- View all staff attendance
- Attendance reports
- Late arrivals tracking
- Monthly summaries

### 🟥 Tab 5: Safety
**File:** `manager/safety.html` (to be created)
**Features:**
- **ID Scans:**
  - View all gate entries
  - Real-time entry log
  - Student ID scanning
  
- **Early Dismissal:**
  - Approve/reject requests
  - Track who picked up student
  - Log time and reason
  - View pickup history
  
- **Pickup Logs:**
  - Complete audit trail
  - Filter by date/student
  - Export logs

### 🟥 Tab 6: Communication
**File:** `manager/communication.html` (to be created)
**Features:**
- Create WhatsApp templates
- Send broadcast messages
- Message to specific classes
- Message to all parents
- View message analytics

### 🟥 Tab 7: Tasks & Polls
**File:** `manager/tasks-polls.html` (to be created)
**Features:**
- **Tasks:**
  - Create daily tasks for teachers
  - Assign to specific teachers/all
  - View completion status
  - Add deadlines
  
- **Polls:**
  - Create polls
  - Assign to teachers/students
  - View poll results
  - Export poll data

### 🟥 Tab 8: Reports
**File:** `manager/reports.html` (to be created)
**Features:**
- Attendance reports
- Student performance
- Staff activity logs
- Gate entry/exit logs
- Message history
- Export to Excel/PDF

---

## Technical Structure

### File Organization
```
teacher-portal/
├── login.html          ← Main login page
├── style.css           ← CSS for login page
├── teacher/            ← Teacher interface folder
│   ├── style.css       ← CSS for teacher pages
│   ├── index.html      ✅ Home page
│   ├── classes.html    🔄 To be created
│   ├── attendance.html 🔄 To be created
│   ├── students.html   🔄 To be created
│   ├── messages.html   ✅ Messages page
│   ├── tasks.html      🔄 To be created
│   └── requests.html   🔄 To be created
└── manager/            ← Manager interface folder
    ├── style.css       ← CSS for manager pages
    ├── index.html      ✅ Dashboard
    ├── setup.html      🔄 To be created
    ├── people.html     🔄 To be created
    ├── attendance.html 🔄 To be created
    ├── safety.html     🔄 To be created
    ├── communication.html 🔄 To be created
    ├── tasks-polls.html   🔄 To be created
    └── reports.html    🔄 To be created
```

### CSS Implementation
- **CSS file in each folder** (`style.css`)
- Teacher pages use: `<link rel="stylesheet" href="style.css">`
- Manager pages use: `<link rel="stylesheet" href="style.css">`
- Login page uses: `<link rel="stylesheet" href="style.css">`
- CSS is properly linked and working ✅
- Responsive design included
- Mobile-friendly navigation

### Navigation Structure
- **Teacher:** Horizontal tab navigation (7 tabs)
- **Manager:** Horizontal tab navigation (8 tabs)
- Active tab highlighting
- Consistent header across all pages
- Logout button on every page

---

## Features Summary

### ✅ Completed Features
1. Login system with role selection
2. Shared CSS with proper linking
3. Teacher Home/Dashboard
4. Teacher Messages (WhatsApp)
5. Manager Dashboard with overview

### 🔄 Features to Implement
1. Teacher: Classes, Attendance, Students, Tasks, Requests pages
2. Manager: Setup, People, Attendance, Safety, Communication, Tasks/Polls, Reports pages
3. CRUD operations for all entities
4. Database integration (currently using JavaScript)

---

## How to Use

### For Teachers:
1. Login with teacher credentials
2. View today's schedule on Home
3. Take attendance as needed
4. Send WhatsApp messages to students/parents
5. Complete daily tasks
6. Submit leave requests

### For Managers:
1. Login with admin credentials
2. Monitor school overview on Dashboard
3. Approve/reject early dismissals
4. Manage students and staff
5. Create classes and timetables
6. Send broadcast messages
7. Generate reports

---

## Next Steps
Would you like me to:
1. Complete all remaining Teacher pages?
2. Complete all remaining Manager pages?
3. Add specific features (QR scanning, PDF reports, etc.)?
4. Add data persistence (localStorage or backend)?

---

## Status
- ✅ Login system ready
- ✅ CSS working properly
- ✅ Multi-page structure set up
- ✅ Teacher and Manager portals separated
- ✅ 3 pages completed (Login, Teacher Home, Teacher Messages, Manager Dashboard)
- 🔄 12 pages remaining to implement full functionality
