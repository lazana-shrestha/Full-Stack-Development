<?php
include "db.php";

if (isset($_POST['add'])) {
    $stmt = $conn->prepare(
        "INSERT INTO students (name, email, course) VALUES (?, ?, ?)"
    );
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['course']
    ]);
}
?>

<form method="POST">
    <h2>Add Student</h2>
    <input type="text" name="name" placeholder="Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="text" name="course" placeholder="Course" required><br><br>
    <button name="add">Add</button>
</form>

<hr>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Action</th>
</tr>

<?php
$stmt = $conn->query("SELECT * FROM students");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['email']}</td>
        <td>{$row['course']}</td>
        <td>
            <a href='edit.php?id={$row['id']}'>Edit</a> |
            <a href='delete.php?id={$row['id']}'>Delete</a>
        </td>
    </tr>";
}
?>
</table>
