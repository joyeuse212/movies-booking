<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$pageTitle = 'Create Account | SCREENHOUSE';
$basePath = '';

$error = '';

$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($username === '' || $email === '' || $password === '' || $confirmPassword === '') {

        $error = 'Please fill in all fields.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = 'Please enter a valid email address.';

    } elseif (strlen($password) < 6) {

        $error = 'Password must contain at least 6 characters.';

    } elseif ($password !== $confirmPassword) {

        $error = 'Passwords do not match.';

    } else {

        $checkEmail = $conn->prepare(
            "SELECT user_id FROM users WHERE email = ?"
        );

        $checkEmail->execute([$email]);

        if ($checkEmail->fetch()) {

            $error = 'This email address is already registered.';

        } else {

            $passwordHash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare(
                "INSERT INTO users
                (username, email, password_hash, role)
                VALUES (?, ?, ?, ?)"
            );

            $stmt->execute([
                $username,
                $email,
                $passwordHash,
                'customer'
            ]);

            header('Location: login.php?registered=1');
            exit;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="auth-section">

    <div class="auth-card">

        <p class="eyebrow">JOIN SCREENHOUSE</p>

        <h1>Create Your Account</h1>

        <p class="auth-description">
            Sign up today and enjoy your favourite movies
            with a simple and convenient booking experience.
        </p>

        <?php if ($error !== ''): ?>

            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST" action="">

            <label for="username">Username</label>

            <input
                type="text"
                id="username"
                name="username"
                placeholder="Enter your username"
                value="<?= htmlspecialchars($username) ?>"
                required
            >

            <label for="email">Email Address</label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your email"
                value="<?= htmlspecialchars($email) ?>"
                required
            >

            <label for="password">Password</label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Create a password"
                required
            >

            <label for="confirm_password">Confirm Password</label>

            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                placeholder="Confirm your password"
                required
            >

            <button type="submit" class="auth-button">
                Create Account
            </button>

        </form>

        <p class="auth-switch">
            Already have an account?
            <a href="login.php">Login here</a>
        </p>

    </div>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
