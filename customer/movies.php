<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
requireRole('customer');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Models/Movie.php';
require_once __DIR__ . '/../app/Controllers/MovieController.php';

$pageTitle = 'Explore Movies | SCREENHOUSE';
$basePath = '../';

// Movie poster images
$movieImages = [
    'Avatar' => 'avatar.jpg',
    'John Wick' => 'john-wick.jpeg',
    'The Batman' => 'the-batman.jpg',
    'Doinglife' =>'doinglife.jpg',
    'Wicked'=>'wicked.jpg',
    'Gradiator'=>'gradiator.jpg'
];

$movieController = new \App\Controllers\MovieController($conn);

$movies = $movieController->index();
require_once __DIR__ . '/../includes/header.php';
?>
<?php require_once __DIR__ . '/../app/Views/customer/movies/index.php'; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
