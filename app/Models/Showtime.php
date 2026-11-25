<?php

namespace App\Models;

use PDO;

class Showtime
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Get all movies for the dropdown
    public function getAllMovies(): array
    {
        $stmt = $this->conn->query(
            "SELECT movie_id, title FROM movies ORDER BY title"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all showtimes with movie titles
    public function getAllShowtimes(): array
    {
        $stmt = $this->conn->query(
            "SELECT showtimes.*, movies.title
             FROM showtimes
             JOIN movies ON showtimes.movie_id = movies.movie_id
             ORDER BY showtimes.start_time DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new showtime
    public function createShowtime(
        int $movieId,
        string $hall,
        string $startTime,
        float $price
    ): bool {
        $stmt = $this->conn->prepare(
            "INSERT INTO showtimes (movie_id, hall, start_time, price)
             VALUES (?, ?, ?, ?)"
        );

        return $stmt->execute([
            $movieId,
            $hall,
            $startTime,
            $price
        ]);
    }

    // Delete a showtime if it has no bookings
    public function deleteShowtime(int $showtimeId): string
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM bookings WHERE showtime_id = ?"
        );

        $stmt->execute([$showtimeId]);

        if ((int) $stmt->fetchColumn() > 0) {
            return 'has_bookings';
        }

        $stmt = $this->conn->prepare(
            "DELETE FROM showtimes WHERE showtime_id = ?"
        );

        $stmt->execute([$showtimeId]);

        return $stmt->rowCount() > 0 ? 'deleted' : 'not_found';
    }

    // Get one showtime by ID
    public function getShowtimeById(int $showtimeId): array|false
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM showtimes WHERE showtime_id = ?"
        );

        $stmt->execute([$showtimeId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update showtime
    public function updateShowtime(
        int $showtimeId,
        int $movieId,
        string $hall,
        string $startTime,
        float $price
    ): bool {
        $stmt = $this->conn->prepare(
            "UPDATE showtimes
             SET movie_id = ?, hall = ?, start_time = ?, price = ?
             WHERE showtime_id = ?"
        );

        return $stmt->execute([
            $movieId,
            $hall,
            $startTime,
            $price,
            $showtimeId
        ]);
    }

}
