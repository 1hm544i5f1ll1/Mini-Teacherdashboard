# City School Portal – KG Registration

PHP-based school portal with **registration-only MVP**: student applications (draft → submitted → approved/rejected → locked), document uploads, and registration summary (print/PDF). Separate **Registrar (Teacher)** and **Manager** roles.

---

## Tech Stack

- **Backend:** PHP 7.4+ (session auth, CSRF, PDO)
- **Database:** MySQL / MariaDB (utf8mb4)
- **Frontend:** HTML, CSS, minimal JS (no framework)
- **Server:** Apache or PHP built-in server

---

## Quick Start

1. **Clone and enter project**
   ```bash
   git clone <your-repo-url>
   cd Mini-Teacherdashboard
   ```

2. **Configure database**  
   Copy `.env.example` to `.env` (or set in `app/config/config.php`):
   - `DB_HOST`, `DB_PORT` (e.g. 3306 or 3307), `DB_USER`, `DB_PASS`, `DB_NAME` (e.g. `sohag_kg_system`).

3. **Create database and tables**
   ```bash
   # Create DB and import schema + seed
   mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS sohag_kg_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   mysql -u root -p sohag_kg_system < database/schema.sql
   mysql -u root -p sohag_kg_system < database/seed.sql
   # Registration MVP (draft → submitted → approved/locked)
   mysql -u root -p sohag_kg_system < database/migrations/001_registration_mvp.sql
   ```

4. **Run the app**
   ```bash
   cd public
   php -S localhost:8000
   ```
   Open **http://localhost:8000** → login → you land on the registration list.

5. **Create uploads folder** (if you use document upload)
   ```bash
   mkdir -p storage/uploads
   ```

For more options (XAMPP, Apache, ports), see [SETUP.md](SETUP.md).

---

## Login & Roles

| Role   | Username  | Password   | Access |
|--------|-----------|------------|--------|
| Manager | `manager` | (see seed) | View all, approve/reject, lock, print summary |
| Registrar | `ahmed` or teacher user | (see seed) | Create/edit draft, submit, upload documents |

After login you are redirected to **/registration**. Default seed users: see `database/seed.sql` (e.g. manager / teacher with hashed passwords). Use `scripts/create_manager.php` or `scripts/reset_passwords.php` if needed.

---

## Registration MVP (What Works)

### Roles

- **Manager:** View all applications, approve or reject (with note), lock record (no further edits), open registration summary and print/save as PDF.
- **Registrar (Teacher):** Create student + guardian, edit draft, upload documents (birth cert, photo, committee scans), submit application.

### Student Registration

- **Create:** Full name (AR), gender, religion, DOB, applied grade (PRE_KG / KG1), guardian name + phone(s) + optional address.
- **Age on 1 October** is calculated and shown on form and summary.
- **Status flow:** **Draft → Submitted → Approved or Rejected → Locked** (locked = immutable except manager view/print).

### Documents

- Upload: birth certificate, photo, committee scans (PDF, JPG, PNG; max 5 MB; type allowlist).
- Download: safe attachment; access checked per registration.

### Search & List

- Filters: status (draft/submitted/approved/rejected), grade, date range.
- Search: by name, phone, or ID.

### Audit

- Created by/at, updated by, submitted at, approved by/at, locked by/at; plus `audit_logs` for actions.

---

## Project Structure

```
Mini-Teacherdashboard/
├── app/
│   ├── config/          # DB, app URL, session, paths
│   ├── core/            # Auth, Router, DB, View, Csrf, Logger, Validator, helpers
│   ├── middleware/      # RequireLogin, RequireRole
│   ├── modules/
│   │   ├── registration/  # RegistrationRepo, RegistrationController
│   │   ├── documents/    # DocumentsController (upload/download)
│   │   ├── admissions/   # Legacy admissions
│   │   ├── auth/         # Login / logout
│   │   └── ...
│   ├── routes/          # web.php
│   └── views/           # auth/, manager/, teacher/, registration/
├── database/
│   ├── schema.sql       # Full schema
│   ├── seed.sql         # Users, sample data
│   └── migrations/
│       └── 001_registration_mvp.sql   # Registration status + audit columns
├── public/              # Document root
│   ├── index.php       # Entry point
│   └── assets/         # CSS, JS, images
├── storage/             # logs/, uploads/
├── scripts/             # install, backup, create_manager, reset_passwords
├── SETUP.md             # Detailed setup
└── README.md            # This file
```

---

## Main Routes

| Route | Method | Description |
|-------|--------|-------------|
| `/` | GET | Redirect to login |
| `/auth/login` | GET/POST | Login (redirect to `/registration`) |
| `/auth/logout` | GET | Logout |
| `/registration` | GET | List registrations (filters, search) |
| `/registration/create` | GET | New registration form |
| `/registration/store` | POST | Create draft |
| `/registration/edit?id=` | GET | Edit form (draft only) |
| `/registration/update` | POST | Update draft |
| `/registration/submit` | POST | Submit application |
| `/registration/approve` | POST | Manager: approve |
| `/registration/reject` | POST | Manager: reject |
| `/registration/lock` | POST | Manager: lock record |
| `/registration/summary?id=` | GET | Registration summary (print/PDF) |
| `/documents/upload` | POST | Upload file for a registration |
| `/documents/download?id=` | GET | Download document |
| `/teacher`, `/manager` | GET | Dashboards (optional) |

---

## Status

- **Registration MVP:** ✅ End-to-end (create → submit → approve/reject → lock, documents, summary/print).
- **Auth:** ✅ Session login, CSRF on POST, role-based access in controllers.
- **Legacy / optional:** Teacher and Manager dashboards, admissions (old flow); some pages are placeholders.

---

## License & Contributing

Use and modify as needed. For bugs or features, open an issue or PR on GitHub.
