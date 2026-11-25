<?php

namespace App\Models;

use PDO;

class Movie
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // READ: Retrieve all movies
    public function getAllMovies(): array
    {
        $stmt = $this->conn->query(
            "SELECT movie_id, title, genre, duration
             FROM movies
             ORDER BY movie_id DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ: Retrieve one movie
    public function getMovieById(int $movieId): array|false
    {
        $stmt = $this->conn->prepare(
            "SELECT movie_id, title, genre, duration
             FROM movies
             WHERE movie_id = ?"
        );

        $stmt->execute([$movieId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CREATE: Add a movie
    public function createMovie(
        string $title,
        string $genre,
        int $duration
    ): bool {
        $stmt = $this->conn->prepare(
            "INSERT INTO movies (title, genre, duration)
             VALUES (?, ?, ?)"
        );

        return $stmt->execute([
            $title,
            $genre,
            $duration
        ]);
    }

    // UPDATE: Edit a movie
    public function updateMovie(
        int $movieId,
        string $title,
        string $genre,
        int $duration
    ): bool {
        $stmt = $this->conn->prepare(
            "UPDATE movies
             SET title = ?, genre = ?, duration = ?
             WHERE movie_id = ?"
        );

        return $stmt->execute([
            $title,
            $genre,
            $duration,
            $movieId
        ]);
    }

    // READ: Retrieve upcoming showtimes
    public function getUpcomingShowtimes(int $movieId): array
    {
        $stmt = $this->conn->prepare(
            "SELECT showtime_id, hall, start_time, price
             FROM showtimes
             WHERE movie_id = ?
             AND start_time >= NOW()
             ORDER BY start_time ASC"
        );

        $stmt->execute([$movieId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // DELETE: Remove a movie
    public function deleteMovie(int $movieId): bool
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM movies WHERE movie_id = ?"
        );

        $stmt->execute([$movieId]);

        return $stmt->rowCount() > 0;
    }

    // API: Search movies by title or genre
    public function searchMovies(string $query): array
    {
        $stmt = $this->conn->prepare(
            "SELECT movie_id, title, genre, duration
             FROM movies
             WHERE title LIKE ? OR genre LIKE ?
             ORDER BY title ASC"
        );

        $searchTerm = '%' . $query . '%';
        $stmt->execute([$searchTerm, $searchTerm]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}