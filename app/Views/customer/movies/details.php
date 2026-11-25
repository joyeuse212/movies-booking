
<?php require_once __DIR__ . '/../../../../includes/header.php'; ?>

<section class="movie-details-page">

    <div class="container">

        <p class="auth-switch">
            <a href="/movies-booking/customer/movies">
                ← Back to Movies
            </a>
        </p>

        <?php if (!$movie): ?>

            <div class="empty-state">
                <h2>Movie Not Found</h2>
                <p>The movie you are looking for does not exist.</p>

                <a href="/movies-booking/customer/movies"
                   class="primary-button">
                    Back to Movies
                </a>
            </div>

        <?php else: ?>

            <?php
            $image = $movieImages[$movie['title']] ?? '';
            ?>

            <div class="movie-details-header">

                <div class="movie-details-poster">

                    <?php if ($image !== ''): ?>

                        <img
                            src="<?= $basePath ?>assets/images/<?= htmlspecialchars($image) ?>"
                            alt="<?= htmlspecialchars($movie['title']) ?>"
                        >

                    <?php else: ?>

                        <span>SCREENHOUSE</span>

                        <h2>
                            <?= htmlspecialchars($movie['title']) ?>
                        </h2>

                    <?php endif; ?>

                </div>

                <div class="movie-details-info">

                    <p class="eyebrow">MOVIE INFORMATION</p>

                    <h1>
                        <?= htmlspecialchars($movie['title']) ?>
                    </h1>

                    <p class="movie-genre">
                        <?= htmlspecialchars($movie['genre']) ?>
                    </p>

                    <p>
                        <strong>Duration:</strong>
                        <?= (int) $movie['duration'] ?> minutes
                    </p>

                    <p class="movie-details-description">
                        Get ready for an amazing cinema experience.
                        Choose your preferred screening below
                        and reserve your seat.
                    </p>

                </div>

            </div>

            <div class="showtimes-section">

                <p class="eyebrow">PLAN YOUR VISIT</p>

                <h2>Available Showtimes</h2>

                <?php if (count($showtimes) > 0): ?>

                    <div class="showtime-grid">

                        <?php foreach ($showtimes as $showtime): ?>

                            <div class="showtime-card">

                                <?php if ($image !== ''): ?>

                                    <div class="showtime-poster">

                                        <img
                                            src="<?= $basePath ?>assets/images/<?= htmlspecialchars($image) ?>"
                                            alt="<?= htmlspecialchars($movie['title']) ?>"
                                        >

                                    </div>

                                <?php endif; ?>

                                <h3>
                                    <?= date('d M Y', strtotime($showtime['start_time'])) ?>
                                </h3>

                                <p class="showtime-time">
                                    <?= date('h:i A', strtotime($showtime['start_time'])) ?>
                                </p>

                                <p>
                                    Hall:
                                    <?= htmlspecialchars($showtime['hall']) ?>
                                </p>

                                <p class="showtime-price">
                                    <?= number_format((float) $showtime['price'], 2) ?> RWF
                                </p>

                                <a
                                    href="/movies-booking/customer/seats?id=<?= (int) $showtime['showtime_id'] ?>"
                                    class="primary-button"
                                >
                                    Choose Seats
                                </a>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="empty-state">

                        <h3>No Showtimes Available</h3>

                        <p>
                            There are currently no upcoming screenings
                            for this movie. Please check again later.
                        </p>

                    </div>

                <?php endif; ?>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>
