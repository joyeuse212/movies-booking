<?php

namespace App\Models;

use PDO;

class Seat
{
    private PDO $conn;

    // Receive database connection
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Retrieve showtime and movie information
    public function getShowtimeDetails(int $showtimeId): array|false
    {
        $stmt = $this->conn->prepare(
            "SELECT
                showtimes.showtime_id,
                showtimes.movie_id,
                showtimes.hall,
                showtimes.start_time,
                showtimes.price,
                movies.title,
                movies.genre,
                movies.duration
             FROM showtimes
             INNER JOIN movies
                ON showtimes.movie_id = movies.movie_id
             WHERE showtimes.showtime_id = ?
             AND showtimes.start_time >= NOW()"
        );

        $stmt->execute([$showtimeId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Retrieve seats that are already booked
    public function getBookedSeats(int $showtimeId): array
    {
        $stmt = $this->conn->prepare(
            "SELECT seat_no
             FROM bookings
             WHERE showtime_id = ?
             AND status <> 'cancelled'"
        );

        $stmt->execute([$showtimeId]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}