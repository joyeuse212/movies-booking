<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');

require_once __DIR__ . '/../config/database.php';

$pageTitle = 'Admin Dashboard | SCREENHOUSE';
$basePath = '../';

// Count records
$users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$movies = $conn->query("SELECT COUNT(*) FROM movies")->fetchColumn();
$showtimes = $conn->query("SELECT COUNT(*) FROM showtimes")->fetchColumn();
$bookings = $conn->query("SELECT COUNT(*) FROM bookings")->fetchColumn();

require_once __DIR__ . '/../includes/header.php';
?>

<section class="dashboard-section">

    <div class="container">

        <p class="eyebrow">ADMINISTRATION</p>
        <h1>Admin Dashboard</h1>

        <p>Welcome to SCREENHOUSE management.</p>

        <div class="dashboard-stats">

            <div class="stat-card">
                <div>
                    <p>Total Users</p>
                    <h2><?= (int) $users ?></h2>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <p>Total Movies</p>
                    <h2><?= (int) $movies ?></h2>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <p>Showtimes</p>
                    <h2><?= (int) $showtimes ?></h2>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <p>Total Bookings</p>
                    <h2><?= (int) $bookings ?></h2>
                </div>
            </div>

        </div>

        <h2>Manage SCREENHOUSE</h2>

        <div class="admin-links">
            <a href="movies.php">Manage Movies →</a>
            <a href="showtimes.php">Manage Showtimes →</a>
            <a href="bookings.php">View Bookings →</a>
            <a href="../index.php" class="primary-button">
            Explore All Movies
            </a>
        </div>

    </div>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
