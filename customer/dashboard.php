<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireRole('customer');

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Customer Dashboard | SCREENHOUSE';
$basePath = '../';

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Count this customer's bookings
$countStmt = $conn->prepare(
    "SELECT COUNT(*) FROM bookings WHERE customer_id = ?"
);

$countStmt->execute([$userId]);

$totalBookings = $countStmt->fetchColumn();

// Get this customer's recent bookings
$bookingStmt = $conn->prepare(
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

$bookingStmt->execute([$userId]);

$recentBookings = $bookingStmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<section class="dashboard-section">

    <div class="container">

        <div class="dashboard-welcome">
            <div>
                <p class="eyebrow">CUSTOMER DASHBOARD</p>

                <h1>
                    Welcome back,
                    <?= htmlspecialchars($username) ?>!
                </h1>

                <p>
                    Your next cinema experience is waiting for you.
                </p>
            </div>

            <a href="movies.php" class="primary-button">
                Browse Movies
            </a>
        </div>

        <div class="dashboard-stats">

            <div class="stat-card">
                <div class="stat-icon">
                    <span>🎟️</span>
                </div>

                <div>
                    <p>Total Bookings</p>
                    <h2><?= (int) $totalBookings ?></h2>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <span>🎬</span>
                </div>

                <div>
                    <p>Movie Collection</p>
                    <h2>Explore</h2>
                </div>
            </div>

        </div>

        <div class="dashboard-heading">
            <div>
                <p class="eyebrow">YOUR ACTIVITY</p>
                <h2>Recent Bookings</h2>
            </div>

            <a href="my_bookings.php" class="text-link">
                View All Bookings →
            </a>
        </div>

        <?php if (count($recentBookings) > 0): ?>

            <div class="booking-table-wrapper">

                <table class="booking-table">

                    <thead>
                        <tr>
                            <th>Movie</th>
                            <th>Showtime</th>
                            <th>Hall</th>
                            <th>Seat</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($recentBookings as $booking): ?>

                            <tr>
                                <td>
                                    <?= htmlspecialchars($booking['title']) ?>
                                </td>

                                <td>
                                    <?= date(
                                        'd M Y, H:i',
                                        strtotime($booking['start_time'])
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($booking['hall']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($booking['seat_no']) ?>
                                </td>

                                <td>
                                    <?= number_format((float) $booking['price'], 2) ?>
                                </td>

                                <td>
                                    <span class="booking-status">
                                        <?= htmlspecialchars($booking['status']) ?>
                                    </span>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="empty-state">
                <h3>No Bookings Yet</h3>

                <p>
                    You have not booked any movie tickets yet.
                    Explore our movies and book your first seat!
                </p>

                <a href="movies.php" class="primary-button">
                    Explore Movies
                </a>
            </div>

        <?php endif; ?>

    </div>

</section>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>
