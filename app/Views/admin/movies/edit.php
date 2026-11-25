<?php
require_once __DIR__ . '/../../../../includes/header.php';
?>

<div class="container">
    <h1>Edit Movie</h1>

    <?php if (!empty($error)): ?>
        <p style="color: red;">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($movie)): ?>

        <form method="POST" action="/movies-booking/admin/movies/update">

            <!-- ADDED CSRF TOKEN HERE -->
            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

            <input
                type="hidden"
                name="movie_id"
                value="<?= (int)$movie['movie_id'] ?>"
            >

            <div>
                <label for="title">Movie Title:</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?= htmlspecialchars($movie['title']) ?>"
                    required
                >
            </div>

            <br>

            <div>
                <label for="genre">Genre:</label>
                <input
                    type="text"
                    id="genre"
                    name="genre"
                    value="<?= htmlspecialchars($movie['genre']) ?>"
                    required
                >
            </div>

            <br>

            <div>
                <label for="duration">Duration (minutes):</label>
                <input
                    type="number"
                    id="duration"
                    name="duration"
                    min="1"
                    value="<?= (int)$movie['duration'] ?>"
                    required
                >
            </div>

            <br>

            <button type="submit">Update Movie</button>

            <a href="/movies-booking/admin/movies">Cancel</a>

        </form>

    <?php else: ?>
        <p>Movie not found.</p>
        <a href="/movies-booking/admin/movies">Back to Movies</a>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../../../../includes/footer.php';
?>