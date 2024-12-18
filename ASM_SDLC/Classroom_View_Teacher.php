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

// Kiểm tra nếu người dùng đã nhập từ khóa tìm kiếm
$searchKeyword = '';
if (isset($_GET['search'])) {
    $searchKeyword = $_GET['search'];
}

// Truy vấn dữ liệu bảng classes với tìm kiếm
$sql = "SELECT classes.id, classes.class_name, classes.class_description, classes.created_at, courses.course_name
        FROM classes
        LEFT JOIN courses ON classes.course_id = courses.id
        WHERE classes.class_name LIKE :searchKeyword 
        OR classes.class_description LIKE :searchKeyword
        OR courses.course_name LIKE :searchKeyword";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':searchKeyword', '%' . $searchKeyword . '%');
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
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

<h1>Classroom Information</h1>
    <!-- Back to Home Button -->
    <div style="margin-bottom: 20px;">
    <a href="Teacher_Home.php">
        <button style="padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Back to Home
        </button>
    </a>
</div>
<div class="search-bar">
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search by class name, description, or course name" value="<?= htmlspecialchars($searchKeyword); ?>">
        <button type="submit">Search</button>
    </form>
</div>

<?php
// Hiển thị thông báo nếu không có kết quả tìm kiếm
if ($stmt->rowCount() == 0 && $searchKeyword != '') {
    echo "<p class='no-results'>No results found for '$searchKeyword'. Please try a different search.</p>";
}
?>

<table>
    <thead>
        <tr>
            <th>Class ID</th>
            <th>Class Name</th>
            <th>Description</th>
            <th>Course Name</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Duyệt qua kết quả và hiển thị từng lớp học
        while ($class = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($class['id']) . "</td>";
            echo "<td>" . htmlspecialchars($class['class_name']) . "</td>";
            echo "<td>" . htmlspecialchars($class['class_description']) . "</td>";
            echo "<td>" . htmlspecialchars($class['course_name']) . "</td>";
            echo "<td>" . htmlspecialchars($class['created_at']) . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
