<?php require_once __DIR__ . '/../../../../includes/header.php'; ?>
<section class="movies-page">

    <div class="container">

        <div class="movies-page-heading">
            <p class="eyebrow">NOW SHOWING</p>

            <h1>Explore Our Movies</h1>

            <p>
                Discover your favourite movies and get ready
                for your next cinema experience.
            </p>
        </div>

        <?php if (count($movies) > 0): ?>

            <div class="movie-grid">

                <?php foreach ($movies as $movie): ?>

                    <article class="movie-card">

                        <div class="movie-poster">

                        <?php
                            $image = $movieImages[$movie['title']] ?? '';
                        ?>

                            <?php if ($image !== ''): ?>

                                <img
                                    src="../assets/images/<?= htmlspecialchars($image) ?>"
                                    alt="<?= htmlspecialchars($movie['title']) ?>"
                                >

                            <?php else: ?>

                                <span>SCREENHOUSE</span>

                                <h3>
                                    <?= htmlspecialchars($movie['title']) ?>
                                </h3>

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
                            <a href="movie-details?id=<?= (int) $movie['movie_id'] ?>">
                                 View Showtimes →
                            </a>
                            

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">
                <h3>No Movies Available</h3>

                <p>
                    Our movie collection is being prepared.
                    Please check back soon.
                </p>
            </div>

        <?php endif; ?>

    </div>

</section>
<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>
