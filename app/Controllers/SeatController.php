<?php

namespace App\Controllers;

use App\Models\Seat;
use PDO;

class SeatController
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Display seat selection page
    public function index(): void
    {
        $showtimeId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $selectedSeat = $_GET['seat'] ?? '';

        $seatModel = new Seat($this->conn);

        $showtime = null;
        $bookedSeats = [];

        if ($showtimeId > 0) {
            $showtime = $seatModel->getShowtimeDetails($showtimeId);

            if ($showtime) {
                $bookedSeats = $seatModel->getBookedSeats($showtimeId);
            }
        }

        $pageTitle = 'Choose Your Seat | SCREENHOUSE';
        $basePath = '../';

        $movieImages = [
            'Avatar' => 'avatar.jpg',
            'John Wick' => 'john-wick.jpeg',
            'The Batman' => 'the-batman.jpg',
            'Doinglife' => 'doinglife.jpg',
            'Gradiator' => 'gradiator.jpg',
            'Wicked' => 'wicked.jpg'
        ];

        require_once __DIR__ . '/../Views/customer/seats/index.php';
    }
}
