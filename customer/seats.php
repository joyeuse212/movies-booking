<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireRole('customer');

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Choose Your Seat | SCREENHOUSE';
$basePath = '../';

$showtimeId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Get movie and screening information
$stmt = $conn->prepare(
    "SELECT
        showtimes.showtime_id,
        showtimes.movie_id,
        showtimes.hall,
        showtimes.start_time,
        showtimes.price,
        movies.title
     FROM showtimes
     INNER JOIN movies
        ON showtimes.movie_id = movies.movie_id
     WHERE showtimes.showtime_id = ?
     AND showtimes.start_time >= NOW()"
);

$stmt->execute([$showtimeId]);
$showtime = $stmt->fetch(PDO::FETCH_ASSOC);

// Get seats already booked
$bookedSeats = [];

if ($showtime) {
    $bookedStmt = $conn->prepare(
        "SELECT seat_no
         FROM bookings
         WHERE showtime_id = ?
         AND status <> 'cancelled'"
    );

    $bookedStmt->execute([$showtimeId]);
    $bookedSeats = $bookedStmt->fetchAll(PDO::FETCH_COLUMN);
}

// Cinema seat arrangement
$rows = ['A', 'B', 'C'];
$seatsPerRow = 6;

require_once __DIR__ . '/../includes/header.php';
?>

<section class="movie-details-page">

    <div class="container">

        <p class="auth-switch">
            <a href="movies.php">← Back to Movies</a>
        </p>

        <?php if (!$showtime): ?>

            <div class="empty-state">
                <h2>Showtime Not Available</h2>
                <p>
                    This screening does not exist or is no longer available.
                </p>
                <a href="movies.php" class="primary-button">
                    Browse Movies
                </a>
            </div>

        <?php else: ?>

            <div class="movies-page-heading">

                <p class="eyebrow">RESERVE YOUR SEAT</p>

                <h1>
                    <?= htmlspecialchars($showtime['title']) ?>
                </h1>

                <p>
                    <?= date('d M Y, h:i A', strtotime($showtime['start_time'])) ?>
                </p>

                <p>
                    Hall: <?= htmlspecialchars($showtime['hall']) ?>
                </p>

                <p>
                    Ticket Price:
                    <?= number_format((float) $showtime['price'], 2) ?> RWF
                </p>

            </div>

            <div class="showtimes-section">

                <h2>Select Your Seat</h2>

                <div class="seat-screen">
                    <p>SCREEN</p>
                </div>

                <form method="POST" action="confirm_booking.php">

                    <input
                        type="hidden"
                        name="showtime_id"
                        value="<?= (int) $showtime['showtime_id'] ?>"
                    >

                    <div class="seat-layout">

                        <?php foreach ($rows as $row): ?>

                            <div class="seat-row">

                                <strong><?= htmlspecialchars($row) ?></strong>

                                <?php for ($number = 1; $number <= $seatsPerRow; $number++): ?>

                                    <?php
                                    $seatNumber = $row . $number;
                                    $isBooked = in_array($seatNumber, $bookedSeats, true);
                                    ?>

                                    <label class="seat-option">

                                        <input
                                            type="radio"
                                            name="seat_no"
                                            value="<?= htmlspecialchars($seatNumber) ?>"
                                            <?= $isBooked ? 'disabled' : '' ?>
                                            required
                                        >

                                        <span class="<?= $isBooked ? 'seat booked' : 'seat available' ?>">
                                            <?= htmlspecialchars($seatNumber) ?>
                                        </span>

                                    </label>

                                <?php endfor; ?>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <div class="seat-legend">

                        <span>
                            <span class="legend-box available"></span>
                            Available
                        </span>

                        <span>
                            <span class="legend-box booked"></span>
                            Booked
                        </span>

                    </div>

                    <?php if (count($bookedSeats) >= count($rows) * $seatsPerRow): ?>

                        <p class="error-message">
                            Sorry, all seats for this screening are booked.
                        </p>

                    <?php else: ?>

                        <button type="submit" class="primary-button">
                            Confirm Selected Seat
                        </button>

                    <?php endif; ?>

                </form>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
