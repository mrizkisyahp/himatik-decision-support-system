# HIMATIK Decision Support System (DSS)

## Project Overview

**HIMATIK Decision Support System (DSS)** is a comprehensive, web-based recruitment and selection platform designed specifically for HIMATIK (Himpunan Mahasiswa Teknik Informatika) organizational member recruitment. 

This system streamlines the entire open recruitment process, from candidate registration and document submission to interviewer assessments and final decision-making, utilizing a Profile Matching Decision Support System to ensure fair, objective, and data-driven selections.

### Objectives
- **Automate Recruitment Workflow:** Transition from manual paperwork to a centralized digital platform.
- **Objective Selection:** Implement a Profile Matching algorithm to objectively evaluate candidates based on predetermined criteria weights.
- **Role-Based Efficiency:** Provide dedicated workspaces for Candidates, Interviewers, and Administrators to manage their specific tasks effectively.
- **Transparent Processes:** Ensure clear announcements and status tracking for all applicants.

### Key Features
- Multi-role architecture (Candidate, Interviewer, Admin)
- Automated Profile Matching DSS calculations
- Secure document upload and verification
- Comprehensive API documentation (Scribe)
- Real-time status updates and email notifications

---

## Main Features

### 🎓 Candidate Management
- **Registration & Profile Completion:** Candidates can register, fill out academic details, and select their department preferences (Biro).
- **Document Upload:** Conditional document requirements based on applicant type (e.g., Staff vs. BPH). Supports PDF forms, photos, and proof of social media engagement.
- **Interview Scheduling:** Candidates can select and book available interview slots.
- **Announcement System:** Personalized dashboards displaying final acceptance/rejection results and department assignments.

### 📋 Interviewer Assessment
- **Role-Based Dashboard:** Interviewers view only their assigned interview schedules and candidates.
- **Candidate Scoring:** Structured grading forms based on specific departmental evaluation criteria.
- **Decision Input:** Interviewers can input their final recommendations (Accept/Reject) and suggest department assignments.

### 🔑 Admin & DSS Capabilities
- **Profile Matching DSS:** Automatically calculates Gap Scores, maps them to weights, and computes Core Factor and Secondary Factor averages to rank candidates objectively.
- **Department & Criteria Management:** Admins define departments and their specific evaluation criteria (Core vs. Secondary factors and target scores).
- **Schedule Management:** Create interview sessions and assign interviewers to time slots.
- **Final Announcements:** Admins review DSS rankings, finalize decisions, and publish results to the public announcement board.

---

## Tech Stack

- **Frontend Client:** Flutter Mobile Client connecting / Laravel Blade (Admin & Interviewer Dashboards)
- **Backend:** Laravel 13 (PHP 8.2+)
- **Database:** MySQL / PostgreSQL
- **ORM:** Eloquent ORM
- **Authentication:** Laravel Sanctum (Token-based API Auth) & Session-based Web Auth
- **File Storage:** Local Storage (Laravel Storage - `storage/app/public`)
- **Email Service:** SMTP (Configurable via Laravel Mail)
- **API Documentation:** Scribe

---

## Project Structure

```text
himatik-decision-support-system/
├── web/                           # Main Backend Laravel Application
│   ├── app/                       # Application logic
│   │   ├── Http/Controllers/      # Web and API Controllers
│   │   ├── Models/                # Eloquent Models (User, Candidate, Departmentsbiro, etc.)
│   │   └── Services/              # Business logic (e.g., ProfileMatchingService)
│   ├── database/                  # Database structure
│   │   ├── migrations/            # Schema definitions
│   │   └── seeders/               # Initial database population scripts
│   ├── public/                    # Publicly accessible files and storage symlink
│   ├── resources/                 # Frontend assets
│   │   └── views/                 # Blade templates for Web UI and Blade Docs
│   ├── routes/                    # Route definitions (web.php, api.php)
│   └── storage/                   # Uploaded files, logs, and framework cache
└── README.md                      # Project documentation
```

---

## Installation Guide

Follow these steps to set up the project locally.

### 1. Clone the Repository
```bash
git clone https://github.com/your-repo/himatik-decision-support-system.git
cd himatik-decision-support-system/web
```

### 2. Install Dependencies
```bash
composer install
npm install
npm run build
```

### 3. Configure Environment Variables
Copy the example environment file and generate an application key.
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup Database
Create a new database (e.g., `himatik_dss`) in your MySQL/PostgreSQL server, then update your `.env` file with the database credentials (see [Environment Variables](#environment-variables) below).

### 5. Run Migrations & Seeders
This will create the necessary tables and populate the database with default departments, criteria, and admin/interviewer accounts.
```bash
php artisan migrate:fresh --seed
```

### 6. Create Storage Symlink
Link the `storage/app/public` directory to `public/storage` so uploaded files are publicly accessible.
```bash
php artisan storage:link
```

### 7. Start the Server
Run the backend server:
```bash
php artisan serve
```

For local development, also run the Vite asset watch server in a separate terminal to compile Tailwind CSS dynamically:
```bash
npm run dev
```

### 8. Access the Website
- Web Interface: `http://localhost:8000`
- API Documentation: `http://localhost:8000/docs`
- Blade View Documentation: `http://localhost:8000/docs/blade`
- API View Documentation : `http://localhost:8000/docs`

---

## Environment Variables

Update your `.env` file with the following configurations:

```ini
APP_NAME="HIMATIK DSS"
APP_ENV=local
APP_KEY=base64:your_generated_key_here
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=himatik_dss
DB_USERNAME=root
DB_PASSWORD=

# File Storage
FILESYSTEM_DISK=public

# Email Service Configuration (Example using SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="recruitment@himatik.pnj.ac.id"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Default Accounts

After running `php artisan db:seed`, the following default accounts are available:

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@himatik.org` | `password123` |
| **Interviewer** | `keroh@himatik.org` | `password123` |
| **Candidate** | *(Register a new account via the website)* | - |

---

## Role Permissions

### 🎓 Candidate
- **Registration & Login:** Create a new account and access the candidate dashboard.
- **Profile Setup:** Fill in academic details, contact information, and department preferences.
- **Document Upload:** Upload required documents (Formulir, Photos, Statement Letters, Social Media Proof).
- **Interview Scheduling:** View available interview slots and book a session.
- **Status & Announcements:** View recruitment status, DSS score breakdown (once published), and final acceptance results.

### 📋 Interviewer
- **Dashboard Access:** Login to view assigned interview schedules.
- **Candidate Evaluation:** Access detailed grading forms for assigned candidates based on department criteria.
- **Input Scores:** Evaluate candidates on a scale of 1-5 for various Core and Secondary factors.
- **Decision Making:** Submit Accept/Reject recommendations and suggest department assignments.

### 🔑 Admin
- **Dashboard Overview:** View live statistics on registered, scheduled, and evaluated candidates.
- **Department Management:** Add, edit, or delete departments/bureaus and define Profile Matching weights (Core vs. Secondary).
- **Criteria Management:** Define evaluation criteria, target scores, and factor types (Core/Secondary) for each department.
- **Schedule Management:** Create interview sessions and assign specific interviewers to them.
- **User Management:** Create and manage Interviewer accounts.
- **DSS Calculation & Rankings:** View automatically generated candidate rankings based on Profile Matching calculations.
- **Final Announcements:** Override or confirm interviewer decisions and publish final results to all candidates.

---

## Main Application Flow

1. **Entry:** User visits the Landing Page (`/`).
2. **Registration (Stage 1):** User creates an account (`/register`).
3. **Profile Completion (Stage 2):** User completes profile, selects Staff/BPH type, and uploads documents (`/register-candidate`).
4. **Scheduling:** Candidate books an available interview slot (`/schedule`).
5. **Evaluation:** Interviewer logs in, views their schedule, and grades the candidate based on department criteria (`/interviewer/grade/...`).
6. **DSS Ranking:** Admin reviews the auto-calculated Profile Matching rankings for each department (`/admin/rankings/...`).
7. **Decision:** Admin or Interviewer submits the final Accept/Reject decision.
8. **Announcement:** Admin publishes results (`/admin/publish`). Candidates see their results on their dashboard, and accepted candidates appear on the public board (`/announcements`).

---

## DSS / Profile Matching Explanation

The system uses the **Profile Matching** algorithm to determine candidate suitability for specific departments:

1. **Target Scores:** Every criterion has an ideal Target Score (e.g., 4 or 5).
2. **Gap Calculation:** `Gap = Candidate Score - Target Score`.
3. **Weight Mapping:** The Gap is converted into a Weight Value based on a predefined table (e.g., Gap 0 = Weight 5, Gap -1 = Weight 4, etc.).
4. **Core vs. Secondary Factors:** 
   - **Core Factors:** Essential skills/attributes for the department.
   - **Secondary Factors:** Supporting skills.
5. **Factor Averages:** The system calculates the Core Factor Average (NCF) and Secondary Factor Average (NSF).
6. **Final Score Calculation:** `Total Score = (Department Core Weight * NCF) + (Department Secondary Weight * NSF)`.
7. **Ranking:** Candidates are ranked from highest to lowest Total Score, providing admins with an objective, data-driven hiring recommendation.

---

## API Configuration

The application exposes a robust REST API for frontend-backend communication (e.g., for a React, Vue, or Flutter client).

- **Authentication:** All protected endpoints require a Bearer Token issued via Laravel Sanctum.
- **Login Endpoint:** `POST /api/login` returns the user data, role, and the Sanctum `token`.
- **Protected Requests:** Include the header `Authorization: Bearer <token>` in all subsequent API requests.
- **Interactive Docs:** Visit `http://localhost:8000/docs` to view all available endpoints, required parameters, and test API calls directly from your browser.

---

## Security Notes

- **Authentication:** Managed securely via Laravel Sanctum for API and session-based auth for web.
- **Authorization:** Strict role-based middleware (`role:admin`, `role:interviewer`, `role:candidate`) ensures users can only access their permitted routes and data.
- **Input Validation:** Comprehensive backend validation rules for all form submissions to prevent SQL injection and invalid data entry.
- **File Upload Validation:** Strict MIME type (`mimes:jpeg,png,jpg,pdf`) and file size (`max:2048`) restrictions on all document uploads.
- **Password Hashing:** Passwords are automatically hashed using Bcrypt before being stored in the database.
- **CSRF Protection:** All web forms are protected using Laravel's native CSRF tokens.

---

## Troubleshooting

### 1. Database Connection Error
**Issue:** `SQLSTATE[HY000] [1045] Access denied for user...`
**Solution:** Verify your database credentials in the `.env` file (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`). Ensure your local database server (MySQL/Postgres) is running.

### 2. Uploaded Images Not Showing (404 Error)
**Issue:** Broken image links or missing PDF documents.
**Solution:** Ensure you have created the symbolic link for storage by running `php artisan storage:link`.

### 3. API Docs (Scribe) Not Generating
**Issue:** Running `php artisan scribe:generate` fails.
**Solution:** Ensure your database is running before generating docs, as Scribe utilizes database transaction helpers to safely simulate API requests. 

### 4. Authentication / Token Issues via API
**Issue:** Receiving `401 Unauthorized` on API requests.
**Solution:** Ensure you are passing the `Accept: application/json` header and the `Authorization: Bearer <token>` header in your requests. Verify the token hasn't expired or been revoked via logout.