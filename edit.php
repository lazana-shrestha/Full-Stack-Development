<?php
include "db.php";

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['update'])) {
    $stmt = $conn->prepare(
        "UPDATE students SET name=?, email=?, course=? WHERE id=?"
    );
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['course'],
        $id
    ]);
    header("Location: index.php");
}
?>

<form method="POST">
    <input type="text" name="name" value="<?= $row['name'] ?>" required><br><br>
    <input type="email" name="email" value="<?= $row['email'] ?>" required><br><br>
    <input type="text" name="course" value="<?= $row['course'] ?>" required><br><br>
    <button name="update">Update</button>
</form>
