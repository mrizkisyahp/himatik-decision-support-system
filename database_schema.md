# Database Schema

This document reflects the current Laravel migration files in `web/database/migrations` as of June 7, 2026. It describes the schema produced by the current migration set, including later refactor migrations.

## Notes

- `departmentsbiro` is the canonical department/biro table.
- `candidate_type` values are `staff` and `bph`.
- Candidate identity fields such as `nim`, `nickname`, `prodi`, `kelas`, `phone`, and `address` are currently stored on `users`.
- `candidate_departmentsbiro` stores department/biro choices instead of `first_choice_id` and `second_choice_id` columns.
- `interviewer_schedule` is created by an older migration but dropped by `2026_06_07_032221_refactor_interview_schedules_table.php`; it is not part of the final current schema.
- Open recruitment quota is decoupled from open recruitment period rows and stored by `candidate_type + department_id`.

## Core Auth And System Tables

### users

Stores all authenticated accounts: admin, interviewer, and candidate.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| name | string | Required |
| email | string | Unique |
| email_verified_at | timestamp nullable | Email verification timestamp |
| google_id | string nullable | Unique Google account ID for linked Google OAuth accounts |
| auth_provider | string | Default `local`; set to `google` for Google-linked accounts |
| avatar_url | string nullable | Google profile avatar URL |
| password | string | Hashed password |
| nim | string(10) nullable | Unique |
| nickname | string nullable | Candidate nickname |
| prodi | string nullable | Program studi |
| kelas | string(50) nullable | Class |
| phone | string(20) nullable | Phone number |
| address | text nullable | Full address |
| role | enum | `admin`, `interviewer`, `candidate`; default `candidate` |
| remember_token | string nullable | Laravel remember token |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |
| department_id | foreignId nullable | References `departmentsbiro.id`, `nullOnDelete`; added for department-scoped accounts such as interviewers |

### password_reset_tokens

| Column | Type | Notes |
| --- | --- | --- |
| email | string | Primary key |
| token | string | Reset token |
| created_at | timestamp nullable |  |

### sessions

| Column | Type | Notes |
| --- | --- | --- |
| id | string | Primary key |
| user_id | foreignId nullable | Indexed, not constrained in migration |
| ip_address | string(45) nullable |  |
| user_agent | text nullable |  |
| payload | longText | Session payload |
| last_activity | integer | Indexed |

### cache

| Column | Type | Notes |
| --- | --- | --- |
| key | string | Primary key |
| value | mediumText |  |
| expiration | bigint | Indexed |

### cache_locks

| Column | Type | Notes |
| --- | --- | --- |
| key | string | Primary key |
| owner | string |  |
| expiration | bigint | Indexed |

### jobs

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| queue | string | Indexed |
| payload | longText |  |
| attempts | unsignedSmallInteger |  |
| reserved_at | unsignedInteger nullable |  |
| available_at | unsignedInteger |  |
| created_at | unsignedInteger |  |

### job_batches

| Column | Type | Notes |
| --- | --- | --- |
| id | string | Primary key |
| name | string |  |
| total_jobs | integer |  |
| pending_jobs | integer |  |
| failed_jobs | integer |  |
| failed_job_ids | longText |  |
| options | mediumText nullable |  |
| cancelled_at | integer nullable |  |
| created_at | integer |  |
| finished_at | integer nullable |  |

### failed_jobs

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| uuid | string | Unique |
| connection | string | Indexed with `queue`, `failed_at` |
| queue | string | Indexed with `connection`, `failed_at` |
| payload | longText |  |
| exception | longText |  |
| failed_at | timestamp | Defaults to current timestamp; indexed with `connection`, `queue` |

### personal_access_tokens

Laravel Sanctum API token table.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| tokenable_type | string | Morph type |
| tokenable_id | bigint | Morph id |
| name | text | Token name |
| token | string(64) | Unique |
| abilities | text nullable |  |
| last_used_at | timestamp nullable |  |
| expires_at | timestamp nullable | Indexed |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

## Department/Biro

### departmentsbiro

Canonical master table for all departments and biro.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| name | string | Unique |
| slug | string | Unique |
| description | text nullable | Public description |
| contact_person | string nullable | Public/contact info |
| personal_aspect_weight | decimal(5,2) | Default `60.00` |
| organizational_aspect_weight | decimal(5,2) | Default `40.00` |
| core_factor_weight | decimal(5,2) | Default `60.00` |
| secondary_factor_weight | decimal(5,2) | Default `40.00` |
| is_active | boolean | Default `true` |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

### department_work_programs

Program kerja per department/biro.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| department_id | foreignId | References `departmentsbiro.id`, cascade delete |
| name | string | Program name |
| description | text nullable |  |
| period | string nullable |  |
| is_active | boolean | Default `true` |
| sort_order | unsignedInteger | Default `0` |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

Index: `department_id, is_active, sort_order`.

### department_agendas

Agenda per department/biro.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| department_id | foreignId | References `departmentsbiro.id`, cascade delete |
| title | string | Agenda title |
| description | text nullable |  |
| start_date | date nullable |  |
| end_date | date nullable |  |
| location | string nullable |  |
| is_active | boolean | Default `true` |
| sort_order | unsignedInteger | Default `0` |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

Index: `department_id, is_active, sort_order`.

## Candidate Registration

### candidates

Stores the candidate recruitment profile linked one-to-one with a user account.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| user_id | foreignId | Unique, references `users.id`, cascade delete |
| candidate_type | enum | `staff`, `bph`; default `staff` |
| photo_path | string nullable | 3x4 photo path |
| department_choice_reason | text nullable | Reason for department choices |
| weakness_description | text nullable | Candidate weakness essay |
| contribution_plan | text nullable | Candidate contribution plan |
| instagram_proof_path | string nullable | Instagram proof upload |
| youtube_proof_path | string nullable | YouTube proof upload |
| political_statement_path | string nullable | Statement upload |
| candidate_signature_path | string nullable | Candidate signature upload |
| parent_signature_path | string nullable | Parent signature upload |
| status | enum | `registered`, `scheduled`, `evaluated`, `completed`; default `registered` |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

### candidate_departmentsbiro

Stores first and second department/biro choices.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| candidate_id | foreignId | References `candidates.id`, cascade delete |
| departmentsbiro_id | foreignId | References `departmentsbiro.id`, cascade delete |
| choice_order | unsignedTinyInteger | Usually `1` or `2` |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

Constraints:

- Unique `candidate_id + choice_order`
- Unique `candidate_id + departmentsbiro_id`

### candidate_educations

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| candidate_id | foreignId | References `candidates.id`, cascade delete |
| education_type | enum | `formal`, `informal` |
| school_name | string |  |
| start_year | unsignedSmallInteger |  |
| end_year | unsignedSmallInteger nullable |  |
| city | string |  |
| major | string nullable |  |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

### candidate_organizations

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| candidate_id | foreignId | References `candidates.id`, cascade delete |
| organization_name | string |  |
| start_year | unsignedSmallInteger |  |
| end_year | unsignedSmallInteger nullable |  |
| place_or_institution | string |  |
| position | string |  |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

### candidate_committees

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| candidate_id | foreignId | References `candidates.id`, cascade delete |
| committee_name | string |  |
| start_year | unsignedSmallInteger |  |
| end_year | unsignedSmallInteger nullable |  |
| organizer | string |  |
| position | string |  |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

### candidate_skills

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| candidate_id | foreignId | References `candidates.id`, cascade delete |
| skill_type | enum | `soft`, `hard` |
| skill_name | string |  |
| proficiency | enum | `dasar`, `sedang`, `cakap` |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

### candidate_facilities

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| candidate_id | foreignId | References `candidates.id`, cascade delete |
| facility_name | string |  |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

### email_verification_otps

Stores one active OTP record per user.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| user_id | foreignId | Unique, references `users.id`, cascade delete |
| code_hash | string | Hashed OTP code |
| attempts | unsignedTinyInteger | Default `0` |
| expires_at | timestamp |  |
| consumed_at | timestamp nullable |  |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

## Open Recruitment

### open_recruitments

Stores the current recruitment window per candidate type.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| candidate_type | enum | `staff`, `bph`; unique |
| starts_at | dateTime nullable | Recruitment start |
| ends_at | dateTime nullable | Recruitment end |
| status | enum | `open`, `closed`; default `closed` |
| interview_location | string nullable | Interview location shown/configured for recruitment |
| interview_requirements | text nullable | Interview requirements |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

### open_recruitment_extensions

Audit table for repeated recruitment period extensions.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| open_recruitment_id | foreignId | References `open_recruitments.id`, cascade delete |
| old_starts_at | dateTime nullable | Previous start |
| old_ends_at | dateTime nullable | Previous end |
| new_starts_at | dateTime nullable | New start |
| new_ends_at | dateTime nullable | New end |
| reason | text nullable | Optional extension reason |
| extended_by | foreignId nullable | References `users.id`, null on delete |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

### open_recruitment_quotas

Operational quota per candidate type and department/biro.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| candidate_type | enum | `staff`, `bph` |
| department_id | foreignId | References `departmentsbiro.id`, cascade delete |
| quota | unsignedInteger | Default `0` |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

Constraint: unique `candidate_type + department_id`.

### open_recruitment_quota_logs

Audit table for quota changes.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| candidate_type | enum | `staff`, `bph` |
| department_id | foreignId | References `departmentsbiro.id`, cascade delete |
| old_quota | unsignedInteger nullable | Previous quota |
| new_quota | unsignedInteger | New quota |
| changed_by | foreignId nullable | References `users.id`, null on delete |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

## Interview Scheduling

### interview_schedules

Final schedule structure after the schedule refactor migration.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| department_id | foreignId | References `departmentsbiro.id`, cascade delete |
| date | date | Interview date |
| start_time | time |  |
| end_time | time |  |
| is_blocked | boolean | Default `false` |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

### candidate_interview_schedules

Stores one selected interview schedule per candidate.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| candidate_id | foreignId | Unique, references `candidates.id`, cascade delete |
| interview_schedule_id | foreignId | Unique, references `interview_schedules.id`, cascade delete |
| department_id | foreignId | References `departmentsbiro.id`, cascade delete |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

Constraint behavior:

- One candidate can have only one selected interview schedule.
- One schedule row can be booked by only one candidate.
- Same clock time can still exist for different departments because schedules are department-specific rows.

## Evaluation And SPK

### default_evaluation_criteria

Template criteria used to reset or seed department-specific criteria.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| code | string | Unique, such as `K1` |
| name | string |  |
| description | text nullable |  |
| type | enum | `core`, `secondary` |
| aspect | enum | `personal`, `organizational` |
| target_score | unsignedTinyInteger | Default `3` |
| catatan | text nullable |  |
| is_active | boolean | Default `true` |
| sort_order | unsignedInteger | Default `0` |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

### evaluation_criteria

Department-specific criteria used by the Profile Matching calculation.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| department_id | foreignId | References `departmentsbiro.id`, cascade delete |
| default_criteria_id | foreignId nullable | References `default_evaluation_criteria.id`, null on delete |
| code | string nullable | Unique per department |
| name | string |  |
| description | text nullable |  |
| type | enum | `core`, `secondary` |
| aspect | enum | `personal`, `organizational` |
| target_score | unsignedTinyInteger | Default `3` |
| catatan | text nullable |  |
| is_active | boolean | Default `true` |
| sort_order | unsignedInteger | Default `0` |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

Constraint: unique `department_id + code`.

### evaluations

Stores one shared score per candidate, department, and criterion. `interviewer_id` means the user who last updated the score.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| candidate_id | foreignId | References `candidates.id`, cascade delete |
| department_id | foreignId | References `departmentsbiro.id`, cascade delete |
| criteria_id | foreignId | References `evaluation_criteria.id`, cascade delete |
| score | integer | Score value |
| notes | text nullable | Interview/evaluation notes |
| interviewer_id | foreignId | References `users.id`, cascade delete |
| version | unsignedInteger | Default `1`; optimistic locking support |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

Constraint: unique `candidate_id + department_id + criteria_id`.

### spk_gap_weights

Gap-to-weight mapping for Profile Matching.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| gap | integer | Unique |
| weight | decimal(8,4) |  |
| description | string nullable |  |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

### spk_results

Stores the latest calculated SPK result per candidate and department.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| candidate_id | foreignId | References `candidates.id`, cascade delete |
| department_id | foreignId | References `departmentsbiro.id`, cascade delete |
| final_score | decimal(8,4) | Default `0` |
| personal_core_score | decimal(8,4) | Default `0` |
| personal_secondary_score | decimal(8,4) | Default `0` |
| personal_score | decimal(8,4) | Default `0` |
| organizational_core_score | decimal(8,4) | Default `0` |
| organizational_secondary_score | decimal(8,4) | Default `0` |
| organizational_score | decimal(8,4) | Default `0` |
| rank_position | unsignedInteger nullable | Ranking position |
| calculation_details | json | Calculation snapshot |
| calculated_by | foreignId nullable | References `users.id`, null on delete |
| calculated_at | timestamp |  |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

Constraint: unique `candidate_id + department_id`.

### spk_calculation_logs

Stores SPK calculation run history, not per-candidate result details.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| department_id | foreignId nullable | References `departmentsbiro.id`, null on delete |
| trigger_type | enum | `manual`, `auto`, `reset`; default `manual` |
| triggered_by | foreignId nullable | References `users.id`, null on delete |
| status | enum | `success`, `partial`, `failed`; default `success` |
| candidates_count | unsignedInteger | Default `0` |
| notes | text nullable |  |
| duration_ms | unsignedInteger nullable | Calculation duration |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

## Announcement

### announcements

Stores public/private final recruitment result state per candidate.

| Column | Type | Notes |
| --- | --- | --- |
| id | bigint | Primary key |
| candidate_id | foreignId | Unique, references `candidates.id`, cascade delete |
| assigned_department_id | foreignId nullable | References `departmentsbiro.id`, set null on delete |
| status | enum | `pending`, `accepted`, `rejected`; default `pending` |
| is_published | boolean | Default `false` |
| published_at | dateTime nullable |  |
| created_at | timestamp nullable |  |
| updated_at | timestamp nullable |  |

## Removed Or Superseded Structures

These are not part of the final current migrated schema:

- `candidates.first_choice_id`
- `candidates.second_choice_id`
- `candidates.recruitment_form_path`
- `candidates.statement_letter_path`
- `candidates.social_media_proof_path`
- `interview_schedules.session_name`
- `interview_schedules.scheduled_at`
- `interview_schedules.location`
- `interview_schedules.is_active`
- `interviewer_schedule` table, because it is dropped by the latest interview schedule refactor migration
- `open_recruitments.is_public`
- `open_recruitments.status` values `draft` and `active`
- `open_recruitment_quotas.open_recruitment_id`
- `open_recruitment_quota_logs.open_recruitment_id`
