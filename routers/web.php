<?php
use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\MovieController;
use App\Controllers\SeatController;
use App\Controllers\BookingController;
use App\Controllers\DashboardController;
use App\Controllers\ShowtimeController;

$router = new Router();

// ================= HOMEPAGE =================

$router->get('/', function () use ($conn) {
    $controller = new HomeController($conn);
    $controller->index();
});

// ================= CUSTOMER ROUTES =================

// Customer movies page
$router->get('/customer/movies', function () use ($conn) {
    $controller = new MovieController($conn);
    $controller->index();
});

// Customer movie details page
$router->get('/customer/movie-details', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('customer');

    $controller = new MovieController($conn);
    $controller->details();
});

// Customer seat selection page
$router->get('/customer/seats', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('customer');

    $controller = new SeatController($conn);
    $controller->index();
});

// Customer checkout page (GET)
$router->get('/customer/checkout', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('customer');

    $controller = new BookingController($conn);
    $controller->index();
});

// Process checkout (POST)
$router->post('/customer/checkout', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('customer');

    $controller = new BookingController($conn);
    $controller->store();
});

// Customer My Bookings page
$router->get('/customer/my-bookings', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('customer');

    $controller = new BookingController($conn);
    $controller->myBookings();
});

// Customer Dashboard
$router->get('/customer/dashboard', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('customer');

    $controller = new BookingController($conn);
    $controller->dashboard();
});

// ================= ADMIN DASHBOARD =================

$router->get('/admin/dashboard', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('admin');

    $controller = new DashboardController($conn);
    $controller->index();
});

// ================= ADMIN SHOWTIME ROUTES =================

// Display all showtimes
$router->get('/admin/showtimes', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('admin');

    $controller = new ShowtimeController($conn);
    $controller->index();
});
// ================= ADMIN BOOKINGS ROUTES =================

// Display all customer bookings
$router->get('/admin/bookings', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('admin');

    $controller = new BookingController($conn);
    $controller->adminIndex();
});

// Add showtime
$router->post('/admin/showtimes/store', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('admin');

    $controller = new ShowtimeController($conn);
    $controller->store();
});

// Delete showtime
$router->post('/admin/showtimes/delete', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('admin');

    $controller = new ShowtimeController($conn);
    $controller->delete();
});

// Display Edit Showtime form
$router->get('/admin/showtimes/edit', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('admin');

    $controller = new ShowtimeController($conn);
    $controller->edit();
});

// Update showtime
$router->post('/admin/showtimes/update', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('admin');

    $controller = new ShowtimeController($conn);
    $controller->update();
});

// ================= ADMIN MOVIE ROUTES =================

// Display all movies
$router->get('/admin/movies', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('admin');

    $controller = new MovieController($conn);
    $controller->adminIndex();
});

// Display Add Movie form
$router->get('/admin/movies/create', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('admin');

    $controller = new MovieController($conn);
    $controller->create();
});

// Save new movie
$router->post('/admin/movies/store', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('admin');

    $controller = new MovieController($conn);
    $controller->store();
});

// Display Edit Movie form
$router->get('/admin/movies/edit', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('admin');

    $controller = new MovieController($conn);
    $controller->edit();
});

// Update movie
$router->post('/admin/movies/update', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('admin');

    $controller = new MovieController($conn);
    $controller->update();
});

// Delete movie
$router->post('/admin/movies/delete', function () use ($conn) {
    require_once __DIR__ . '/../includes/auth.php';
    requireRole('admin');

    $controller = new MovieController($conn);
    $controller->delete();
});
// ================= API ROUTES =================
// JSON API for Live Search
$router->get('/api/movies/search', function () use ($conn) {
    $controller = new MovieController($conn);
    $controller->searchJson();
});