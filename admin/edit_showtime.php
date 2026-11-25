<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Edit Showtime | SCREENHOUSE';
$basePath = '../';

$error = '';

$showtimeId = (int) ($_GET['id'] ?? $_POST['showtime_id'] ?? 0);

// Get the selected showtime
$stmt = $conn->prepare(
    "SELECT * FROM showtimes WHERE showtime_id = ?"
);
$stmt->execute([$showtimeId]);

$showtime = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$showtime) {
    exit('Showtime not found.');
}

// Update showtime
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $movieId = (int) ($_POST['movie_id'] ?? 0);
    $hall = trim($_POST['hall'] ?? '');
    $startTime = trim($_POST['start_time'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);

    if ($movieId <= 0 || $hall === '' || $startTime === '' || $price <= 0) {

        $error = 'Please enter all showtime information correctly.';

    } else {

        $stmt = $conn->prepare(
            "UPDATE showtimes
             SET movie_id = ?, hall = ?, start_time = ?, price = ?
             WHERE showtime_id = ?"
        );

        $stmt->execute([
            $movieId,
            $hall,
            $startTime,
            $price,
            $showtimeId
        ]);

        header('Location: showtimes.php');
        exit;
    }
}

// Get movies
$movies = $conn->query(
    "SELECT movie_id, title FROM movies ORDER BY title"
)->fetchAll(PDO::FETCH_ASSOC);

$formattedDate = date(
    'Y-m-d\TH:i',
    strtotime($showtime['start_time'])
);

require_once __DIR__ . '/../includes/header.php';
?>

<section class="dashboard-section">
    <div class="container">

        <p class="auth-switch">
            <a href="showtimes.php">← Back to Showtimes</a>
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

            <form method="POST">

                <input
                    type="hidden"
                    name="showtime_id"
                    value="<?= $showtimeId ?>"
                >

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

                <input
                    type="text"
                    name="hall"
                    value="<?= htmlspecialchars($showtime['hall']) ?>"
                    required
                >

                <label>Date and Time</label>

                <input
                    type="datetime-local"
                    name="start_time"
                    value="<?= htmlspecialchars($formattedDate) ?>"
                    required
                >

                <label>Ticket Price</label>

                <input
                    type="number"
                    name="price"
                    min="0.01"
                    step="0.01"
                    value="<?= htmlspecialchars((string) $showtime['price']) ?>"
                    required
                >

                <button type="submit" class="auth-button">
                    Save Changes
                </button>

            </form>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
