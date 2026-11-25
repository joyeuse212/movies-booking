<?php
require_once __DIR__ . '/../../../../includes/header.php';
?>

<div class="container">
    <h1>Add New Movie</h1>

    <?php if (!empty($error)): ?>
        <p style="color: red;">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="/movies-booking/admin/movies/store">

        <!-- ADD THIS LINE FOR CSRF SECURITY -->
        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

        <div>
            <label for="title">Movie Title:</label>
            <input
                type="text"
                id="title"
                name="title"
                value="<?= htmlspecialchars($old['title'] ?? '') ?>"
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
                value="<?= htmlspecialchars($old['genre'] ?? '') ?>"
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
                value="<?= htmlspecialchars((string)($old['duration'] ?? '')) ?>"
                required
            >
        </div>

        <br>

        <button type="submit">Save Movie</button>

        <a href="/movies-booking/admin/movies">Cancel</a>

    </form>
</div>

<?php
require_once __DIR__ . '/../../../../includes/footer.php';
?>