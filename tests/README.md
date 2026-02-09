# QA Organization Standard (The Testing Pyramid)

In professional software development, Testing/QA is organized in layers, from technical unit tests to user-facing E2E tests.

## 1. Unit Tests (`tests/Unit/`)
- **What:** Tests isolated functions, classes, or methods. No database, no HTTP, no UI.
- **Goal:** Verify logic (e.g., does `checkPassword()` return true for correct password?).
- **Current Project:** `tests/Unit/AuthTest.php` checks the `src/Auth.php` class.
- **Tool:** PHPUnit (Standard), Mocha/Jest (JS).

## 2. Integration Tests (`tests/Integration/`)
- **What:** Tests how modules work together (e.g., API Endpoint + Database).
- **Goal:** Verify data flow (e.g., POST to `/api/login` reads `users.json` correctly).
- **Current Project:** `tests/Integration/ApiTest.php` calls the running server.
- **Tool:** PHPUnit, Postman/Newman.

## 3. End-to-End (E2E) Tests (`tests/E2E/`)
- **What:** Simulations of a real user clicking buttons in a browser.
- **Goal:** Verify the full user journey (Web Page + JS + API + DB).
- **Current Project:** Manual testing via `public/qa.html` or Browser.
- **Tool:** Cypress, Selenium, Playwright.

## 4. Documentation (`docs/qa/`)
- **Test Plans:** Detailed documents describing what will be tested.
- **Bug Reports:** Jira/Trello tickets describing failures.
- **Release Notes:** What was fixed in this version.

## 5. CI/CD (Automation)
- In a real company, these tests run automatically on GitHub/GitLab every time a developer pushes code.
- If unit tests fail, the code is rejected.

---

### How to Run Structure in This Project

1. **Unit:** `php tests/Unit/AuthTest.php`
2. **Integration:** (Requires Server) `php tests/Integration/ApiTest.php`
3. **E2E:** Manual via `run_app.bat` -> QA Dashboard.
