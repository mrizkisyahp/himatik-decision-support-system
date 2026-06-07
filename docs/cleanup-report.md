# Cleanup Report

Last reviewed: 2026-06-07

## Files Removed

| File | Reason | Verification |
| --- | --- | --- |
| `docs/test.md` | Empty placeholder documentation file. | File was zero-length when read, and `rg` found no direct reference to `test.md`. |
| `web/resources/views/welcome.blade.php` | Laravel default welcome page not routed by `routes/web.php`. | Removed after explicit user approval; route scan found no web route rendering it. |
| `web/resources/views/admin/stub.blade.php` | Legacy stub UI no longer used by current admin modules. | Removed after deleting `AdminWebController` stub methods and confirming no active route renders it. |
| `web/resources/views/admin/testing.blade.php` | Legacy SPK sandbox page replaced by the current Profile Matching admin page. | Removed after deleting `/admin/testing` web routes and testing controller methods. |
| `web/resources/views/admin/interviewers.blade.php` | Legacy interviewer account page replaced by `/admin/accounts`. | Removed after deleting `/admin/interviewers` web routes and legacy controller methods. |
| `web/resources/views/admin/rankings.blade.php` | Legacy rankings view was not returned by the current controller; `/admin/profile-matching` is the active admin page. | Removed after deleting `/admin/rankings/{department}` web route. |
| `web/resources/views/layouts/admin.blade.php` | Legacy admin layout only supported deleted legacy admin views. | Removed after deleting the views that extended it. |

## Files Edited

| File | Change | Reason |
| --- | --- | --- |
| `docs/project-structure.md` | Added project structure documentation. | Captures current Laravel app map after scanning routes, controllers, views, models, services, and assets. |
| `docs/blade.md` | Added Blade/UI documentation. | Documents current public/auth/candidate/admin/interviewer views and route connections. |
| `docs/api.md` | Added API documentation. | Documents actual `routes/api.php` endpoints and controller validation behavior. |
| `docs/cleanup-report.md` | Added cleanup findings. | Records safe removal and suspected unused files without deleting uncertain files. |
| `web/app/Http/Controllers/Web/BladeDocsController.php` | Refreshed runtime `/docs/blade` route metadata. | Existing Blade docs contained stale route names and omitted newer admin/interviewer pages. |

## Suspected Unused Or Legacy, Not Removed

These files or code paths may be cleanup candidates, but they were not removed because they are not 100% proven unused or may still be useful for demos, legacy links, or package overrides.

| Path | Why suspected | Why not removed |
| --- | --- | --- |
| `web/resources/views/scribe/index.blade.php` | Package override may be custom. | Scribe routes are active. |
| `web/resources/views/vendor/l5-swagger/index.blade.php` | Package override may be custom. | L5 Swagger routes and config are active. |
| `web/public/images/screen.png` | Not found in scanned Blade references. | Public assets should not be deleted without checking external references/design use. |

## Code Cleanup Notes

- No migrations, models, services, auth behavior, or Profile Matching logic were removed or changed.
- No public assets were removed.
- Legacy web routes for `/admin/testing`, `/admin/interviewers`, `/admin/rankings/{department}`, old `/admin/criteria/*` aliases, and old single-slot `/admin/schedules` CRUD were removed after explicit user approval.
- No file was deleted unless it was empty and unreferenced.

## Verification

| Command | Result | Notes |
| --- | --- | --- |
| `php artisan view:cache` | Passed | Blade templates compiled successfully. |
| `php artisan route:list` | Passed | Laravel listed 139 routes successfully. |
| `git diff --check -- docs web/app/Http/Controllers/Web/BladeDocsController.php web/resources/views/docs/blade.blade.php` | Passed | No whitespace errors in docs cleanup changes. |
| `php artisan test` | Blocked by environment | Test runner uses in-memory SQLite, but this PHP runtime is missing the SQLite PDO driver (`could not find driver`). |
| `php artisan scribe:generate` | Blocked by Scribe cache deletion | Route extraction completed, then Scribe failed deleting `.scribe/endpoints/00.yaml`. Generated tracked docs were not updated. |

## Recommended Next Cleanup Passes

1. API docblock cleanup: refresh Scribe docblocks to match `docs/api.md`, then regenerate Scribe output.
2. Asset audit: inspect real browser/network usage before deleting any images.
