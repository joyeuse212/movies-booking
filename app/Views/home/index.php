<?php require_once __DIR__ . '/../../../includes/header.php'; ?>
<section class="hero">
    <div class="container hero-content">
        <p class="eyebrow">WELCOME TO SCREENHOUSE</p>

        <h1>Experience Movies<br>Like Never Before.</h1>

        <p class="hero-description">
            Discover your favourite movies, choose your seats
            and book your next cinema experience.
        </p>

      <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>

          <a href="admin/dashboard.php" class="primary-button">
              Back to Admin Dashboard
           </a>

<?php endif; ?>
    </div>
</section>

<section class="movies-section">
    <div class="container">

        <div class="section-heading">
            <div>
                <p class="eyebrow">YOUR NEXT EXPERIENCE</p>
                <h2>Discover Movies</h2>
            </div>

            <a href="customer/movies.php" class="text-link">
                View All Movies →
            </a>
        </div>

        <?php if (count($movies) > 0): ?>

            <div class="movie-grid">

                <?php foreach ($movies as $movie): ?>

                    <?php
                    $image = $movieImages[$movie['title']] ?? '';
                    ?>

                    <article class="movie-card">

                        <div class="movie-poster">

                            <?php if ($image !== ''): ?>

                                <img
                                    src="assets/images/<?= htmlspecialchars($image) ?>"
                                    alt="<?= htmlspecialchars($movie['title']) ?>"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >

                                <div class="poster-fallback" style="display: none;">
                                    <span>SCREENHOUSE</span>
                                    <h3>
                                        <?= htmlspecialchars($movie['title']) ?>
                                    </h3>
                                </div>

                            <?php else: ?>

                                <div class="poster-fallback">
                                    <span>SCREENHOUSE</span>
                                    <h3>
                                        <?= htmlspecialchars($movie['title']) ?>
                                    </h3>
                                </div>

                            <?php endif; ?>

                        </div>

                        <div class="movie-info">

                            <h3>
                                <?= htmlspecialchars($movie['title']) ?>
                            </h3>

                            <p>
                                <?= htmlspecialchars($movie['genre']) ?>
                                <span>•</span>
                                <?= (int) $movie['duration'] ?> minutes
                            </p>

                            <a href="customer/movie_details.php?id=<?= (int) $movie['movie_id'] ?>">
                                View Showtimes →
                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">
                <h3>Movies Coming Soon</h3>

                <p>
                    Our movie collection will appear here.
                    Please check back soon.
                </p>
            </div>

        <?php endif; ?>

    </div>
</section>
<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>