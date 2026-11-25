<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Edit Movie | SCREENHOUSE';
$basePath = '../';

$id = (int) ($_GET['id'] ?? 0);
$error = '';
$success = '';

// Get movie information
$stmt = $conn->prepare("SELECT * FROM movies WHERE movie_id = ?");
$stmt->execute([$id]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$movie) {
    die('Movie not found.');
}

// Update movie
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $duration = (int) ($_POST['duration'] ?? 0);

    if ($title === '' || $genre === '' || $duration <= 0) {

        $error = 'Please enter all information correctly.';

    } else {

        $stmt = $conn->prepare(
            "UPDATE movies
             SET title = ?, genre = ?, duration = ?
             WHERE movie_id = ?"
        );

        $stmt->execute([$title, $genre, $duration, $id]);

        $success = 'Movie updated successfully!';

        $stmt = $conn->prepare("SELECT * FROM movies WHERE movie_id = ?");
        $stmt->execute([$id]);
        $movie = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="auth-section">
    <div class="auth-card">

        <p class="eyebrow">MOVIE MANAGEMENT</p>
        <h1>Edit Movie</h1>

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

        <form method="POST">

            <label>Movie Title</label>
            <input type="text" name="title"
                   value="<?= htmlspecialchars($movie['title']) ?>" required>

            <label>Genre</label>
            <input type="text" name="genre"
                   value="<?= htmlspecialchars($movie['genre']) ?>" required>

            <label>Duration (minutes)</label>
            <input type="number" name="duration"
                   value="<?= (int) $movie['duration'] ?>" min="1" required>

            <button type="submit" class="auth-button">
                Update Movie
            </button>

        </form>

        <p class="auth-switch">
            <a href="movies.php">Back to Manage Movies</a>
        </p>

    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
