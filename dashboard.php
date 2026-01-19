<?php
require 'db.php';
require 'session.php';

$user_email = '';
$is_logged_in = false;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Use prepared statement
    $sql = "SELECT email FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if ($user) {
        $user_email = htmlspecialchars($user['email']);
        $is_logged_in = true;
    }
}

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    // Destroy session completely
    session_unset();
    session_destroy();
    
    // Clear session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to my site</h1>
    
    <?php if ($is_logged_in): ?>
        <p>Logged In User: <?php echo $user_email; ?></p>
        <a href="?logout=1">
            <button>Logout</button>
        </a>
    <?php else: ?>
        <a href="login.php">
            <button>Login</button>
        </a>
    <?php endif; ?>
    
</body>
</html>