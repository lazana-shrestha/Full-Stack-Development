<?php
require 'db.php';
require 'session.php';

$message = '';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validation
        if (empty($email) || empty($password)) {
            $message = "Email and password are required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email format";
        } elseif (strlen($password) < 8) {
            $message = "Password must be at least 8 characters";
        } else {
            // Check if email already exists
            $checkSql = "SELECT id FROM users WHERE email = ?";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([$email]);
            $existingUser = $checkStmt->fetch();

            if ($existingUser) {
                $message = "Email already registered. Please use a different email or login.";
            } else {
                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Use prepared statement
                $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$email, $hashedPassword]);

                $message = "User signed up successfully. Redirecting to login...";
                header('refresh: 2; url=login.php');
            }
        }
    }
} catch (PDOException $e) {
    // Check for duplicate entry error (MySQL error code 1062)
    if ($e->getCode() == 23000) {
        $message = "Email already registered. Please use a different email or login.";
    } else {
        // Generic error message for other errors
        $message = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
</head>
<body>
    <h2>Signup</h2>
    
    <?php if ($message): ?>
        <p style="<?php echo strpos($message, 'already registered') !== false ? 'color:red;' : ''; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>
    
    <form method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        
        <label>Password (min. 8 characters):</label><br>
        <input type="password" name="password" minlength="8" required><br><br>
        
        <button type="submit">Signup</button>
    </form>
    
    <br>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>