# Database Schema Design

This document records the agreed target database schema for the HIMATIK PNJ recruitment DSS. It is a design note only; migrations are not implemented yet.

## Core Flow

The system flow is:

```text
register account -> verify email OTP -> landing -> candidate registration -> schedule selection -> interview evaluation -> SPK calculation -> announcement
```

## Main Design Decisions

- `departmentsbiro` is kept as the department/biro master table.
- Department/biro choices are stored in `candidate_departmentsbiro`, not `first_choice_id` / `second_choice_id`.
- Interview schedules are department-specific.
- Candidates schedule only for their first-choice department.
- Criteria are editable per department through `evaluation_criteria`.
- `default_evaluation_criteria` stores the reset/template criteria.
- Profile Matching weights are stored in `departmentsbiro`.
- Gap weights are stored in `spk_gap_weights`.
- SPK results are overwritten on recalculation, while logs are preserved in `spk_calculation_logs`.
- Inactive departments are visible to admin only. Public/candidate/interviewer flows should only use active departments.

## Laravel/Auth Tables

### users

```text
id
name
email unique
email_verified_at nullable
password
role enum('admin', 'interviewer', 'candidate') default candidate
remember_token
created_at
updated_at
```

### password_reset_tokens

```text
email primary
token
created_at nullable
```

### sessions

```text
id primary
user_id nullable index
ip_address nullable
user_agent nullable
payload
last_activity index
```

### personal_access_tokens

```text
id
tokenable_type
tokenable_id
name
token unique
abilities nullable
last_used_at nullable
expires_at nullable index
created_at
updated_at
```

### cache

```text
key primary
value
expiration index
```

### cache_locks

```text
key primary
owner
expiration index
```

### jobs

```text
id
queue index
payload
attempts
reserved_at nullable
available_at
created_at
```

### job_batches

```text
id primary
name
total_jobs
pending_jobs
failed_jobs
failed_job_ids
options nullable
cancelled_at nullable
created_at
finished_at nullable
```

### failed_jobs

```text
id
uuid unique
connection
queue
payload
exception
failed_at
```

## Department/Biro

### departmentsbiro

```text
id
name unique
slug unique
description nullable
personal_aspect_weight decimal(5,2) default 60.00
organizational_aspect_weight decimal(5,2) default 40.00
core_factor_weight decimal(5,2) default 60.00
secondary_factor_weight decimal(5,2) default 40.00
is_active boolean default true
created_at
updated_at
```

Rules:

```text
personal_aspect_weight + organizational_aspect_weight = 100
core_factor_weight + secondary_factor_weight = 100
```

Admin can see all departments. Candidate/public/interviewer endpoints should only return active departments.

## Candidate Registration

### candidates

```text
id
user_id unique foreign users
candidate_type enum('staff', 'bph') default staff
nim unique
nickname nullable
prodi enum(
  'Teknik Informatika',
  'Teknik Multimedia dan Jaringan',
  'Teknik Multimedia dan Digital'
)
kelas
phone
address nullable
photo_path
department_choice_reason nullable
weakness_description nullable
contribution_plan nullable
instagram_proof_path nullable
youtube_proof_path nullable
political_statement_path nullable
candidate_signature_path nullable
parent_signature_path nullable
status enum('registered', 'scheduled', 'evaluated', 'completed') default registered
created_at
updated_at
```

### candidate_departmentsbiro

```text
id
candidate_id foreign candidates
departmentsbiro_id foreign departmentsbiro
choice_order tinyint
created_at
updated_at
```

Constraints:

```text
unique(candidate_id, choice_order)
unique(candidate_id, departmentsbiro_id)
choice_order only 1 or 2
```

### candidate_educations

```text
id
candidate_id foreign candidates
education_type enum('formal', 'informal')
school_name
start_year
end_year nullable
city
major nullable
created_at
updated_at
```

### candidate_organizations

```text
id
candidate_id foreign candidates
organization_name
start_year
end_year nullable
place_or_institution
position
created_at
updated_at
```

### candidate_committees

```text
id
candidate_id foreign candidates
committee_name
start_year
end_year nullable
organizer
position
created_at
updated_at
```

### candidate_skills

```text
id
candidate_id foreign candidates
skill_type enum('soft', 'hard')
skill_name
proficiency enum('dasar', 'sedang', 'cakap')
created_at
updated_at
```

### candidate_facilities

```text
id
candidate_id foreign candidates
facility_name
created_at
updated_at
```

## Email Verification

### email_verification_otps

```text
id
user_id unique foreign users
code_hash
attempts unsignedTinyInteger default 0
expires_at
consumed_at nullable
created_at
updated_at
```

## Interview Scheduling

### interview_schedules

```text
id
department_id foreign departmentsbiro
session_name
scheduled_at
location nullable
is_active boolean default true
created_at
updated_at
```

Each row represents one available interview slot for one department.

### candidate_interview_schedules

```text
id
candidate_id foreign candidates
interview_schedule_id foreign interview_schedules
department_id foreign departmentsbiro
created_at
updated_at
```

Constraints:

```text
unique(candidate_id)
unique(interview_schedule_id)
```

Business rules:

```text
department_id must equal interview_schedules.department_id
candidate can only book a schedule from their first-choice department
```

### interviewer_schedule

```text
id
interview_schedule_id foreign interview_schedules
user_id foreign users
created_at
updated_at
```

Constraint:

```text
unique(interview_schedule_id, user_id)
```

## Evaluation Criteria

### default_evaluation_criteria

```text
id
code unique
name
description nullable
type enum('core', 'secondary')
aspect enum('personal', 'organizational')
target_score unsignedTinyInteger default 3
catatan text nullable
is_active boolean default true
sort_order unsignedInteger default 0
created_at
updated_at
```

Default criteria:

```text
K1 Kepercayaan Diri dan Komunikasi      personal        core
K2 Problem Solve (Pemecahan Masalah)    personal        core
K3 Manajemen Waktu                      personal        secondary
K4 Konsistensi Jawaban                  personal        secondary
K5 Komitmen                             organizational  core
K6 Pengalaman Organisasi/Kepanitiaan    organizational  secondary
K7 Pengetahuan Organisasi               organizational  secondary
```

This table is the reset/template source.

Department-specific default target scores:

```text
Department/Biro                         K1 K2 K3 K4 K5 K6 K7
Biro Kesekretariatan                    3  3  5  5  4  3  4
Biro Bendahara Umum                     3  4  4  5  5  3  3
Biro Kreatif                            4  5  4  3  4  3  2
Departemen Pendidikan dan Teknologi     4  5  4  4  4  3  4
Departemen Komunikasi dan Informasi     5  4  4  4  4  3  3
Departemen Bisnis dan Kemitraan         5  4  4  3  4  4  3
Departemen Sosial Politik               5  5  3  4  4  4  4
Departemen Sosial Mahasiswa             4  4  3  3  5  4  3
Departemen Kesehatan Mahasiswa          4  4  4  3  5  4  3
Departemen Kerohanian                   4  3  4  5  5  3  3
```

### evaluation_criteria

```text
id
department_id foreign departmentsbiro
default_criteria_id nullable foreign default_evaluation_criteria
code nullable
name
description nullable
type enum('core', 'secondary')
aspect enum('personal', 'organizational')
target_score unsignedTinyInteger default 3
catatan text nullable
is_active boolean default true
sort_order unsignedInteger default 0
created_at
updated_at
```

This table is the actual department-specific CRUD criteria table used for scoring.

Optional constraint:

```text
unique(department_id, code)
```

Only use that constraint if custom criteria always have a code.

## Evaluation Scores

### evaluations

```text
id
candidate_id foreign candidates
department_id foreign departmentsbiro
criteria_id foreign evaluation_criteria
score integer
notes text nullable
interviewer_id foreign users
version unsignedInteger default 1
created_at
updated_at
```

Constraints:

```text
unique(candidate_id, department_id, criteria_id)
score 1 to 5
```

There is only one shared score per candidate, department, and criteria. Multiple interviewers can access the same evaluation form, but they do not create separate scores. `interviewer_id` stores the last interviewer who updated the score. `version` supports optimistic locking so stale saves can be rejected. Real-time display can broadcast score changes to other interviewer screens without changing this database shape.

## Profile Matching

### spk_gap_weights

```text
id
gap integer unique
weight decimal(8,4)
description nullable
created_at
updated_at
```

Default rows:

```text
0   5.0000
1   4.5000
-1  4.0000
2   3.5000
-2  3.0000
3   2.5000
-3  2.0000
4   1.5000
-4  1.0000
```

The used gap mapping must be snapshotted into `spk_results.calculation_details` during calculation.

### spk_results

```text
id
candidate_id foreign candidates
department_id foreign departmentsbiro
final_score decimal(8,4)
personal_core_score decimal(8,4)
personal_secondary_score decimal(8,4)
personal_score decimal(8,4)
organizational_core_score decimal(8,4)
organizational_secondary_score decimal(8,4)
organizational_score decimal(8,4)
rank_position unsignedInteger nullable
calculation_details json
calculated_by nullable foreign users
calculated_at timestamp
created_at
updated_at
```

Constraint:

```text
unique(candidate_id, department_id)
```

Recalculation overwrites this row.

### spk_calculation_logs

```text
id
department_id nullable foreign departmentsbiro
trigger_type enum('manual', 'auto', 'reset')
triggered_by nullable foreign users
status enum('success', 'partial', 'failed')
candidates_count unsignedInteger default 0
notes text nullable
duration_ms unsignedInteger nullable
created_at
updated_at
```

This table stores calculation history and does not replace `spk_results`.

## Announcement

### announcements

```text
id
candidate_id unique foreign candidates
assigned_department_id nullable foreign departmentsbiro
status enum('pending', 'accepted', 'rejected') default pending
is_published boolean default false
published_at nullable
created_at
updated_at
```

Public announcements must not expose score details. Detailed evaluation results are private.
