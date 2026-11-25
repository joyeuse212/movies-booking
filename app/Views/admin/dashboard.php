<?php require_once __DIR__ . '/../../../includes/header.php'; ?>

<section class="dashboard-section">

    <div class="container">

        <p class="eyebrow">ADMINISTRATION</p>

        <h1>Admin Dashboard</h1>

        <p>Welcome to SCREENHOUSE management.</p>

        <div class="dashboard-stats">

            <div class="stat-card">
                <div>
                    <p>Total Users</p>
                    <h2><?= $users ?></h2>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <p>Total Movies</p>
                    <h2><?= $movies ?></h2>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <p>Showtimes</p>
                    <h2><?= $showtimes ?></h2>
                </div>
            </div>

            <div class="stat-card">
                <div>
                    <p>Total Bookings</p>
                    <h2><?= $bookings ?></h2>
                </div>
            </div>

        </div>

        <h2>Manage SCREENHOUSE</h2>

        <div class="admin-links">

            <a href="/movies-booking/admin/movies">
                Manage Movies →
            </a>

            <a href="/movies-booking/admin/showtimes">
                Manage Showtimes →
            </a>

            <a href="/movies-booking/admin/bookings">
                View Bookings →
            </a>

            <a href="/movies-booking/customer/movies"
               class="primary-button">
                Explore All Movies
            </a>

        </div>

    </div>

</section>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
