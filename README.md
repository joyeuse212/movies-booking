# SCREENHOUSE - Online Movie Ticket Booking System

**Course:** Web Programming (Day 1 - Day 10 Project)
**Student:** ISHIMWE JOYEUSE — 224013173
**Architecture:** Custom MVC (Model-View-Controller) using Vanilla PHP
**Database:** MySQL (PDO with Prepared Statements)

## 🚀 Setup Instructions

1. **Clone the repository** into your XAMPP/WAMP `htdocs` folder:
   ```bash
   git clone https://github.com/joyeuse212/movies-booking.git

   ## 🧪 Test Evidence
To view the full testing matrix (Happy Path and Failure Path), please refer to the **`TEST_EVIDENCE.md`** file included in this repository. 

Below is a summary of the evidence verified during development:
1. **Live Search (JSON API):** Verified via browser Network tab. Typing in the search box returns a `200 OK` JSON response from `/api/movies/search`.
2. **CSRF Protection:** Verified by using browser DevTools to remove the `<input type="hidden" name="csrf_token">` from forms. Submitting the form successfully blocks the action and returns a "Security token invalid" error.
3. **Password Hashing:** Verified by inspecting the `users` table in phpMyAdmin. Passwords are stored as bcrypt hashes (e.g., `$2y$10$...`), not plain text.
4. **SQL Injection Prevention:** Verified by entering `' OR 1=1 --` into the login email field. The PDO prepared statement treats this as a literal string, and login fails.
5. **Double-Booking Prevention:** Verified by attempting to book the same seat for the same showtime twice. The database `UNIQUE` constraint throws a PDOException, which is caught and displayed as a friendly user error.