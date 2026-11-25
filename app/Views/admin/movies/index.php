<?php
require_once __DIR__ . '/../../../../includes/header.php';
?>

<section class="admin-movies-section">
    <div class="container">

        <div class="admin-movies-heading">
            <h1>Manage Movies</h1>
            <p>Add new movies and manage movies in SCREENHOUSE.</p>
        </div>

        <?php if (!empty($message)): ?>
            <p class="success-message">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p class="error-message">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <!-- ADD MOVIE FORM -->
        <div class="admin-movie-form">
            <h2>Add New Movie</h2>

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
        </div>

        <!-- MOVIE LIST -->
        <div class="admin-movie-list">

            <h2>Added Movies</h2>

            <?php if (empty($movies)): ?>

                <p class="no-movies">No movies found. Add your first movie above.</p>

            <?php else: ?>

                <div class="booking-table-wrapper">
                    <table class="booking-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Movie Title</th>
                                <th>Genre</th>
                                <th>Duration</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($movies as $movie): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars((string)$movie['movie_id']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($movie['title']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($movie['genre']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)$movie['duration']) ?> minutes
                                    </td>

                                    <td>
                                        <div class="action-buttons">

                                            <a
                                                href="/movies-booking/admin/movies/edit?id=<?= (int)$movie['movie_id'] ?>"
                                                class="primary-button"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                method="POST"
                                                action="/movies-booking/admin/movies/delete"
                                                onsubmit="return confirm('Are you sure you want to delete this movie?');"
                                            >
                                                <input
                                                    type="hidden"
                                                    name="movie_id"
                                                    value="<?= (int)$movie['movie_id'] ?>"
                                                >

                                                <button type="submit" class="delete-button">
                                                    Delete
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

        </div>

    </div>
</section>

<?php
require_once __DIR__ . '/../../../../includes/footer.php';
?>
