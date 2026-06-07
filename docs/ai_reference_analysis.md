# AI Technical Reference: HIMATIK DSS Codebase Analysis

This document serves as a complete technical guide and reference for AI agents or developers continuing the development of the **HIMATIK Decision Support System (DSS)**. It outlines the architecture, database architecture, Profile Matching SPK algorithm, database comparison, known bugs/discrepancies, and missing features.

---

## 1. System Architecture & Tech Stack

*   **Backend Framework**: Laravel 13 (PHP 8.2+) with Eloquent ORM.
*   **Web Client UI (Admin & Interviewer)**: Laravel Blade + Tailwind CSS 4 + Alpine.js (bundled via Vite).
*   **Mobile Client App (Candidate)**: Flutter client interacting with Laravel via REST API.
*   **Authentication & Security**:
    *   Web: Session-based authentication.
    *   API: Token-based authentication using **Laravel Sanctum**.
*   **Documentation Tools**:
    *   Scribe (mounted at `/docs` for API documentation).
    *   L5 Swagger (mounted at `/api/documentation`).

---

## 2. Database Architecture & Conventions

### Key Model & Schema Design
*   **One-to-One Account-to-Profile Relation**: 
    *   The `users` table holds authentication data AND primary student identity fields: `nim`, `nickname`, `prodi`, `kelas`, `phone`, and `address`.
    *   The `candidates` table holds application-specific data (essays, file upload paths) and a `user_id` foreign key.
*   **Department Choice Storage**: Department preferences are stored in the `candidate_departmentsbiro` pivot table, using `choice_order` (1 for 1st choice, 2 for 2nd choice).
*   **Interview Schedules**: The `interview_schedules` table stores slot sessions (`date`, `start_time`, `end_time`, `is_blocked`). The old `interviewer_schedule` table has been removed.
*   **Open Recruitment Quotas**: Quotas are decoupled from open recruitment periods and stored under `open_recruitment_quotas` keyed by `candidate_type` + `department_id`.

---

## 3. Profile Matching (DSS) Algorithm

The calculation logic resides in [ProfileMatchingService.php](file:///home/takofaru/Data/tugas/semester-4/project/himatik_dss/himatik_dss_web/web/app/Services/ProfileMatchingService.php):

1.  **Calculate Gap**: For each active criterion in the target department:
    $$\text{Gap} = \text{Actual Score} - \text{Target Score}$$
    *   *Actual Score*: Score given by interviewer (scale 1-5).
    *   *Target Score*: Target defined in `evaluation_criteria.target_score`.
2.  **Map Gap to Weight**: Gaps are mapped using the weights defined in the `spk_gap_weights` table:
    *   Gap $0 \rightarrow$ Weight $5.0$
    *   Gap $1 \rightarrow$ Weight $4.5$
    *   Gap $-1 \rightarrow$ Weight $4.0$
    *   Gap $2 \rightarrow$ Weight $3.5$
    *   Gap $-2 \rightarrow$ Weight $3.0$
    *   *etc.*
3.  **Group and Average Factors**: Criteria are grouped into **Core Factor (CF)** and **Secondary Factor (SF)** per aspect (Personal & Organizational). Averages are calculated for both.
4.  **Calculate Aspect Scores**:
    $$\text{Aspect Score} = (\text{CF Weight} \times \text{Average CF}) + (\text{SF Weight} \times \text{Average SF})$$
5.  **Calculate Total Score**: Combining personal and organizational aspects based on department weights:
    $$\text{Total Score} = (\text{Personal Weight} \times \text{Personal Aspect Score}) + (\text{Organizational Weight} \times \text{Organizational Aspect Score})$$

---

## 4. Known Bugs & Inconsistencies (Must Be Fixed)

### 1. REST API Candidate Profile Submission Failure
*   **Files**: `CandidateApiController@storeProfile` and `CandidateProfileService@createFor`
*   **Bug**: 
    1.  `CandidateProfileRules` validates NIM using `'unique:candidates,nim'`. Since `nim` resides on the `users` table, this uniqueness check is incorrect.
    2.  `CandidateProfileService` passes identity fields (`nickname`, `nim`, `prodi`, `kelas`, `phone`, `address`) to `Candidate::create()`. Since these fields do not exist on the `candidates` table and are not in `$fillable`, they are discarded. The `users` table is never updated with these fields during the API request, resulting in lost candidate identity data.
*   **Fix**: Update `CandidateProfileRules` to check `'unique:users,nim,' . $userId` and ensure `CandidateProfileService` performs `$user->update([...])` with the identity fields before creating the `Candidate` row.

### 2. REST API Schedule Mismatch
*   **Files**: `CandidateApiController@getAvailableSchedules` and `bookSchedule`
*   **Bug**: The API queries old, deprecated columns (`session_name`, `scheduled_at`, `location`, `is_active`) which were removed in the schedule refactoring migration. The database currently uses `date`, `start_time`, `end_time`, and `is_blocked`.
*   **Fix**: Update the query in the controller to use the new columns (`is_blocked` instead of `is_active`, ordering by `date` and `start_time` instead of `scheduled_at`).

### 3. Admin Pendaftaran Search SQL Error
*   **Files**: `AdminWebController@registrations`
*   **Bug**: The search query attempts to query `candidates.nim` or `candidates.prodi` directly, which throws an SQL exception.
*   **Fix**: Use `whereHas('user', ...)` to query NIM and Prodi from the `users` table.

### 4. Route Redirect Exception
*   **Files**: `CandidateWebController::redirectCandidateUser()`
*   **Bug**: The controller redirects to `interviewer.schedule`, which does not exist. The correct route name is `interviewer.schedules`.
*   **Fix**: Change the redirect path to `route('interviewer.schedules')`.

---

## 5. Missing Features (Future Roadmap)

1.  **Quota Enforcement**: The system has `open_recruitment_quotas` data but does not enforce it. Prevent admins from accepting a candidate if the target department's quota is already filled.
2.  **Real Top Candidates on Interviewer Dashboard**: The dashboard currently uses random mock data instead of reading from `SpkResult`.
3.  **Document Upload Security**: Document files are stored on the public disk. Highly sensitive files like signatures and statement letters should be moved to the private disk and fetched via an authenticated controller download action.
4.  **Asynchronous Profile Matching (Queue)**: SPK calculations currently run on-the-fly when loading ranking pages. Consider running them asynchronously on evaluations submit via a Laravel Queue Job.

---

## 6. Database Comparison: Migrations vs web.sql

*   **Schema Consistency**: The table schemas in `web.sql` match the Laravel migrations exactly. The refactored schedule table structure and the dropped `interviewer_schedule` table are correctly reflected in both.
*   **Data Differences**:
    *   **Seeders (`RecruitmentTestDataSeeder.php`)**: Seeds core master data, mock candidates, mock bookings, and raw evaluation scores. It **does not seed `spk_results` or calculation logs** (as these are calculated dynamically at runtime).
    *   **SQL Dump (`web.sql`)**: Contains actual state data, including active sessions, pre-computed `spk_results`, and calculation logs from previous testing sessions.
