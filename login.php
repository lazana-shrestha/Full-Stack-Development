<?php
require 'db.php';
require 'session.php';

$error = '';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRF validation
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $error = "Invalid request";
        } else {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Basic validation
            if (empty($email) || empty($password)) {
                $error = "Email and password are required";
            } else {
                // Use prepared statement
                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user) {
                    // Verify hashed password
                    if (password_verify($password, $user['password'])) {
                        // Regenerate session ID to prevent fixation
                        session_regenerate_id(true);
                        
                        // Store only user ID in session
                        $_SESSION['user_id'] = $user['id'];
                        
                        // Generate new CSRF token after login
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                        
                        header('Location: dashboard.php');
                        exit;
                    }
                }
                // Generic error message (prevents user enumeration)
                $error = "Invalid email or password";
            }
        }
    }
} catch (Exception $e) {
    // Generic error message
    $error = "Something went wrong. Please try again.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        
        <label>Email:</label><br>
        <input type="text" name="email" required><br><br>
        
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        
        <button type="submit">Login</button>
    </form>
    <br>
    <a href="signup.php">Go to Signup</a>
</body>
</html>