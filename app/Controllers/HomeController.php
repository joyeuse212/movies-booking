<?php

namespace App\Controllers;

use App\Models\Movie;
use PDO;

class HomeController
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // Display the homepage
    public function index(): void
    {
        $movieModel = new Movie($this->conn);

        $movies = $movieModel->getAllMovies();

        $pageTitle = 'SCREENHOUSE | Discover Movies';
        $basePath = '';

        $movieImages = [
            'Avatar' => 'avatar.jpg',
            'John Wick' => 'john-wick.jpeg',
            'The Batman' => 'the-batman.jpg',
            'Doinglife' => 'doinglife.jpg',
            'Gradiator' => 'gradiator.jpg',
            'Wicked' => 'wicked.jpg'
        ];

        require_once __DIR__ . '/../Views/home/index.php';
    }
}