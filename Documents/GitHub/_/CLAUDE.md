# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

This repo contains two PHP web applications for cattle farm management:
- `sistema_yustre/` — the active production deployment on IONOS hosting
- `dairyfarm/` — a parallel variant of the same system (same structure, slightly different assets)
- `logs/` — server access and SFTP logs from IONOS

Both apps share identical architecture. Work primarily in `sistema_yustre/`.

## Deployment

No build system. Files are uploaded directly via SFTP to IONOS hosting. The entry point is controlled by `.htaccess`:

```
DirectoryIndex login/login.php
```

The cron job runs daily at 7:00 AM via IONOS panel:
```
/usr/bin/php /homepages/1/d4299673130/htdocs/sistema_yustre/cron/daily_check.php
```

## Architecture

### Auth & Roles

Three roles: `admin`, `clinic`, `shop`. Login is processed in `login/process_login.php` via PDO against the `employees` table. After authentication, sessions store `user_id`, `user_name`, `user_email`, `user_rol`, and `logged_in`. Each dashboard and controller guards access by checking session role at the top of the file.

### Module Pattern

Each feature follows a two-file pattern:
- **Controller** (`front/{area}/controllers{X}/{module}_controller.php`) — handles DB connection, POST actions (CRUD), and builds query result variables. Always calls `verify_csrf_token()` on POST.
- **View** (`front/{area}/modules{X}/{module}.php`) — `require_once`s its controller at the top, then renders HTML using variables set by the controller.

### Clinic modules (`front/clinic/`)
- `cow_registry` — CRUD for cows; auto-generates IDs like `AB123`
- `calves` — calf tracking
- `medicines_colostrum` — medicine stock + colostrum records

### Shop modules (`front/shop/`)
- `work_orders` — maintenance work orders; auto-generates codes like `WO-20240424-A1B2`
- `parts_inventory` — spare parts stock
- `assets` — equipment/asset registry with photo upload

### Shared utilities (`shared/`)
- `csrf_helper.php` — `generate_csrf_token()` and `verify_csrf_token()`. Always call `generate_csrf_token()` to embed the token in forms, and `verify_csrf_token()` at the start of POST handlers. **Note:** CSRF is currently commented out in `login/process_login.php` (marked as a TODO to re-enable).
- `alerts_controller.php` — included by `dashboard_clinic.php`; queries low medicine stock and calves without colostrum records
- `alerts_shop_controller.php` — included by `dashboard_shop.php`

### Database

Single MySQL database on IONOS. Credentials are hardcoded in each file that connects (no config file). Key tables: `employees`, `cows`, `calves`, `colostrum`, `medicines`, `work_orders`, `parts_inventory` (assets table name varies). Both PDO and mysqli are used — PDO in clinic controllers, mysqli in shop controllers.

### Frontend

Bootstrap 5.3 (CDN), Google Fonts Poppins (CDN), inline SVG icons, per-module CSS files in `assets/css/`. JS is minimal: `assets/js/theme-toggle.js`, `assets/js/weather.js`, `assets/js/calendar.js`.

## Known Issues / TODOs

- CSRF verification is disabled in `login/process_login.php` — needs to be re-enabled.
- DB credentials are repeated in every file that connects — no centralized config.
- `assets/js/README.txt` notes that `weather.js` and `calendar.js` are placeholders (partially implemented).
