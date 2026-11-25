<?php require_once __DIR__ . '/../../../../includes/header.php'; ?>

<section class="auth-section">
    <div class="auth-card payment-card">

        <?php if ($success && $movie): ?>

            <p class="eyebrow">Payment Successful!</p>

            <h1>Payment Successful!</h1>

            <p class="success-message">
                Your demo payment was successful and your booking is confirmed.
            </p>

            <div class="payment-summary">
                <p>Booking ID: <?= $bookingId ?></p>

                <p>
                    Movie:
                    <?= htmlspecialchars($movie['title']) ?>
                </p>

                <p>
                    Seat:
                    <?= htmlspecialchars($seatNo) ?>
                </p>
            </div>
            <a href="/movies-booking/customer/my-bookings">
              View My Bookings
            </a> 

        <?php elseif (!$movie): ?>

            <h1>Invalid Booking</h1>

            <p>Please select a valid movie showtime and seat.</p>

            <a href="../movies" class="auth-button">
                Back to Movies
            </a>

        <?php else: ?>

            <a href="../seats?id=<?= $showtimeId ?>&seat=<?= urlencode($seatNo) ?>"
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

            <form method="POST"
                  action="/movies-booking/customer/checkout"
                  id="paymentForm">

                <!-- ADDED CSRF TOKEN HERE -->
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

                <input type="hidden" name="confirm_payment" value="1">

                <input type="hidden"
                       name="showtime_id"
                       value="<?= $showtimeId ?>">

                <input type="hidden"
                       name="seat_no"
                       value="<?= htmlspecialchars($seatNo) ?>">

                <h2>Select Payment Method</h2>

                <label class="payment-option">
                    <input type="radio"
                           name="payment_method"
                           value="mobile_money"
                           required>
                    Mobile Money (Demo)
                </label>

                <label class="payment-option">
                    <input type="radio"
                           name="payment_method"
                           value="card"
                           required>
                    Visa / Mastercard (Demo)
                </label>

                <button type="submit"
                        class="auth-button"
                        id="payButton">
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

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>