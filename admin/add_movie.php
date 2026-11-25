<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Add Movie | SCREENHOUSE';
$basePath = '../';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $duration = (int) ($_POST['duration'] ?? 0);

    if ($title === '' || $genre === '' || $duration <= 0) {

        $error = 'Please enter all movie information correctly.';

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO movies (title, genre, duration)
             VALUES (?, ?, ?)"
        );

        $stmt->execute([$title, $genre, $duration]);
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="auth-section">
    <div class="auth-card">

        <p class="auth-switch">
            <a href="movies.php">← Back to Manage Movies</a>
        </p>

        <p class="eyebrow">MOVIE MANAGEMENT</p>
        <h1>Add New Movie</h1>

        <?php if ($error !== ''): ?>
            <p class="error-message">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST">

            <label>Movie Title</label>
            <input type="text" name="title" required>

            <label>Genre</label>
            <input type="text" name="genre" required>

            <label>Duration (minutes)</label>
            <input type="number" name="duration" min="1" required>

            <button type="submit" class="auth-button">
                Add Movie
            </button>

        </form>

    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
