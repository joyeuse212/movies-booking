<?php

namespace App\Models;

use PDO;

class Booking
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Get movie and showtime information
    public function getShowtimeDetails(int $showtimeId): array|false
    {
        $stmt = $this->conn->prepare(
            "SELECT
                movies.title,
                showtimes.hall,
                showtimes.start_time,
                showtimes.price
             FROM showtimes
             JOIN movies
                ON showtimes.movie_id = movies.movie_id
             WHERE showtimes.showtime_id = ?
             AND showtimes.start_time >= NOW()"
        );

        $stmt->execute([$showtimeId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Save a confirmed booking
    public function createBooking(
        int $showtimeId,
        int $customerId,
        string $seatNo
    ): string {
        $stmt = $this->conn->prepare(
            "INSERT INTO bookings
            (showtime_id, customer_id, seat_no, status)
            VALUES (?, ?, ?, ?)"
        );

        $stmt->execute([
            $showtimeId,
            $customerId,
            $seatNo,
            'confirmed'
        ]);

        return $this->conn->lastInsertId();
    }

    // Get all bookings belonging to a customer
    public function getCustomerBookings(int $customerId): array
    {
        $stmt = $this->conn->prepare(
            "SELECT
                bookings.booking_id,
                bookings.seat_no,
                bookings.status,
                showtimes.hall,
                showtimes.start_time,
                showtimes.price,
                movies.title
             FROM bookings
             JOIN showtimes
                ON bookings.showtime_id = showtimes.showtime_id
             JOIN movies
                ON showtimes.movie_id = movies.movie_id
             WHERE bookings.customer_id = ?
             ORDER BY bookings.booking_id DESC"
        );

        $stmt->execute([$customerId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count all bookings belonging to a customer
    public function getCustomerBookingCount(int $customerId): int
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*)
             FROM bookings
             WHERE customer_id = ?"
        );

        $stmt->execute([$customerId]);

        return (int) $stmt->fetchColumn();
    }

    // Get the five most recent bookings
    public function getRecentCustomerBookings(int $customerId): array
    {
        $stmt = $this->conn->prepare(
            "SELECT
                bookings.booking_id,
                bookings.seat_no,
                bookings.status,
                showtimes.hall,
                showtimes.start_time,
                showtimes.price,
                movies.title
             FROM bookings
             INNER JOIN showtimes
                ON bookings.showtime_id = showtimes.showtime_id
             INNER JOIN movies
                ON showtimes.movie_id = movies.movie_id
             WHERE bookings.customer_id = ?
             ORDER BY bookings.booking_id DESC
             LIMIT 5"
        );

        $stmt->execute([$customerId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Get all bookings for admin
public function getAllBookings(): array
{
    $stmt = $this->conn->query(
        "SELECT
            bookings.booking_id,
            users.username,
            movies.title,
            showtimes.hall,
            showtimes.start_time,
            bookings.seat_no,
            showtimes.price,
            bookings.status
         FROM bookings
         JOIN users
            ON bookings.customer_id = users.user_id
         JOIN showtimes
            ON bookings.showtime_id = showtimes.showtime_id
         JOIN movies
            ON showtimes.movie_id = movies.movie_id
         ORDER BY bookings.booking_id DESC"
    );

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
