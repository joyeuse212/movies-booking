<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'View Bookings | SCREENHOUSE';
$basePath = '../';

// Get all customer bookings
$stmt = $conn->query(
    "SELECT bookings.booking_id,
            users.username,
            movies.title,
            showtimes.hall,
            showtimes.start_time,
            bookings.seat_no,
            showtimes.price,
            bookings.status
     FROM bookings
     JOIN users ON bookings.customer_id = users.user_id
     JOIN showtimes ON bookings.showtime_id = showtimes.showtime_id
     JOIN movies ON showtimes.movie_id = movies.movie_id
     ORDER BY bookings.booking_id DESC"
);

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<section class="dashboard-section">
    <div class="container">

        <p class="auth-switch">
            <a href="dashboard.php">← Back to Dashboard</a>
        </p>

        <p class="eyebrow">BOOKING MANAGEMENT</p>
        <h1>Customer Bookings</h1>
        <p>View all movie ticket bookings made by customers.</p>

        <div class="booking-table">
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Movie</th>
                        <th>Hall</th>
                        <th>Date and Time</th>
                        <th>Seat</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (count($bookings) > 0): ?>

                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?= (int) $booking['booking_id'] ?></td>

                                <td><?= htmlspecialchars($booking['username']) ?></td>

                                <td><?= htmlspecialchars($booking['title']) ?></td>

                                <td><?= htmlspecialchars($booking['hall']) ?></td>

                                <td><?= htmlspecialchars($booking['start_time']) ?></td>

                                <td><?= htmlspecialchars($booking['seat_no']) ?></td>

                                <td><?= number_format((float) $booking['price'], 2) ?></td>

                                <td><?= htmlspecialchars($booking['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8">No bookings have been made yet.</td>
                        </tr>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
