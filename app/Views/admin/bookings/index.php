<?php require_once __DIR__ . '/../../../../includes/header.php'; ?>

<section class="dashboard-section">
    <div class="container">

        <p class="auth-switch">
            <a href="/movies-booking/admin/dashboard">
                ← Back to Dashboard
            </a>
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
                                <td>
                                    <?= (int) $booking['booking_id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($booking['username']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($booking['title']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($booking['hall']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($booking['start_time']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($booking['seat_no']) ?>
                                </td>

                                <td>
                                    <?= number_format((float) $booking['price'], 2) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($booking['status']) ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8">
                                No bookings have been made yet.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>
