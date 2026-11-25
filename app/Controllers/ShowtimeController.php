<?php

namespace App\Controllers;

use App\Models\Showtime;
use PDO;

class ShowtimeController
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // ADMIN: Display all showtimes
    public function index(): void
    {
        $showtimeModel = new Showtime($this->conn);

        $movies = $showtimeModel->getAllMovies();
        $showtimes = $showtimeModel->getAllShowtimes();

        $pageTitle = 'Manage Showtimes | SCREENHOUSE';
        $basePath = '../';

        $error = '';
        $success = '';

        $status = $_GET['status'] ?? '';

        if ($status === 'added') {
            $success = 'Showtime added successfully!';
        } elseif ($status === 'deleted') {
            $success = 'Showtime deleted successfully!';
        } elseif ($status === 'has_bookings') {
            $error = 'This showtime cannot be deleted because it has existing bookings.';
        } elseif ($status === 'not_found') {
            $error = 'Showtime not found.';
        } elseif ($status === 'updated') {
            $success = 'Showtime updated successfully!';
        } elseif ($status === 'invalid') {
            $error = 'Please enter all showtime information correctly.';
        } elseif ($status === 'csrf_error') {
            $error = 'Security token invalid. Please try again.';
        }

        require __DIR__ . '/../Views/admin/showtimes/index.php';
    }

    // ADMIN: Add a new showtime
    public function store(): void
    {
        // 1. Verify CSRF Token
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            header('Location: /movies-booking/admin/showtimes?status=csrf_error');
            exit;
        }

        $movieId = (int) ($_POST['movie_id'] ?? 0);
        $hall = trim($_POST['hall'] ?? '');
        $startTime = trim($_POST['start_time'] ?? '');
        $priceInput = $_POST['price'] ?? '';
        $price = filter_var($priceInput, FILTER_VALIDATE_FLOAT);

        // 2. Server-side validation
        if (
            $movieId <= 0 ||
            strlen($hall) < 2 ||
            $startTime === '' ||
            $price === false ||
            $price <= 0
        ) {
            header('Location: /movies-booking/admin/showtimes?status=invalid');
            exit;
        }

        $startTime = str_replace('T', ' ', $startTime);

        $showtimeModel = new Showtime($this->conn);

        try {
            if ($showtimeModel->createShowtime(
                $movieId,
                $hall,
                $startTime,
                (float) $price
            )) {
                header('Location: /movies-booking/admin/showtimes?status=added');
                exit;
            }
        } catch (\PDOException $e) {
            // Log error internally if needed
        }

        header('Location: /movies-booking/admin/showtimes?status=invalid');
        exit;
    }

    // ADMIN: Delete a showtime
    public function delete(): void
    {
        // 1. Verify CSRF Token
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            header('Location: /movies-booking/admin/showtimes?status=csrf_error');
            exit;
        }

        $showtimeId = (int) ($_POST['showtime_id'] ?? 0);

        if ($showtimeId <= 0) {
            header('Location: /movies-booking/admin/showtimes?status=not_found');
            exit;
        }

        $showtimeModel = new Showtime($this->conn);

        try {
            $status = $showtimeModel->deleteShowtime($showtimeId);
        } catch (\PDOException $e) {
            $status = 'has_bookings';
        }

        header('Location: /movies-booking/admin/showtimes?status=' . $status);
        exit;
    }

    // ADMIN: Display Edit Showtime form
    public function edit(): void
    {
        $showtimeId = (int) ($_GET['id'] ?? 0);

        $showtimeModel = new Showtime($this->conn);
        $showtime = $showtimeModel->getShowtimeById($showtimeId);

        if (!$showtime) {
            http_response_code(404);
            echo 'Showtime not found.';
            return;
        }

        $movies = $showtimeModel->getAllMovies();

        $formattedDate = date(
            'Y-m-d\TH:i',
            strtotime($showtime['start_time'])
        );

        $pageTitle = 'Edit Showtime | SCREENHOUSE';
        $basePath = '../';
        $error = '';

        require __DIR__ . '/../Views/admin/showtimes/edit.php';
    }

    // ADMIN: Update Showtime
    public function update(): void
    {
        // 1. Verify CSRF Token
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            header('Location: /movies-booking/admin/showtimes?status=csrf_error');
            exit;
        }

        $showtimeId = (int) ($_POST['showtime_id'] ?? 0);
        $movieId = (int) ($_POST['movie_id'] ?? 0);
        $hall = trim($_POST['hall'] ?? '');
        $startTime = trim($_POST['start_time'] ?? '');
        $priceInput = $_POST['price'] ?? '';
        $price = filter_var($priceInput, FILTER_VALIDATE_FLOAT);

        $showtimeModel = new Showtime($this->conn);
        $showtime = $showtimeModel->getShowtimeById($showtimeId);

        if (!$showtime) {
            http_response_code(404);
            echo 'Showtime not found.';
            return;
        }

        // 2. Server-side validation
        if (
            $movieId <= 0 ||
            strlen($hall) < 2 ||
            $startTime === '' ||
            $price === false ||
            $price <= 0
        ) {
            $error = 'Please enter all showtime information correctly.';

            $movies = $showtimeModel->getAllMovies();

            $showtime['movie_id'] = $movieId;
            $showtime['hall'] = $hall;
            $showtime['start_time'] = $startTime;
            $showtime['price'] = $priceInput;

            $formattedDate = $startTime;

            $pageTitle = 'Edit Showtime | SCREENHOUSE';
            $basePath = '../';

            require __DIR__ . '/../Views/admin/showtimes/edit.php';
            return;
        }

        $startTime = str_replace('T', ' ', $startTime);

        try {
            $showtimeModel->updateShowtime(
                $showtimeId,
                $movieId,
                $hall,
                $startTime,
                (float) $price
            );

            header('Location: /movies-booking/admin/showtimes?status=updated');
            exit;

        } catch (\PDOException $e) {
            $error = 'Unable to update showtime. Please try again.';

            $movies = $showtimeModel->getAllMovies();

            $formattedDate = date(
                'Y-m-d\TH:i',
                strtotime($showtime['start_time'])
            );

            $pageTitle = 'Edit Showtime | SCREENHOUSE';
            $basePath = '../';

            require __DIR__ . '/../Views/admin/showtimes/edit.php';
        }
    }
}