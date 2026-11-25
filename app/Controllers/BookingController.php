<?php

namespace App\Controllers;

use App\Models\Booking;
use PDO;
use PDOException;

class BookingController
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Display checkout page
    public function index(): void
    {
        $showtimeId = (int) ($_GET['id'] ?? 0);
        $seatNo = trim($_GET['seat'] ?? '');

        $success = isset($_GET['success']);
        $bookingId = (int) ($_GET['booking'] ?? 0);
        $error = '';

        $bookingModel = new Booking($this->conn);

        $movie = $showtimeId > 0
            ? $bookingModel->getShowtimeDetails($showtimeId)
            : false;

        $pageTitle = 'Payment | SCREENHOUSE';
        $basePath = '../';

        require __DIR__ . '/../Views/customer/checkout/index.php';
    }

    // Process demo payment and save booking
    public function store(): void
    {
        // 1. Verify CSRF Token
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            // Redirect back to seats or show error
            header('Location: /movies-booking/customer/movies');
            exit;
        }

        $showtimeId = (int) ($_POST['showtime_id'] ?? 0);
        $seatNo = trim($_POST['seat_no'] ?? '');
        $paymentMethod = $_POST['payment_method'] ?? '';

        $bookingModel = new Booking($this->conn);

        $movie = $showtimeId > 0
            ? $bookingModel->getShowtimeDetails($showtimeId)
            : false;

        $error = '';

        if (!$movie) {
            $error = 'Invalid movie showtime.';
        } elseif (!preg_match('/^[A-C](10|[1-9])$/', $seatNo)) {
            $error = 'Invalid seat selection.';
        } elseif (!in_array($paymentMethod, ['mobile_money', 'card'], true)) {
            $error = 'Please select a payment method.';
        } else {
            try {
                $bookingId = $bookingModel->createBooking(
                    $showtimeId,
                    (int) $_SESSION['user_id'],
                    $seatNo
                );

                header(
                    'Location: /movies-booking/customer/checkout?success=1&id=' .
                    $showtimeId .
                    '&seat=' . urlencode($seatNo) .
                    '&booking=' . $bookingId
                );
                exit;

            } catch (PDOException $e) {
                $error = 'This seat is already booked. Please choose another seat.';
            }
        }

        $success = false;
        $bookingId = 0;
        $pageTitle = 'Payment | SCREENHOUSE';
        $basePath = '../';

        require __DIR__ . '/../Views/customer/checkout/index.php';
    }

    // Display bookings belonging to the logged-in customer
    public function myBookings(): void
    {
        $userId = (int) $_SESSION['user_id'];

        $bookingModel = new Booking($this->conn);

        $bookings = $bookingModel->getCustomerBookings($userId);

        $pageTitle = 'My Bookings | SCREENHOUSE';
        $basePath = '../';
        require __DIR__ . '/../Views/customer/bookings/index.php';
    }
  
    // Display the customer dashboard
    public function dashboard(): void
    {
        $userId = (int) $_SESSION['user_id'];
        $username = $_SESSION['username'];

        $bookingModel = new Booking($this->conn);

        $totalBookings = $bookingModel->getCustomerBookingCount($userId);

        $recentBookings = $bookingModel->getRecentCustomerBookings($userId);

        $pageTitle = 'Customer Dashboard | SCREENHOUSE';
        $basePath = '../';

        require __DIR__ . '/../Views/customer/dashboard/index.php';
    }

    // ADMIN: Display all customer bookings
    public function adminIndex(): void
    {
        $bookingModel = new Booking($this->conn);

        $bookings = $bookingModel->getAllBookings();

        $pageTitle = 'View Bookings | SCREENHOUSE';
        $basePath = '../';

        require __DIR__ . '/../Views/admin/bookings/index.php';
    }
}