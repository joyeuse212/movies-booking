<?php

namespace App\Models;

use PDO;

class Dashboard
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Count all users
    public function countUsers(): int
    {
        return (int) $this->conn
            ->query("SELECT COUNT(*) FROM users")
            ->fetchColumn();
    }

    // Count all movies
    public function countMovies(): int
    {
        return (int) $this->conn
            ->query("SELECT COUNT(*) FROM movies")
            ->fetchColumn();
    }

    // Count all showtimes
    public function countShowtimes(): int
    {
        return (int) $this->conn
            ->query("SELECT COUNT(*) FROM showtimes")
            ->fetchColumn();
    }

    // Count all bookings
    public function countBookings(): int
    {
        return (int) $this->conn
            ->query("SELECT COUNT(*) FROM bookings")
            ->fetchColumn();
    }

    // Retrieve all dashboard statistics
    public function getStatistics(): array
    {
        return [
            'users' => $this->countUsers(),
            'movies' => $this->countMovies(),
            'showtimes' => $this->countShowtimes(),
            'bookings' => $this->countBookings()
        ];
    }
}
