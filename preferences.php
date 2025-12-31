<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $theme = $_POST['theme'];
    setcookie('theme', $theme, time()+86400*30);
    
    // Redirect back to preferences to see change immediately
    header('Location: preferences.php');
    exit();
}

$current_theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Theme Preferences</title>
</head>
<body>
    <h2>Change Theme</h2>
    <p>Current theme: <?php echo $current_theme; ?></p>
    
    <form method="POST">
        <input type="radio" name="theme" value="light" <?php echo $current_theme == 'light' ? 'checked' : ''; ?>> Light<br>
        <input type="radio" name="theme" value="dark" <?php echo $current_theme == 'dark' ? 'checked' : ''; ?>> Dark<br>
        <button type="submit">Save Theme</button>
    </form>
    
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>