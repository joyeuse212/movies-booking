USE movie_ticket_booking;

-- Users (Admin and Customer)
INSERT INTO users (username, email, password_hash, role) VALUES
('admin', 'admin@gmail.com', '$2y$10$OSpPeRDgIynsclMoLRfRuOcJunJfvX5ucaKsvNOBrtgGoa3vcig8.', 'admin'),
('ishimwe joy', 'joyishimwe201@gmail.com', '$2y$10$KXvao9RdBd57lzAC5jXdWOj1fgdBX9AnNcCT1BCBfSuF7etsoI31a', 'customer');

-- Movies
INSERT INTO movies (title, genre, duration) VALUES
('Doinglife', 'action', 120),
('Avatar', 'actions', 150),
('John Wick', 'action', 130),
('The Batman', 'actions', 250);

-- Showtimes
INSERT INTO showtimes (movie_id, hall, start_time, price) VALUES
(1, 'auditorium', '2026-11-20 17:00:00', 5000.00),
(2, 'main hall', '2026-11-21 18:00:00', 10000.00),
(3, 'hall2', '2026-11-19 12:00:00', 15000.00),
(4, 'hall2', '2026-11-23 20:00:00', 10000.00);