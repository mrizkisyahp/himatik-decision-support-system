# Project Structure

Last reviewed: 2026-06-07

## Overview

This repository contains a Laravel recruitment and decision support system for HIMATIK PNJ.

- `web/`: Laravel application.
- `mobile/`: mobile application workspace, not reviewed for this Laravel documentation pass.
- `docs/`: project documentation generated/maintained for codebase orientation.
- `database_schema_design.md`: current agreed database schema design notes.
- `project-context.md`: running implementation context and completed work notes.

## Laravel App Map

### Routes

- `web/routes/web.php`: public, guest, candidate, interviewer, and admin Blade routes.
- `web/routes/api.php`: JSON API routes for public data, auth, candidate registration, schedules, interviewer grading, and admin CRUD.
- Scribe API docs are mounted at `/docs`, `/docs.openapi`, and `/docs.postman`.
- L5 Swagger docs are mounted at `/api/documentation` and `/api/docs`.

### Controllers

- `App\Http\Controllers\Web\LandingWebController`: public landing page.
- `App\Http\Controllers\Web\AuthWebController`: Blade login/logout flow.
- `App\Http\Controllers\Web\CandidateWebController`: candidate account registration, OTP verification, profile registration, dashboard, and schedule booking.
- `App\Http\Controllers\Web\InterviewerWebController`: interviewer dashboard, registrations, schedules, profile matching, scoring, decision, and department criteria management.
- `App\Http\Controllers\Web\AdminWebController`: admin dashboard, registration table, open recruitment, departments, criteria, schedules, announcements, and profile matching.
- `App\Http\Controllers\Web\AdminAccountController`: admin account management page.
- `App\Http\Controllers\Api\*`: JSON API endpoints matching the core public/auth/candidate/interviewer/admin flows.

### Views

- `web/resources/views/landing.blade.php`: public HIMATIK profile and recruitment landing page.
- `web/resources/views/auth/*`: login, account registration, and OTP pages.
- `web/resources/views/candidate/*`: candidate profile, dashboard, and schedule pages.
- `web/resources/views/admin/*`: admin layout and admin feature pages.
- `web/resources/views/interviewer/*`: interviewer layout and feature pages.
- `web/resources/views/public/announcements.blade.php`: public accepted candidate announcement page.
- `web/resources/views/docs/blade.blade.php`: in-app Blade documentation page backed by `BladeDocsController`.

### Models

Core recruitment models:

- `User`
- `Candidate`
- `Departmentsbiro`
- `CandidateDepartmentChoice`
- `InterviewSchedule`
- `CandidateInterviewSchedule`
- `Announcement`

Candidate profile child models:

- `CandidateEducation`
- `CandidateOrganization`
- `CandidateCommittee`
- `CandidateSkill`
- `CandidateFacility`

SPK/Profile Matching models:

- `DefaultEvaluationCriteria`
- `EvaluationCriteria`
- `Evaluation`
- `SpkGapWeight`
- `SpkResult`
- `SpkCalculationLog`

Open recruitment models:

- `OpenRecruitment`
- `OpenRecruitmentExtension`
- `OpenRecruitmentQuota`
- `OpenRecruitmentQuotaLog`

Department content models:

- `DepartmentAgenda`
- `DepartmentWorkProgram`

### Services and Support

- `CandidateOtpService`: creates, sends, verifies, and consumes email OTP records.
- `CandidateProfileService`: creates candidate profiles and child records transactionally.
- `OpenRecruitmentService`: resolves open recruitment visibility and availability.
- `ProfileMatchingService`: performs Profile Matching/SPK calculation and result persistence.
- `CandidateProfileRules`: shared validation rules for candidate profile submission.
- `SpkCriteriaDefaults`: canonical default criteria and target score helpers.

### Frontend Assets

- `web/resources/css/app.css`: Tailwind CSS entrypoint.
- `web/resources/js/app.js`: Alpine.js/Vite entrypoint.
- `web/public/images/*`: landing/auth visual assets, including HIMATIK logo and group photo.
- `web/public/vendor/scribe/*`: generated Scribe documentation assets.

## Verification Commands Used

- `php artisan route:list`
- `php artisan view:cache`
- targeted `rg` scans for routes, views, includes, controllers, and suspected unused files.
