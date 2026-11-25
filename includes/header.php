<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = $pageTitle ?? 'SCREENHOUSE | Movie Ticket Booking';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="/movies-booking/assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="container nav-container">
        <a href="/movies-booking/" class="logo">
            SCREEN<span>HOUSE</span>
        </a>

        <nav class="main-nav">
            <a href="/movies-booking/">Home</a>

            <?php if (isset($_SESSION['user_id'])): ?>

                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="/movies-booking/admin/dashboard">Dashboard</a>
                    <a href="/movies-booking/admin/movies">Movies</a>
                    <a href="/movies-booking/admin/showtimes">Showtimes</a>
                    <a href="/movies-booking/admin/bookings">Bookings</a>
                <?php else: ?>
                    <a href="/movies-booking/customer/dashboard">Dashboard</a>
                    <a href="/movies-booking/customer/movies">Movies</a>
                <?php endif; ?>

                <a href="/movies-booking/logout.php" class="nav-button">Logout</a>

            <?php else: ?>

                <a href="/movies-booking/login.php">Login</a>
                <a href="/movies-booking/register.php" class="nav-button">Join Now</a>

            <?php endif; ?>
        </nav>
    </div>
</header>

<main>
