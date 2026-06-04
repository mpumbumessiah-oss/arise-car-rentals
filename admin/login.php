<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';
$rateLimitKey = 'login_attempts_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');

// CSRF: generate token only on GET or when not set
if ($_SERVER['REQUEST_METHOD'] === 'GET' || empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF validation
    if (
        !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        error_log("CSRF validation failed for IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        die("CSRF validation failed. Please go back and refresh the page.");
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Rate limiting (simple session-based)
    if (isset($_SESSION[$rateLimitKey]) && $_SESSION[$rateLimitKey] > 20) {
        $error = 'Too many login attempts. Please try again later.';
    } elseif (!empty($username) && !empty($password)) {
        try {
            $db = Database::getInstance();
            $conn = $db->connection();

            $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
            if (!$stmt) {
                throw new Exception("SQL prepare failed: " . $conn->error);
            }

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();

            // TEMPORARY PLAIN TEXT CHECK (No password_verify)
            if ($admin && $password === $admin['password']) {
                // Regenerate session ID and CSRF token after successful login
                session_regenerate_id(true);
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                unset($_SESSION[$rateLimitKey]);
                
                // Generate fresh CSRF token for future forms
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                header('Location: index.php');
                exit;
            } else {
                $_SESSION[$rateLimitKey] = ($_SESSION[$rateLimitKey] ?? 0) + 1;
                $error = 'Invalid username or password.';
            }
            $stmt->close();
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $error = 'An internal error occurred. Please try again later.';
        }
    } else {
        $error = 'Please enter both username and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Arise Car Rentals</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6">Admin Login</h2>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <div class="mb-4">
                <input type="text" name="username" placeholder="Username" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-6">
                <input type="password" name="password" placeholder="Password" required class="w-full border rounded px-3 py-2">
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                Login
            </button>
        </form>
    </div>
</div>
</body>
</html>