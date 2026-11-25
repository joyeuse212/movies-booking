# SCREENHOUSE - Test Plan

This document outlines the test cases used to verify the functionality, security, and business logic of the SCREENHOUSE application, fulfilling the requirement for ≥10 test cases (Happy Path + Failure Path).

| Test ID | Feature Tested | Type | Test Case Description | Expected Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-01** | Registration | Happy Path | Create a new user account with valid details. | User is redirected to Login with a success message. Database stores a hashed password. | PASS |
| **TC-02** | Registration | Failure Path | Try to register with an email that already exists in the DB. | Error message: "This email address is already registered." | PASS |
| **TC-03** | Login | Happy Path | Log in as Admin with valid credentials. | Redirects to `/admin/dashboard`. Session is created and ID regenerated. | PASS |
| **TC-04** | Login | Failure Path | Enter valid email but incorrect password. | Error message: "Invalid email or password." | PASS |
| **TC-05** | CSRF Security | Failure Path | Remove the `csrf_token` hidden input from the "Delete Movie" form via DevTools and submit. | The `MovieController` rejects the request. Redirects with error: "Security token invalid." | PASS |
| **TC-06** | Admin CRUD | Happy Path | Add a new Movie with valid Title, Genre, and Duration. | Movie is saved to the DB. Success message displayed on the Manage Movies page. | PASS |
| **TC-07** | Admin CRUD | Failure Path | Attempt to delete a Movie that has active showtimes linked to it. | Deletion is blocked by foreign key constraints. Error: "Cannot delete movie. It has scheduled showtimes." | PASS |
| **TC-08** | JSON API | Happy Path | Type "Bat" into the customer live search bar. | JavaScript `fetch()` sends request to `/api/movies/search?q=Bat`. Grid updates instantly without page reload. | PASS |
| **TC-09** | Booking | Happy Path | Select a showtime, click seat A1, choose payment method, click Pay. | Booking is saved. Seat A1 is marked as booked. Success message shown. | PASS |
| **TC-10** | Booking | Failure Path | Two users attempt to book seat A1 for the same showtime simultaneously. | The `UNIQUE (showtime_id, seat_no)` constraint catches the second attempt. Error: "This seat is already booked." | PASS |
| **TC-11** | Input Validation | Failure Path | Try to add a Movie with a duration of -5 minutes. | Server-side validation catches the invalid integer and rejects the form. | PASS |
| **TC-12** | XSS Security | Failure Path | Try to name a Movie `<script>alert('hack')</script>`. | The text is escaped via `htmlspecialchars()` and displays as raw text. Script does not execute. | PASS |