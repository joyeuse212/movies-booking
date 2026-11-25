<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireRole('customer');

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Booking Confirmation | SCREENHOUSE';
$basePath = '../';

$error = '';
$success = false;

$showtimeId = 0;
$seatNo = '';
$movieTitle = '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    $error = 'Please select a seat before confirming your booking.';

} else {

    $showtimeId = (int) ($_POST['showtime_id'] ?? 0);
    $seatNo = trim($_POST['seat_no'] ?? '');
    $customerId = (int) $_SESSION['user_id'];

    if ($showtimeId <= 0 || !preg_match('/^[A-C][1-6]$/', $seatNo)) {

        $error = 'Invalid showtime or seat selection.';

    } else {

        try {

            $conn->beginTransaction();

            // Check that the showtime exists and is still upcoming.
            // Lock the showtime row while processing this booking.
            $showtimeStmt = $conn->prepare(
                "SELECT showtime_id
                 FROM showtimes
                 WHERE showtime_id = ?
                 AND start_time >= NOW()
                 FOR UPDATE"
            );

            $showtimeStmt->execute([$showtimeId]);
            $availableShowtime = $showtimeStmt->fetch(PDO::FETCH_ASSOC);

            if (!$availableShowtime) {

                $error = 'This showtime is no longer available.';

            } else {

                // Check whether another customer has booked this seat.
                $checkStmt = $conn->prepare(
                    "SELECT booking_id
                     FROM bookings
                     WHERE showtime_id = ?
                     AND seat_no = ?
                     AND status <> 'cancelled'"
                );

                $checkStmt->execute([$showtimeId, $seatNo]);

                if ($checkStmt->fetch()) {

                    $error = 'Sorry, this seat has already been booked. Please choose another seat.';

                } else {

                    // Save the booking.
                    $insertStmt = $conn->prepare(
                        "INSERT INTO bookings
                         (showtime_id, customer_id, seat_no, status)
                         VALUES (?, ?, ?, ?)"
                    );

                    $insertStmt->execute([
                        $showtimeId,
                        $customerId,
                        $seatNo,
                        'confirmed'
                    ]);

                    $bookingId = (int) $conn->lastInsertId();

                    // Get the movie title for the confirmation page.
                    $movieStmt = $conn->prepare(
                        "SELECT movies.title
                         FROM showtimes
                         JOIN movies
                         ON showtimes.movie_id = movies.movie_id
                         WHERE showtimes.showtime_id = ?"
                    );

                    $movieStmt->execute([$showtimeId]);
                    $movieTitle = (string) $movieStmt->fetchColumn();

                    $success = true;
                }
            }

            $conn->commit();

        } catch (Throwable $e) {

            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            $error = 'Something went wrong while processing your booking. Please try again.';
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="auth-section">

    <div class="auth-card">

        <?php if ($success): ?>

            <p class="eyebrow">BOOKING SUCCESSFUL</p>

            <h1>Your Seat Is Confirmed!</h1>

            <p class="auth-description">
                Your movie ticket has been booked successfully.
            </p>

            <div class="success-message">
                Booking confirmed!
            </div>

            <p>
                <strong>Booking ID:</strong>
                <?= $bookingId ?>
            </p>

            <p>
                <strong>Movie:</strong>
                <?= htmlspecialchars($movieTitle) ?>
            </p>

            <p>
                <strong>Seat:</strong>
                <?= htmlspecialchars($seatNo) ?>
            </p>

            <div class="auth-switch">
                <a href="my_bookings.php" class="primary-button">
                    View My Bookings
                </a>
            </div>

        <?php else: ?>

            <p class="eyebrow">BOOKING NOT COMPLETED</p>

            <h1>Booking Unsuccessful</h1>

            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>

            <a
                href="movies.php"
                class="primary-button"
            >
                Browse Movies
            </a>

        <?php endif; ?>

    </div>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
