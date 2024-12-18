<?php
// Kết nối cơ sở dữ liệu
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

// Truy vấn danh sách các khóa học
$sql = "SELECT * FROM courses";
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course View</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
        }
        .search-bar {
            text-align: center;
            margin-top: 20px;
        }
        .search-bar input[type="text"] {
            width: 300px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-bar button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #45a049;
        }
        .no-results {
            text-align: center;
            color: red;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h1>Course View</h1>
    <!-- Back to Home Button -->
    <div style="margin-bottom: 20px;">
    <a href="Teacher_Home.php">
        <button style="padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Back to Home
        </button>
    </a>
</div>
<!-- Form tìm kiếm -->
<div class="search-bar">
    <form method="GET">
        <input type="text" name="search" placeholder="Search by Course Name" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit">Search</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Course Name</th>
            <th>Description</th>
            <th>Class IDs</th>
            <th>Created At</th>
            <th>Teacher ID</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Xử lý tìm kiếm
        if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
            $search = '%' . trim($_GET['search']) . '%';
            $sql = "SELECT * FROM courses WHERE course_name LIKE :search";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':search' => $search]);
        }

        // Hiển thị danh sách khóa học
        while ($course = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($course['id']) . "</td>";
            echo "<td>" . htmlspecialchars($course['course_name']) . "</td>";
            echo "<td>" . htmlspecialchars($course['course_description']) . "</td>";
            echo "<td>" . htmlspecialchars($course['class_ids']) . "</td>";
            echo "<td>" . htmlspecialchars($course['created_at']) . "</td>";
            echo "<td>" . htmlspecialchars($course['teacher_id']) . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
