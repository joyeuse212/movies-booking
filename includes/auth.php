<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin(): void
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }
}

function requireRole(string $role): void
{
    requireLogin();

    if ($_SESSION['role'] !== $role) {
        header('Location: ../login.php');
        exit;
    }
}
