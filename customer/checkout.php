<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$showtimeId = (int) ($_POST['showtime_id'] ?? $_GET['id'] ?? 0);
$seatNo = trim($_POST['seat_no'] ?? $_GET['seat'] ?? '');

$error = '';
$success = isset($_GET['success']);
$bookingId = (int) ($_GET['booking'] ?? 0);

// Get movie and showtime information
$stmt = $conn->prepare(
    "SELECT movies.title, showtimes.hall,
            showtimes.start_time, showtimes.price
     FROM showtimes
     JOIN movies ON showtimes.movie_id = movies.movie_id
     WHERE showtimes.showtime_id = ?
     AND showtimes.start_time >= NOW()"
);

$stmt->execute([$showtimeId]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

// Process simulated payment and save booking
if (isset($_POST['confirm_payment']) && $movie) {

    $paymentMethod = $_POST['payment_method'] ?? '';

    if (!preg_match('/^[A-C](10|[1-9])$/', $seatNo)) {

        $error = 'Invalid seat selection.';

    } elseif (!in_array($paymentMethod, ['mobile_money', 'card'], true)) {

        $error = 'Please select a payment method.';

    } else {

        try {

            $booking = $conn->prepare(
                "INSERT INTO bookings
                (showtime_id, customer_id, seat_no, status)
                VALUES (?, ?, ?, ?)"
            );

            $booking->execute([
                $showtimeId,
                $_SESSION['user_id'],
                $seatNo,
                'confirmed'
            ]);

            $newBookingId = $conn->lastInsertId();

            header(
                'Location: checkout.php?success=1&id=' .
                $showtimeId .
                '&seat=' . urlencode($seatNo) .
                '&booking=' . $newBookingId
            );
            exit;

        } catch (PDOException $e) {

            $error = 'This seat is already booked. Please choose another seat.';
        }
    }
}

$pageTitle = 'Payment | SCREENHOUSE';
$basePath = '../';

require_once __DIR__ . '/../includes/header.php';
?>

<section class="auth-section">

    <div class="auth-card payment-card">

        <?php if ($success): ?>

            <p class="eyebrow">Payment Successful!</p>

            <h1>Payment Successful!</h1>

            <p class="success-message">
                Your demo payment was successful and your booking is confirmed.
            </p>

            <div class="payment-summary">
                <p>Booking ID: <?= $bookingId ?></p>
                <p>Movie: <?= htmlspecialchars($movie['title'] ?? '') ?></p>
                <p>Seat: <?= htmlspecialchars($seatNo) ?></p>
            </div>

            <a href="my_bookings.php" class="auth-button">
                View My Bookings
            </a>

        <?php elseif (!$movie): ?>

            <h1>Invalid Booking</h1>

            <p>Please select a valid movie showtime and seat.</p>

            <a href="movies.php" class="auth-button">
                Back to Movies
            </a>

        <?php else: ?>
            <a href="seats.php?id=<?= $showtimeId ?>&seat=<?= urlencode($seatNo) ?>"
                    class="back-link">
                   ← Back to Seat Selection
             </a>
            <p class="eyebrow">SECURE CHECKOUT DEMO</p>

            <h1>Complete Your Payment</h1>

            <p>Review your booking and select a payment method.</p>

            <?php if ($error !== ''): ?>
                <p class="error-message">
                    <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>

            <div class="payment-summary">

                <h2>Booking Summary</h2>

                <p>
                    <strong>Movie:</strong>
                    <?= htmlspecialchars($movie['title']) ?>
                </p>

                <p>
                    <strong>Hall:</strong>
                    <?= htmlspecialchars($movie['hall']) ?>
                </p>

                <p>
                    <strong>Date:</strong>
                    <?= htmlspecialchars($movie['start_time']) ?>
                </p>

                <p>
                    <strong>Seat:</strong>
                    <?= htmlspecialchars($seatNo) ?>
                </p>

                <div class="payment-total">
                    Total Amount:
                    <?= number_format((float) $movie['price'], 2) ?> RWF
                </div>

            </div>

            <form method="POST" id="paymentForm">
                <input type="hidden" name="confirm_payment" value="1">
                <input type="hidden" name="showtime_id"
                       value="<?= $showtimeId ?>">

                <input type="hidden" name="seat_no"
                       value="<?= htmlspecialchars($seatNo) ?>">

                <h2>Select Payment Method</h2>

                <label class="payment-option">
                    <input type="radio" name="payment_method"
                           value="mobile_money" required>
                    Mobile Money (Demo)
                </label>

                <label class="payment-option">
                    <input type="radio" name="payment_method"
                           value="card" required>
                    Visa / Mastercard (Demo)
                </label>
                <button type="submit" class="auth-button" id="payButton">
                   Pay Now
                </button>
            </form>

        <?php endif; ?>

    </div>

</section>

<script>
const paymentForm = document.getElementById('paymentForm');

paymentForm?.addEventListener('submit', function(event) {

    event.preventDefault();

    const button = document.getElementById('payButton');

    button.textContent = 'Processing Payment...';
    button.disabled = true;

    setTimeout(() => {

        alert(
            'Payment Successful!\n\n' +
            'Your booking is confirmed.\n' +
            'Thank you for choosing SCREENHOUSE. ' +
            'Enjoy your movie!'
        );

        paymentForm.submit();

    }, 1500);

});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
