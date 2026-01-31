/**
 * City School Portal - Core Logic & Storage
 * Persistence: localStorage ("cityschool")
 * Schema version: 2.0 (Relational-style)
 */

const STORAGE_KEY = 'cityschool';

const DEFAULT_DATA = {
    // --- CORE ---
    roles: [
        { id: 1, name: 'Manager', description: 'School Oversight' },
        { id: 2, name: 'Teacher', description: 'Academic Staff' },
        { id: 3, name: 'Admin', description: 'System Administrator' }
    ],
    users: [
        { id: 1, username: 'admin', password: 'admin', role_id: 1, employee_id: 101, name: 'Admin Manager', is_active: true },
        { id: 2, username: 'teacher', password: 'teacher', role_id: 2, employee_id: 102, name: 'Ahmed Mohamed', is_active: true }
    ],
    academic_years: [
        { id: 1, name: '2025-2026', is_active: true }
    ],

    // --- STUDENTS & GUARDIANS ---
    students: [
        { id: 1, student_code: 'S001', full_name: 'Ahmed Mahmoud', gender: 'Male', role: 'Student', religion: 'Muslim', dob: '2020-05-15', age: '5y 8m', national_id: '31501010123456', status: 'Active', class_id: 1 },
        { id: 2, student_code: 'S002', full_name: 'Fatma Ali', gender: 'Female', role: 'Student', religion: 'Muslim', dob: '2020-08-20', age: '5y 5m', national_id: '31502020123457', status: 'Active', class_id: 1 },
        { id: 3, student_code: 'S003', full_name: 'Youssef Hassan', gender: 'Male', role: 'Student', religion: 'Muslim', dob: '2020-03-10', age: '5y 10m', national_id: '31503030123458', status: 'Active', class_id: 1 },
        { id: 4, student_code: 'S004', full_name: 'Mariam Khaled', gender: 'Female', role: 'Student', religion: 'Muslim', dob: '2020-11-05', age: '5y 2m', national_id: '31504040123459', status: 'Active', class_id: 1 },
        { id: 5, student_code: 'S005', full_name: 'Omar Ibrahim', gender: 'Male', role: 'Student', religion: 'Muslim', dob: '2020-12-25', age: '5y 1m', national_id: '31505050123460', status: 'Active', class_id: 1 }
    ],
    guardians: [
        { id: 1, full_name: 'Mahmoud Ali', phone: '+20 123 456 7890', relationship: 'Father' }
    ],

    // --- ACADEMICS ---
    classes: [
        { id: 1, year_id: 1, grade: 'KG1', name: 'A', section: 'Primary', room: '101', teacher_id: 2 }
    ],
    attendance_records: [
        { id: 1, student_id: 1, date: '2026-01-21', status: 'Present', note: 'Early' },
        { id: 2, student_id: 2, date: '2026-01-21', status: 'Absent', note: 'Sick' }
    ],
    exit_logs: [
        { id: 1, name: 'Ahmed Mahmoud', picked_by: 'Mahmoud Ali', reason: 'Doctor Appointment', date: '2026-01-22 10:30 AM', status: 'Completed' }
    ],
    discipline_logs: [
        { id: 1, date: '2026-01-20', student_name: 'Ahmed Mahmoud', type: 'Late', severity: 'Low', action: 'Warning' }
    ],
    exams: [
        { id: 1, name: 'Mid-term Quiz', subject: 'Mathematics', class_id: 1, max_mark: 20, date: '2026-01-22' }
    ],
    exam_results: [],

    // --- ADMISSIONS (Images 1-5) ---
    admission_tests: [
        { id: 1, student_id: 1, year_id: 1, applied_grade: 'KG1', test_date: '2026-02-01', overall_status: 'Pending' },
        { id: 2, student_id: 2, year_id: 1, applied_grade: 'KG1', test_date: '2026-02-02', overall_status: 'Approved' }
    ],
    committees: [
        'Medical', 'Educational', 'Behavioral', 'Social Worker', 'Speech Therapist', 'Parent', 'Computer', 'Activities', 'PE', 'Music'
    ],
    committee_results: {}, // { admission_test_id: { committee_name: { score, result, examiner, notes } } }
    
    // --- HR & EMPLOYEE SERVICES (Images 7-9) ---
    employees: [
        { id: 101, code: 'E001', name: 'Admin Manager', job: 'Principal', dept: 'Management' },
        { id: 102, code: 'E002', name: 'Ahmed Mohamed', job: 'Math Teacher', dept: 'Academics' }
    ],
    exit_permissions: [
        { id: 1, employee_id: 102, start: '10:00 AM', end: '11:30 AM', reason: 'Doctor Visit', status: 'Accepted' }
    ],
    leave_requests: [],

    // --- TASKS & POLLS ---
    tasks: [
        { id: 1, title: 'Submit Grade 3A Marks', due: '2026-01-25', status: 'Pending', assigned_to: 2 }
    ],
    polls: [
        { id: 1, title: 'Summer Trip Location?', options: ['Alexandria', 'Luxor'], votes: {} }
    ]
};

const storage = {
    get: () => {
        let data = localStorage.getItem(STORAGE_KEY);
        if (!data) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(DEFAULT_DATA));
            return DEFAULT_DATA;
        }
        try {
            const parsed = JSON.parse(data);
            // Ensure essential keys exist, if any are missing or empty, reset to default to ensure demo works
            const essentialKeys = ['students', 'classes', 'users', 'roles'];
            const isMissingData = essentialKeys.some(key => !parsed[key] || parsed[key].length === 0);
            
            if (isMissingData) {
                console.warn("Data missing in storage, restoring defaults");
                localStorage.setItem(STORAGE_KEY, JSON.stringify(DEFAULT_DATA));
                return DEFAULT_DATA; 
            }
            return parsed;
        } catch (e) {
            return DEFAULT_DATA;
        }
    },
    save: (data) => localStorage.setItem(STORAGE_KEY, JSON.stringify(data)),
    update: (key, val) => {
        const data = storage.get();
        data[key] = val;
        storage.save(data);
    }
};

const ui = {
    statusBadge: (status) => {
        const map = { 
            'Accepted': 'success', 'Approved': 'success', 'Active': 'success',
            'Pending': 'warning', 'Late': 'warning',
            'Rejected': 'danger', 'Absent': 'danger', 'Inactive': 'danger'
        };
        return `<span class="badge badge-${map[status] || 'info'}">${status}</span>`;
    },
    createTable: (cols, rows, actionFn) => `
        <div class="table-container">
            <table>
                <thead><tr>${cols.map(c => `<th>${c}</th>`).join('')}${actionFn ? '<th>Action</th>' : ''}</tr></thead>
                <tbody>${rows.map(r => `<tr>${r.map(cell => `<td>${cell}</td>`).join('')}${actionFn ? `<td>${actionFn(r)}</td>` : ''}</tr>`).join('')}</tbody>
            </table>
        </div>
    `,
    showToast: (msg, type = 'success') => {
        const t = document.createElement('div');
        t.className = `alert alert-${type}`;
        Object.assign(t.style, { position: 'fixed', top: '20px', right: '20px', zIndex: 10000 });
        t.innerHTML = msg;
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 3000);
    }
};
