<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/csrf.php'; // Added CSRF helper

session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: /movies-booking/admin/dashboard'); // Fixed URL
    } else {
        header('Location: /movies-booking/customer/dashboard'); // Fixed URL
    }
    exit;
}

$pageTitle = 'Login | SCREENHOUSE';
$basePath = '';

$success = '';
$error = '';

if (isset($_GET['registered']) && $_GET['registered'] === '1') {
    $success = 'Your account has been created successfully! You can now log in.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Verify CSRF Token
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security token invalid. Please try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $error = 'Please enter your email and password.';
        } else {
            $stmt = $conn->prepare(
                "SELECT user_id, username, email, password_hash, role
                 FROM users
                 WHERE email = ?"
            );

            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header('Location: /movies-booking/admin/dashboard');
                    exit;
                }

                header('Location: /movies-booking/customer/dashboard');
                exit;

            } else {
                $error = 'Invalid email or password. Please try again.';
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="auth-section">
    <div class="auth-card">
        <p class="eyebrow">WELCOME BACK</p>
        <h1>Login to SCREENHOUSE</h1>
        <p class="auth-description">
            Sign in to continue your cinema experience.
        </p>

        <?php if ($success !== ''): ?>
            <div class="success-message">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            
            <!-- ADDED CSRF TOKEN HERE -->
            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your email"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required
            >

            <button type="submit" class="auth-button">
                Login
            </button>
        </form>

        <p class="auth-switch">
            Don't have an account?
            <a href="register.php">Create Account</a>
        </p>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>