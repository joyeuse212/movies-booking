<?php

namespace App\Controllers;

use App\Models\Movie;
use PDO;
use PDOException;

class MovieController
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // CUSTOMER: Display all movies
    public function index(): void
    {
        $movieModel = new Movie($this->conn);
        $movies = $movieModel->getAllMovies();

        $pageTitle = 'Explore Movies | SCREENHOUSE';
        $basePath = '../';

        $movieImages = [
            'Avatar' => 'avatar.jpg',
            'John Wick' => 'john-wick.jpeg',
            'The Batman' => 'the-batman.jpg',
            'Doinglife' => 'doinglife.jpg',
            'Wicked' => 'wicked.jpg',
            'Gradiator' => 'gradiator.jpg'
        ];

        require __DIR__ . '/../Views/customer/movies/index.php';
    }

    // CUSTOMER: Display movie details
    public function details(): void
    {
        $movieId = (int) ($_GET['id'] ?? 0);

        $movieModel = new Movie($this->conn);
        $movie = null;
        $showtimes = [];

        if ($movieId > 0) {
            $movie = $movieModel->getMovieById($movieId);

            if ($movie) {
                $showtimes = $movieModel->getUpcomingShowtimes($movieId);
            }
        }

        $pageTitle = 'Movie Details | SCREENHOUSE';
        $basePath = '../';

        $movieImages = [
            'Avatar' => 'avatar.jpg',
            'John Wick' => 'john-wick.jpeg',
            'The Batman' => 'the-batman.jpg',
            'Doinglife' => 'doinglife.jpg',
            'Gradiator' => 'gradiator.jpg',
            'Wicked' => 'wicked.jpg'
        ];

        require __DIR__ . '/../Views/customer/movies/details.php';
    }

    // ADMIN: Display all movies
    
// ADMIN: Display movie form and all movies
public function adminIndex(): void
{
    $movieModel = new Movie($this->conn);
    $movies = $movieModel->getAllMovies();

    $pageTitle = 'Manage Movies | SCREENHOUSE';

    $message = '';
    $error = '';

    $old = [
        'title' => '',
        'genre' => '',
        'duration' => ''
    ];

    $status = $_GET['status'] ?? '';

    if ($status === 'deleted') {
        $message = 'Movie deleted successfully.';
    } elseif ($status === 'added') {
        $message = 'Movie added successfully.';
    } elseif ($status === 'updated') {
        $message = 'Movie updated successfully.';
    } elseif ($status === 'not_found') {
        $error = 'Movie not found.';
    } elseif ($status === 'delete_error') {
        $error = 'This movie cannot be deleted because it has showtimes or related bookings.';
    }

    require __DIR__ . '/../Views/admin/movies/index.php';
}

    // ADMIN: Display Add Movie form
    public function create(): void
    {
        $pageTitle = 'Add Movie | SCREENHOUSE';
        $basePath = '../';
        $error = '';

        $old = [
            'title' => '',
            'genre' => '',
            'duration' => ''
        ];

        require __DIR__ . '/../Views/admin/movies/create.php';
    }

    // ADMIN: Save new movie

// ADMIN: Save new movie
public function store(): void
{
    $title = trim($_POST['title'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $durationInput = $_POST['duration'] ?? '';
    $duration = filter_var($durationInput, FILTER_VALIDATE_INT);

    $movieModel = new Movie($this->conn);

    if (
        $title === '' ||
        $genre === '' ||
        $duration === false ||
        $duration <= 0
    ) {
        $error = 'Please enter all movie information correctly.';
    } else {
        try {
            if ($movieModel->createMovie($title, $genre, $duration)) {
                header('Location: /movies-booking/admin/movies?status=added');
                exit;
            }

            $error = 'Unable to add the movie. Please try again.';

        } catch (PDOException $e) {
            $error = 'Unable to add the movie. Please try again.';
        }
    }

    // Keep the form and movie list on the same page
    $movies = $movieModel->getAllMovies();

    $pageTitle = 'Manage Movies | SCREENHOUSE';

    $message = '';

    $old = [
        'title' => $title,
        'genre' => $genre,
        'duration' => $durationInput
    ];

    require __DIR__ . '/../Views/admin/movies/index.php';
}
   // ADMIN: Display Edit Movie form
    public function edit(): void
    {
        $movieId = (int) ($_GET['id'] ?? 0);

        $movieModel = new Movie($this->conn);
        $movie = $movieModel->getMovieById($movieId);

        if (!$movie) {
            http_response_code(404);
            echo 'Movie not found.';
            return;
        }

        $pageTitle = 'Edit Movie | SCREENHOUSE';
        $basePath = '../';
        $error = '';

        require __DIR__ . '/../Views/admin/movies/edit.php';
    }

    // ADMIN: Update movie
    public function update(): void
    {
        $movieId = (int) ($_POST['movie_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $genre = trim($_POST['genre'] ?? '');
        $durationInput = $_POST['duration'] ?? '';
        $duration = filter_var($durationInput, FILTER_VALIDATE_INT);

        $movieModel = new Movie($this->conn);
        $movie = $movieModel->getMovieById($movieId);

        if (!$movie) {
            http_response_code(404);
            echo 'Movie not found.';
            return;
        }

        if ($title === '' || $genre === '' || $duration === false || $duration <= 0) {
            $pageTitle = 'Edit Movie | SCREENHOUSE';
            $basePath = '../';
            $error = 'Please enter all movie information correctly.';

            $movie['title'] = $title;
            $movie['genre'] = $genre;
            $movie['duration'] = $durationInput;

            require __DIR__ . '/../Views/admin/movies/edit.php';
            return;
        }

        try {
            $movieModel->updateMovie($movieId, $title, $genre, $duration);

            header('Location: /movies-booking/admin/movies?status=updated');
            exit;
        } catch (PDOException $e) {
            $pageTitle = 'Edit Movie | SCREENHOUSE';
            $basePath = '../';
            $error = 'Unable to update the movie. Please try again.';

            $movie['title'] = $title;
            $movie['genre'] = $genre;
            $movie['duration'] = $durationInput;

            require __DIR__ . '/../Views/admin/movies/edit.php';
        }
    }

    // ADMIN: Delete movie
    public function delete(): void
    {
        $movieId = (int) ($_POST['movie_id'] ?? 0);

        if ($movieId <= 0) {
            header('Location: /movies-booking/admin/movies?status=not_found');
            exit;
        }

        try {
            $movieModel = new Movie($this->conn);
            $deleted = $movieModel->deleteMovie($movieId);

            $status = $deleted ? 'deleted' : 'not_found';
        } catch (PDOException $e) {
            $status = 'delete_error';
        }

        header('Location: /movies-booking/admin/movies?status=' . $status);
        exit;
    }
}
