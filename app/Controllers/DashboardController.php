<?php

namespace App\Controllers;

use App\Models\Dashboard;
use PDO;

class DashboardController
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // ADMIN: Display dashboard
    public function index(): void
    {
        $dashboardModel = new Dashboard($this->conn);

        $stats = $dashboardModel->getStatistics();

        $users = $stats['users'];
        $movies = $stats['movies'];
        $showtimes = $stats['showtimes'];
        $bookings = $stats['bookings'];

        $pageTitle = 'Admin Dashboard | SCREENHOUSE';
        $basePath = '../';

        require __DIR__ . '/../Views/admin/dashboard.php';
    }
}
