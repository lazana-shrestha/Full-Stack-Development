<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();
        
    if ($student && password_verify($password, $student['password_hash'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['student_id'] = $student['student_id'];
        $_SESSION['full_name'] = $student['full_name'];
        
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Invalid Student ID or Password!";
    }
}
?>

<form method="POST">
    Student ID: <input type="text" name="student_id"><br>
    Password: <input type="password" name="password"><br>
    <button type="submit">Login</button>
</form>

<?php if (isset($error)): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<a href="register.php">Register</a>