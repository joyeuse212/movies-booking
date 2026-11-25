<?php require_once __DIR__ . '/../../../../includes/header.php'; ?>

<section class="dashboard-section">
    <div class="container">

        <p class="auth-switch">
            <a href="/movies-booking/admin/showtimes">
                ← Back to Showtimes
            </a>
        </p>

        <p class="eyebrow">SHOWTIME MANAGEMENT</p>

        <h1>Edit Showtime</h1>

        <?php if ($error !== ''): ?>
            <p class="error-message">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <div class="auth-card">

            <h2>Update Showtime Information</h2>

            <form method="POST"
                  action="/movies-booking/admin/showtimes/update">

                <!-- ADDED CSRF TOKEN HERE -->
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

                <input type="hidden"
                       name="showtime_id"
                       value="<?= (int) $showtime['showtime_id'] ?>">

                <label>Select Movie</label>

                <select name="movie_id" required>

                    <?php foreach ($movies as $movie): ?>

                        <option
                            value="<?= (int) $movie['movie_id'] ?>"
                            <?= (int) $movie['movie_id'] === (int) $showtime['movie_id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($movie['title']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <label>Hall</label>

                <input type="text"
                       name="hall"
                       value="<?= htmlspecialchars($showtime['hall']) ?>"
                       required>

                <label>Date and Time</label>

                <input type="datetime-local"
                       name="start_time"
                       value="<?= htmlspecialchars($formattedDate) ?>"
                       required>

                <label>Ticket Price</label>

                <input type="number"
                       name="price"
                       min="0.01"
                       step="0.01"
                       value="<?= htmlspecialchars((string) $showtime['price']) ?>"
                       required>

                <button type="submit" class="auth-button">
                    Save Changes
                </button>

            </form>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>