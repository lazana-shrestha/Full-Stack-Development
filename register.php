<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    
    $hash = password_hash($password, PASSWORD_BCRYPT);
    
    $stmt = $pdo->prepare("INSERT INTO students (student_id, full_name, password_hash) VALUES (?, ?, ?)");
    $stmt->execute([$student_id, $name, $hash]);
    
    header('Location: login.php');
    exit();
}
?>

<form method="POST">
    Student ID: <input type="text" name="student_id"><br>
    Name: <input type="text" name="name"><br>
    Password: <input type="password" name="password"><br>
    <button type="submit">Register</button>
</form>
<a href="login.php">Login</a>