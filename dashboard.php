<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

// Get theme from cookie
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body style="
    background: <?php echo $theme == 'dark' ? 'black' : 'white'; ?>;
    color: <?php echo $theme == 'dark' ? 'white' : 'black'; ?>;
    font-family: Arial;
    padding: 20px;
">
    <h1>Welcome <?php echo $_SESSION['full_name']; ?>!</h1>
    <p>Student ID: <?php echo $_SESSION['student_id']; ?></p>
    <p>Current Theme: <strong><?php echo $theme; ?></strong></p>
    
    <a href="preferences.php">Change Theme</a><br>
    <a href="logout.php">Logout</a>
</body>
</html>