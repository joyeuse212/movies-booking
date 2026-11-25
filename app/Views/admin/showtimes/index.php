<?php require_once __DIR__ . '/../../../../includes/header.php'; ?>

<section class="dashboard-section">
    <div class="container">

        <p class="auth-switch">
            <a href="/movies-booking/admin/dashboard">
                ← Back to Dashboard
            </a>
        </p>

        <p class="eyebrow">SHOWTIME MANAGEMENT</p>
        <h1>Manage Showtimes</h1>

        <?php if ($error !== ''): ?>
            <p class="error-message">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <p class="success-message">
                <?= htmlspecialchars($success) ?>
            </p>
        <?php endif; ?>

        <div class="auth-card">

            <h2>Add New Showtime</h2>

            <form method="POST"
                  action="/movies-booking/admin/showtimes/store">

                <!-- ADDED CSRF TOKEN HERE -->
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

                <label>Select Movie</label>

                <select name="movie_id" required>
                    <option value="">Choose a movie</option>

                    <?php foreach ($movies as $movie): ?>
                        <option value="<?= (int) $movie['movie_id'] ?>">
                            <?= htmlspecialchars($movie['title']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>

                <label>Hall</label>

                <input type="text"
                       name="hall"
                       placeholder="Hall 1"
                       required>

                <label>Date and Time</label>

                <input type="datetime-local"
                       name="start_time"
                       required>

                <label>Ticket Price</label>

                <input type="number"
                       name="price"
                       min="0.01"
                       step="0.01"
                       required>

                <button type="submit" class="auth-button">
                    Add Showtime
                </button>

            </form>

        </div>

        <h2>Available Showtimes</h2>

        <div class="booking-table">

            <table>
                <thead>
                    <tr>
                        <th>Movie</th>
                        <th>Hall</th>
                        <th>Date and Time</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($showtimes) > 0): ?>

                        <?php foreach ($showtimes as $showtime): ?>

                            <tr>
                                <td>
                                    <?= htmlspecialchars($showtime['title']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($showtime['hall']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($showtime['start_time']) ?>
                                </td>

                                <td>
                                    <?= number_format((float) $showtime['price'], 2) ?> RWF
                                </td>

                                <td>
                                    <div class="action-buttons">

                                        <a href="/movies-booking/admin/showtimes/edit?id=<?= (int) $showtime['showtime_id'] ?>"
                                           class="primary-button">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="/movies-booking/admin/showtimes/delete"
                                              onsubmit="return confirm('Are you sure you want to delete this showtime?');">

                                            <input type="hidden"
                                                   name="showtime_id"
                                                   value="<?= (int) $showtime['showtime_id'] ?>">
                                                   
                                            <!-- ADDED CSRF TOKEN HERE -->
                                            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

                                            <button type="submit"
                                                    class="delete-button">
                                                Delete
                                            </button>

                                        </form>

                                    </div>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5">
                                No showtimes available.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>