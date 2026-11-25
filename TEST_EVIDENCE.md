# Test Evidence - SCREENHOUSE

This document provides evidence of testing for the SCREENHOUSE Movie Ticket Booking System, fulfilling the test plan requirements.

## 1. Security Evidence (Failure Paths)

### 1.1 CSRF Token Protection
*   **Action Taken:** Removed the `<input type="hidden" name="csrf_token">` from the "Add Movie" form using browser developer tools.
*   **Result:** The `MovieController` detected the missing token via `verify_csrf_token()`. The request was rejected, and the user was redirected to `/admin/movies?status=csrf_error`.
*   **UI Output:** A red error message displayed: *"Security token invalid. Please try again."*

### 1.2 Password Hashing Verification
*   **Action Taken:** Registered a new user via `register.php`.
*   **Result:** Queried the `users` table in phpMyAdmin.
*   **Evidence:** The `password_hash` column displayed a bcrypt hash (`$2y$10$...`) instead of the plain text password. `password_verify()` successfully validates the login.

### 1.3 SQL Injection Prevention
*   **Action Taken:** Entered `admin@gmail.com' OR '1'='1` into the login email field.
*   **Result:** The PDO prepared statement (`WHERE email = ?`) treated the entire string as a literal email address. The login failed with *"Invalid email or password."*

## 2. Business Logic Evidence (Happy Paths & Edge Cases)

### 2.1 JSON API Live Search
*   **Action Taken:** Navigated to `/customer/movies` and typed "Bat" into the search input.
*   **Result:** A `fetch()` request was sent to `/api/movies/search?q=Bat`. The server returned a JSON array containing "The Batman". The JavaScript successfully updated the DOM without reloading the page.

### 2.2 Double Booking Prevention
*   **Action Taken:** Attempted to book seat `A1` for Showtime `1` while it was already booked by another user.
*   **Result:** The `Booking` model's `createBooking()` method threw a `PDOException` due to the database `UNIQUE (showtime_id, seat_no)` constraint.
*   **UI Output:** The controller caught the exception and displayed: *"This seat is already booked. Please choose another seat."*

### 2.3 Full CRUD Operations (Movies)
*   **Create:** Added "Inception" (Sci-Fi, 148 mins). Success message displayed.
*   **Read:** Verified "Inception" appeared in the Manage Movies table and customer grid.
*   **Update:** Changed duration from 148 to 150. Success message displayed.
*   **Delete:** Deleted "Inception". Success message displayed.
*   **Constraint Testing:** Attempted to delete a movie with active showtimes. Application blocked deletion and showed: *"Cannot delete movie. It has scheduled showtimes."*