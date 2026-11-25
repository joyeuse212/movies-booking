
<?php require_once __DIR__ . '/../../../../includes/header.php'; ?>

<section class="seats-page">

    <div class="container">

        <?php if (!$showtime): ?>

            <div class="empty-state">
                <h2>Showtime Not Found</h2>
                <p>This screening does not exist or has already passed.</p>

                <a href="/movies-booking/customer/movies"
                   class="primary-button">
                    Back to Movies
                </a>
            </div>

        <?php else: ?>

            <p class="auth-switch">
                <a href="/movies-booking/customer/movie-details?id=<?= (int) $showtime['movie_id'] ?>">
                    ← Back to Movie Details
                </a>
            </p>

            <div class="seats-heading">
                <p class="eyebrow">BOOK YOUR EXPERIENCE</p>
                <h1>Choose Your Seat</h1>

                <p>
                    Select your preferred seat for
                    <?= htmlspecialchars($showtime['title']) ?>.
                </p>
            </div>

            <?php
            $image = $movieImages[$showtime['title']] ?? '';
            ?>

            <div class="screening-info">

                <?php if ($image !== ''): ?>
                    <div class="screening-poster">
                        <img
                            src="<?= $basePath ?>assets/images/<?= htmlspecialchars($image) ?>"
                            alt="<?= htmlspecialchars($showtime['title']) ?>"
                        >
                    </div>
                <?php endif; ?>

                <div class="screening-details">

                    <h2><?= htmlspecialchars($showtime['title']) ?></h2>

                    <p>Genre: <?= htmlspecialchars($showtime['genre']) ?></p>

                    <p>
                        Duration: <?= (int) $showtime['duration'] ?> minutes
                    </p>

                    <p>Hall: <?= htmlspecialchars($showtime['hall']) ?></p>

                    <p>
                        Date:
                        <?= date('d M Y, H:i', strtotime($showtime['start_time'])) ?>
                    </p>

                    <p class="ticket-price">
                        Ticket Price:
                        <?= number_format((float) $showtime['price'], 2) ?> RWF
                    </p>

                </div>

            </div>

            <div class="seat-selection-card">

                <div class="cinema-screen">SCREEN</div>

                <div class="seat-legend">
                    <span><i class="legend-seat available"></i>Available</span>
                    <span><i class="legend-seat selected"></i>Selected</span>
                    <span><i class="legend-seat booked"></i>Booked</span>
                </div>

                <form method="POST"
                      action="/movies-booking/customer/checkout">

                    <input
                        type="hidden"
                        name="showtime_id"
                        value="<?= (int) $showtime['showtime_id'] ?>"
                    >

                    <div class="seat-layout">

                        <?php foreach (['A', 'B', 'C'] as $row): ?>

                            <div class="seat-row">

                                <span class="row-label"><?= $row ?></span>

                                <?php for ($number = 1; $number <= 10; $number++): ?>

                                    <?php
                                    $seatNo = $row . $number;
                                    $isBooked = in_array($seatNo, $bookedSeats, true);
                                    ?>

                                    <?php if ($isBooked): ?>

                                        <span class="seat booked-seat">
                                            <?= $number ?>
                                        </span>

                                    <?php else: ?>

                                        <label class="seat available-seat">

                                            <input
                                                type="radio"
                                                name="seat_no"
                                                value="<?= $seatNo ?>"
                                                <?= $selectedSeat === $seatNo ? 'checked' : '' ?>
                                                required
                                            >

                                            <span><?= $number ?></span>

                                        </label>

                                    <?php endif; ?>

                                <?php endfor; ?>

                                <span class="row-label"><?= $row ?></span>

                            </div>

                    <?php endforeach; ?>
 
                    </div>

                    <div class="seat-confirmation">

                        <p>Please select one available seat to continue.</p>

                        <button type="submit" class="primary-button">
                            Continue to Checkout
                        </button>

                    </div>

                </form>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>
