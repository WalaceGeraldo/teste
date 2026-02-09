# QA Guide for Login NoSQL

This project is a simple Login System using a JSON file as a NoSQL database.

## Architecture
- **public/**: Frontend (HTML/CSS/JS).
- **api/**: Backend endpoints (PHP).
- **src/**: Core logic (Auth class).
- **data/**: Database storage (JSON).
- **tests/**: Automated tests.
- **scripts/**: Maintenance scripts.

## Installation
1. Ensure you have PHP installed (`php -v`).
2. Clone/Open the project.
3. Run `run_app.bat` to start the server.

## Testing Procedures

### 1. Automated Tests
Run the unit tests to verify the backend logic:
```bash
php tests/run_tests.php
```

### 2. Manual Testing (Checklist)
| ID | Test Case | Steps | Expected Result | Pass/Fail |
|----|-----------|-------|-----------------|-----------|
| TC01 | Login Success | 1. Open app<br>2. Enter `admin` / `password123`<br>3. Click Enter | "Login realizado com sucesso" | |
| TC02 | Login Fail | 1. Open app<br>2. Enter `admin` / `wrongpass`<br>3. Click Enter | "Invalid credentials" | |
| TC03 | Empty Fields | 1. Open app<br>2. Leave fields empty<br>3. Click Enter | "Usuário é obrigatório" (Client validation) | |
| TC04 | QA User | 1. Run `scripts/setup_qa.bat`<br>2. Login with `qa` / `qatest` | "Login realizado com sucesso" (Role: qa) | |

## Maintenance
- To reset the database to default: Run `scripts/reset_db.bat`.
- To setup QA environment: Run `scripts/setup_qa.bat`.

## Troubleshooting
- **404 Not Found**: Ensure you are running the server via `run_app.bat` or `php -S localhost:8000`.
- **JSON Error**: Check if `data/users.json` is valid JSON. Use `php api/test_db.php` to diagnose.
