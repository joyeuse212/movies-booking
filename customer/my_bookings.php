<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireRole('customer');

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'My Bookings | SCREENHOUSE';
$basePath = '../';

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT bookings.booking_id, bookings.seat_no,
            bookings.status, showtimes.hall,
            showtimes.start_time, showtimes.price,
            movies.title
     FROM bookings
     JOIN showtimes ON bookings.showtime_id = showtimes.showtime_id
     JOIN movies ON showtimes.movie_id = movies.movie_id
     WHERE bookings.customer_id = ?
     ORDER BY bookings.booking_id DESC"
);

$stmt->execute([$userId]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<section class="dashboard-section">

    <div class="container">

        <p class="auth-switch">
            <a href="dashboard.php">← Back to Dashboard</a>
        </p>

        <div class="dashboard-heading">
            <div>
                <p class="eyebrow">YOUR TICKETS</p>
                <h2>My Bookings</h2>
            </div>

            <a href="movies.php" class="primary-button">
                Browse Movies
            </a>
        </div>

        <?php if (count($bookings) > 0): ?>

            <div class="booking-table-wrapper">

                <table class="booking-table">

                    <thead>
                        <tr>
                            <th>Movie</th>
                            <th>Date</th>
                            <th>Hall</th>
                            <th>Seat</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($bookings as $booking): ?>

                            <tr>
                                <td><?= htmlspecialchars($booking['title']) ?></td>

                                <td>
                                    <?= date('d M Y, H:i', strtotime($booking['start_time'])) ?>
                                </td>

                                <td><?= htmlspecialchars($booking['hall']) ?></td>

                                <td><?= htmlspecialchars($booking['seat_no']) ?></td>

                                <td><?= number_format((float) $booking['price'], 2) ?></td>

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

                <p>You have not booked any movie tickets.</p>

                <a href="movies.php" class="primary-button">
                    Explore Movies
                </a>
            </div>

        <?php endif; ?>

    </div>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
