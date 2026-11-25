<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Manage Showtimes | SCREENHOUSE';
$basePath = '../';

$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    // Delete a showtime
    if ($action === 'delete') {

        $showtimeId = (int) ($_POST['showtime_id'] ?? 0);

        if ($showtimeId > 0) {

            $stmt = $conn->prepare(
                "SELECT COUNT(*) FROM bookings WHERE showtime_id = ?"
            );

            $stmt->execute([$showtimeId]);
            $bookingCount = (int) $stmt->fetchColumn();

            if ($bookingCount > 0) {

                $error = 'This showtime cannot be deleted because it has existing bookings.';

            } else {

                $stmt = $conn->prepare(
                    "DELETE FROM showtimes WHERE showtime_id = ?"
                );

                $stmt->execute([$showtimeId]);

                $success = $stmt->rowCount() > 0
                    ? 'Showtime deleted successfully!'
                    : 'Showtime not found.';
            }

        } else {
            $error = 'Invalid showtime selected.';
        }
    }

    // Add a new showtime
    if ($action === 'add') {

        $movieId = (int) ($_POST['movie_id'] ?? 0);
        $hall = trim($_POST['hall'] ?? '');
        $startTime = $_POST['start_time'] ?? '';
        $price = (float) ($_POST['price'] ?? 0);

        if ($movieId <= 0 || $hall === '' || $startTime === '' || $price <= 0) {

            $error = 'Please enter all showtime information correctly.';

        } else {

            $stmt = $conn->prepare(
                "INSERT INTO showtimes (movie_id, hall, start_time, price)
                 VALUES (?, ?, ?, ?)"
            );

            $stmt->execute([$movieId, $hall, $startTime, $price]);

            $success = 'Showtime added successfully!';
        }
    }
}

// Get movies
$movies = $conn->query(
    "SELECT movie_id, title FROM movies ORDER BY title"
)->fetchAll(PDO::FETCH_ASSOC);

// Get showtimes
$stmt = $conn->query(
    "SELECT showtimes.*, movies.title
     FROM showtimes
     JOIN movies ON showtimes.movie_id = movies.movie_id
     ORDER BY showtimes.start_time DESC"
);

$showtimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<section class="dashboard-section">
    <div class="container">

        <p class="auth-switch">
            <a href="dashboard.php">← Back to Dashboard</a>
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

            <form method="POST">

                <input type="hidden" name="action" value="add">

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
                <input type="text" name="hall" placeholder="Hall 1" required>

                <label>Date and Time</label>
                <input type="datetime-local" name="start_time" required>

                <label>Ticket Price</label>
                <input type="number" name="price" min="0.01" step="0.01" required>

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

                                        <a
                                            href="<?= $basePath ?>admin/edit_showtime.php?id=<?= (int) $showtime['showtime_id'] ?>"
                                            class="primary-button"
                                        >
                                            Edit
                                        </a>

                                        <form method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this showtime?');">

                                            <input type="hidden" name="action" value="delete">

                                            <input
                                                type="hidden"
                                                name="showtime_id"
                                                value="<?= (int) $showtime['showtime_id'] ?>"
                                            >

                                            <button type="submit" class="delete-button">
                                                Delete
                                            </button>

                                        </form>

                                    </div>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5">No showtimes available.</td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
