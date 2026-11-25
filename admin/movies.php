<?php
require_once __DIR__ . '/../includes/header.php';
?>

<section class="auth-section">
    <div class="auth-card">

        <h1>Add New Movie</h1>
        <p class="auth-description">
            Enter the movie details below.
        </p>

        <?php if (!empty($error)): ?>
            <p class="error-message">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="/movies-booking/admin/movies/store">

            <label for="title">Movie Title:</label>
            <input
                type="text"
                id="title"
                name="title"
                value="<?= htmlspecialchars($old['title'] ?? '') ?>"
                required
            >

            <label for="genre">Genre:</label>
            <input
                type="text"
                id="genre"
                name="genre"
                value="<?= htmlspecialchars($old['genre'] ?? '') ?>"
                required
            >

            <label for="duration">Duration (minutes):</label>
            <input
                type="number"
                id="duration"
                name="duration"
                min="1"
                value="<?= htmlspecialchars((string)($old['duration'] ?? '')) ?>"
                required
            >

            <button type="submit" class="auth-button">
                Save Movie
            </button>

        </form>

        <p class="auth-switch">
            <a href="/movies-booking/admin/movies">Cancel and go back</a>
        </p>

    </div>
</section>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
