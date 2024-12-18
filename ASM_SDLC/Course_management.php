<?php
// Kết nối đến cơ sở dữ liệu
$host = 'localhost';
$dbname = 'SE06302';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>
 
</head>
<body>
    <!-- Back to Home Button -->
<div style="margin-bottom: 20px;">
    <a href="Admin_Home.php">
        <button style="padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Back to Home
        </button>
    </a>
</div>

<!-- Add Course Form -->
<h2>Add Course</h2>
<form method="POST">
    <input type="text" name="course_name" placeholder="Course Name" required>
    <textarea name="course_description" placeholder="Course Description" required></textarea>
    <input type="text" name="class_ids" placeholder="Class IDs (comma separated)" required>
    <input type="number" name="teacher_id" placeholder="Teacher ID" required>
    <button type="submit" name="add_course">Add Course</button>
</form>

<!-- Process Add Course -->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
    $course_name = $_POST['course_name'];
    $course_description = $_POST['course_description'];
    $class_ids = $_POST['class_ids'];
    $teacher_id = $_POST['teacher_id'];

    $sql = "INSERT INTO courses (course_name, course_description, class_ids, teacher_id, created_at) 
            VALUES (:course_name, :course_description, :class_ids, :teacher_id, NOW())";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':course_name' => $course_name,
            ':course_description' => $course_description,
            ':class_ids' => $class_ids,
            ':teacher_id' => $teacher_id,
        ]);
        echo "Course added successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- Edit Course Form -->
<h2>Edit Course</h2>
<?php
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM courses WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $edit_id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($course) {
        ?>
        <form method="POST">
            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
            <input type="text" name="course_name" value="<?= $course['course_name'] ?>" required>
            <textarea name="course_description" required><?= $course['course_description'] ?></textarea>
            <input type="text" name="class_ids" value="<?= $course['class_ids'] ?>" required>
            <input type="number" name="teacher_id" value="<?= $course['teacher_id'] ?>" required>
            <button type="submit" name="update_course">Update Course</button>
        </form>
        <?php
    }
}

// Handle Update Course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_course'])) {
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $course_description = $_POST['course_description'];
    $class_ids = $_POST['class_ids'];
    $teacher_id = $_POST['teacher_id'];

    $sql = "UPDATE courses 
            SET course_name = :course_name, course_description = :course_description, 
                class_ids = :class_ids, teacher_id = :teacher_id 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':course_name' => $course_name,
            ':course_description' => $course_description,
            ':class_ids' => $class_ids,
            ':teacher_id' => $teacher_id,
            ':id' => $course_id,
        ]);
        echo "Course updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- List of Courses -->
<h2>Course List</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Course Name</th>
        <th>Course Description</th>
        <th>Class IDs</th>
        <th>Created At</th>
        <th>Teacher ID</th>
        <th>Actions</th>
    </tr>
    <?php
    $sql = "SELECT * FROM courses";
    $stmt = $pdo->query($sql);
    while ($course = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$course['id']}</td>";
        echo "<td>{$course['course_name']}</td>";
        echo "<td>{$course['course_description']}</td>";
        echo "<td>{$course['class_ids']}</td>";
        echo "<td>{$course['created_at']}</td>";
        echo "<td>{$course['teacher_id']}</td>";
        echo "<td>
                <a href='?edit_id={$course['id']}'>
                <button class='edit-btn'>Edit</button>
                </a> | 
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='course_id' value='{$course['id']}'>
                    <button type='submit' name='delete_course'>Delete</button>
                </form>
              </td>";
        echo "</tr>";
    }
    ?>
</table>

<!-- Process Delete Course -->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course'])) {
    $course_id = $_POST['course_id'];
    $sql = "DELETE FROM courses WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([':id' => $course_id]);
        echo "Course deleted successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

</body>
</html>
