# KG/Admission Registration Portal — Production Readiness Playbook

Runnable reference for the Production Readiness Test Lead. Use with the Cursor rule `.cursor/rules/production-readiness-test-lead.mdc`.

---

## Minimum 15 Test Cases (Full Format)

### TC-AUTH-01 — Auth brute-force / rate limit (login)
- **Type:** integration
- **Risk:** Auth/session abuse
- **Preconditions:** Login endpoint reachable
- **Steps:** Send N failed login attempts (e.g. 20) in quick succession from same IP
- **Expected:** After threshold (e.g. 5–10), 429 or lockout; no account enumeration
- **Failure signals:** Unlimited attempts; different error for "user not found" vs "wrong password"
- **Quick fix hints:** Add rate limiter (e.g. Upstash, Vercel KV) on POST login; return generic "Invalid credentials"
- **Automation suggestion:** Artillery/k6 script or Playwright loop calling login API

### TC-CSRV-02 — CSRF on state-changing actions
- **Type:** integration / e2e
- **Risk:** Cross-site request forgery
- **Preconditions:** CSRF token or SameSite cookies expected
- **Steps:** From external site, POST to create/edit/submit/lock/sign without valid token/cookie
- **Expected:** 403 or 401; no state change
- **Failure signals:** Action succeeds; no token validation in server action/API
- **Quick fix hints:** Enforce CSRF token in all mutations; SameSite=Strict for session cookie
- **Automation suggestion:** cURL from script with no Referer/cookie; expect 403

### TC-XSS-03 — XSS on free-text + safe PDF
- **Type:** integration / manual
- **Risk:** PII leakage, script injection
- **Preconditions:** Applicant form with deputy opinion, names, notes
- **Steps:** Submit `<script>alert(1)</script>`, `"><img src=x onerror=alert(1)>` in free-text; generate PDF
- **Expected:** Input escaped in UI; PDF shows escaped text only; no script execution
- **Failure signals:** Script runs in browser; raw HTML in PDF
- **Quick fix hints:** Sanitize/escape on render; use PDF library that escapes; CSP headers
- **Automation suggestion:** Playwright submit payloads; snapshot PDF text content

### TC-AUTHZ-04 — Officer cannot lock/finalize/override
- **Type:** integration
- **Risk:** Role authorization
- **Preconditions:** User with Officer role only
- **Steps:** Call lock/finalize/override decision endpoints (or server actions) as Officer
- **Expected:** 403; no DB change
- **Failure signals:** 200 and record locked; UI hides button but API allows
- **Quick fix hints:** Enforce role in server action/API: only Manager/Stage Deputy can lock
- **Automation suggestion:** API test with Officer token for lock/finalize routes

### TC-AUTHZ-05 — Manager can review/finalize/lock; locked = immutable
- **Type:** integration
- **Risk:** Lock integrity
- **Preconditions:** Applicant in submitted state; Manager role
- **Steps:** Manager finalize → lock; then attempt edit of committees/attachments/signatures (any role)
- **Expected:** Lock succeeds; subsequent edit/delete of locked record returns 403 or validation error
- **Failure signals:** Locked record still editable; committee/attachments/signatures changeable after lock
- **Quick fix hints:** Check `locked_at` (or status) in all update/delete paths; reject if locked
- **Automation suggestion:** Lock via API then PATCH/PUT same resource; expect 403/422

### TC-IDOR-06 — Applicant view/edit/export authorized only
- **Type:** integration / e2e
- **Risk:** IDOR on applicant/PDF/attachments
- **Preconditions:** Two users (A, B); each has access to different applicants
- **Steps:** As A, request view/edit/export for B’s applicant ID (guess or enumerate ID)
- **Expected:** 403 or 404; no data returned
- **Failure signals:** 200 and B’s data returned
- **Quick fix hints:** Every applicant fetch must filter by allowed list (e.g. stage/committee/role)
- **Automation suggestion:** Create two applicants; as User A call GET/POST with User B’s applicant id; expect 403/404

### TC-FILE-07 — Attachments: allowlist, size, virus hook, safe download
- **Type:** integration / manual
- **Risk:** File upload malware/oversize; PII leakage
- **Preconditions:** Upload endpoint and download URL
- **Steps:** Upload oversize file; wrong MIME/extension; .exe or script; download attachment with different user
- **Expected:** Reject oversize/wrong type; quarantine or block executable; download only with auth and same authz as applicant
- **Failure signals:** Oversize accepted; executable stored and served; download without auth or for other’s applicant
- **Quick fix hints:** Allowlist (e.g. PDF, images); max size in config; Content-Disposition; virus scan hook or quarantine; authz on download
- **Automation suggestion:** cURL upload oversize/wrong type; cURL download with wrong user

### TC-AUDIT-08 — Audit log: who/what/when for key events
- **Type:** integration
- **Risk:** Audit log tampering
- **Preconditions:** Audit log store (DB or append-only)
- **Steps:** Perform create, edit, submit, committee entry, decision, sign, lock, export; read audit log
- **Expected:** Each action has immutable entry: user id, action, resource id, timestamp (and optionally IP)
- **Failure signals:** Missing events; editable log; no user id or timestamp
- **Quick fix hints:** Single write path for audit; append-only; include action + resource + user + time
- **Automation suggestion:** Trigger actions via API; query audit table and assert entries exist

### TC-DATA-09 — Age-on-1-October accuracy
- **Type:** unit / integration
- **Risk:** Wrong eligibility
- **Preconditions:** DOB input; age calculated as of 1 Oct
- **Steps:** DOB = 1 Oct 2020; 30 Sep 2020; 2 Oct 2020; leap year (e.g. 29 Feb 2020); missing DOB
- **Expected:** Correct age for 1 Oct cutoff; leap year correct; missing DOB → validation error or explicit handling
- **Failure signals:** Off-by-one; wrong leap year; timezone shifts age
- **Quick fix hints:** Use date library; fix reference date (1 Oct); store/compare in UTC or explicit timezone
- **Automation suggestion:** Unit tests for age function with above DOBs

### TC-DATA-10 — Committee template: 12 committees, stable numbering, persist/print
- **Type:** integration / manual
- **Risk:** PDF correctness; data integrity
- **Preconditions:** Committee template defined; applicant with committee results
- **Steps:** Save committee results for all 12; lock; export PDF; re-open record
- **Expected:** All 12 present; question numbers stable; results persist in DB and match PDF
- **Failure signals:** Missing committee; renumbered questions; results lost or wrong in PDF
- **Quick fix hints:** Template versioned; question IDs stable; PDF generation reads from same source as UI
- **Automation suggestion:** Seed applicant; fill committees via API; export PDF; parse PDF and compare to DB

### TC-PDF-11 — PDF export: form layout, approvals/signatures, authorized applicant only
- **Type:** integration / e2e
- **Risk:** PDF correctness; IDOR
- **Preconditions:** Official form layout spec; applicant with approvals/signatures
- **Steps:** Export PDF as Manager for applicant A; export as Officer for applicant B (not in Officer’s list)
- **Expected:** PDF matches layout; includes approvals/signatures; Officer gets 403 for B
- **Failure signals:** Wrong layout; missing signatures; Officer receives B’s PDF
- **Quick fix hints:** Template aligned to spec; authz check on export same as view; embed only signed data
- **Automation suggestion:** Export PDF; diff key sections to golden layout; authz test for B

### TC-SEARCH-12 — Search/list/filter returns only authorized records
- **Type:** integration
- **Risk:** PII leakage via search
- **Preconditions:** User with limited scope (e.g. one stage)
- **Steps:** Search/list/filter with broad criteria
- **Expected:** Only applicants user is allowed to see
- **Failure signals:** Results include other stages/schools/applicants
- **Quick fix hints:** All list/search queries filter by role/stage/school; no “return all” for restricted roles
- **Automation suggestion:** Seed data for two scopes; query as restricted user; assert result set subset

### TC-CONC-13 — Concurrency: two sessions editing same applicant pre-lock
- **Type:** integration / manual
- **Risk:** Data loss; inconsistent state
- **Preconditions:** Applicant in draft/submitted; two sessions (same or different users) editing
- **Steps:** Session A and B edit same applicant; both submit (or save)
- **Expected:** Deterministic merge or last-write with warning; no silent overwrite of critical fields
- **Failure signals:** Lost updates; corrupt record; no warning
- **Quick fix hints:** Optimistic lock (version/timestamp); or last-write-wins with "edited by X at Y" warning
- **Automation suggestion:** Two parallel requests updating same applicant; assert final state and no 500

### TC-OPS-14 — Migrations: one-off admin; rollback verified
- **Type:** manual
- **Risk:** Migrations/deploy config
- **Preconditions:** Migration scripts; dev/stage DB
- **Steps:** Run migrations as one-off; verify schema; run rollback if available; re-run
- **Expected:** Migrations run once; rollback leaves DB consistent; re-run idempotent or no-op
- **Failure signals:** Migrations in app startup; rollback fails or leaves broken state
- **Quick fix hints:** Migrations as separate step (e.g. `npm run db:migrate` in CI/deploy); document rollback
- **Automation suggestion:** CI job: migrate → smoke query → rollback → migrate

### TC-OBS-15 — Logs: stdout, structured, no PII, errors sanitized
- **Type:** manual / integration
- **Risk:** PII leakage in logs
- **Preconditions:** App running; trigger error and normal flow
- **Steps:** Inspect stdout/log output for login, applicant create, error stack
- **Expected:** Structured (e.g. JSON); no names/DOB/IDs in plain text; stack traces without PII
- **Failure signals:** Logs contain names, DOB, applicant ID; raw stack in production
- **Quick fix hints:** Structured logger; redact PII in log layer; error serialization strip sensitive fields
- **Automation suggestion:** Run request that triggers error; grep log for email/name/DOB; expect none

---

## Phase 3 — Execution Checklist (What to Run)

### Local
- `npm ci`
- `cp .env.example .env.local` and fill
- `npm run db:migrate` (or equivalent)
- `npm run dev` → smoke: login, create applicant, submit, export PDF

### CI
- `npm run lint`
- `npm run test` (unit + integration)
- `npm run build`
- Optional: `npm run db:migrate` in CI DB; run smoke API tests

### Playwright smoke path
1. Login (Officer)
2. Create applicant (draft)
3. Fill interview section
4. Fill 1 committee
5. Submit
6. Login (Manager) → review → finalize → lock
7. Export PDF → check layout and signatures

### Seed/reset
- Minimal seed: one Officer, one Manager, one applicant (draft), one applicant (submitted).
- Reset: script that truncates application tables and reseeds; no production data.

### Golden path (≤5 min)
- Login → Create applicant → Submit → Manager lock → Export PDF (manual click-through).

### Disaster check
- Restore DB from backup (dev/stage) → run app → export PDF for one applicant → verify file and content.

---

## Phase 4 — Ship Gate Template

- **Ship status:** SHIP | SHIP WITH RISK | NO-SHIP
- **Blockers:** [List with exact fix steps]
- **Non-blockers:** [List]
- **24-hour hardening:** [Top 3–5 items]
- **7-day hardening:** [Privacy, backups, monitoring, polish]

---

## 12-Factor Checklist (Phase 1)

| # | Factor | Status | Evidence | Fix |
|---|--------|--------|----------|-----|
| 1 | Codebase | | One repo; many deploys | |
| 2 | Dependencies | | package.json / lockfile | |
| 3 | Config | | Env vars; no secrets in code | |
| 4 | Backing services | | DB/Redis as attached resources | |
| 5 | Build, release, run | | Build → release → run stages | |
| 6 | Processes | | Stateless app processes | |
| 7 | Port binding | | Export via port | |
| 8 | Concurrency | | Scale out processes | |
| 9 | Disposability | | Fast startup; graceful shutdown | |
| 10 | Dev/prod parity | | Same stack/config discipline | |
| 11 | Logs | | Stdout; event stream | |
| 12 | Admin processes | | One-off migrate/seed | |

Fill Status (PASS/PARTIAL/FAIL), Evidence (paths/commands), and Fix (1–5 bullets) per factor when running Phase 1.
