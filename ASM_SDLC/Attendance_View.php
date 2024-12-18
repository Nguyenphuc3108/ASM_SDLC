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

// Truy vấn dữ liệu bảng Attendance với tìm kiếm
$sql = "SELECT attendance.id, attendance.student_id, attendance.class_id, attendance.date, attendance.status, attendance.created_at,
        students.name AS student_name, classes.class_name
        FROM attendance
        LEFT JOIN students ON attendance.student_id = students.Student_ID
        LEFT JOIN classes ON attendance.class_id = classes.id
        WHERE students.name LIKE :searchKeyword OR classes.class_name LIKE :searchKeyword OR attendance.date LIKE :searchKeyword";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':searchKeyword', '%' . $searchKeyword . '%');
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Information</title>
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
    </style>
</head>
<body>

<h1>Attendance Information</h1>
    <!-- Back to Home Button -->
    <div style="margin-bottom: 20px;">
    <a href="Student_Home.php">
        <button style="padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Back to Home
        </button>
    </a>
</div>
<div class="search-bar">
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search by student, class, or date" value="<?= htmlspecialchars($searchKeyword); ?>">
        <button type="submit">Search</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Student Name</th>
            <th>Class Name</th>
            <th>Date</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Duyệt qua kết quả và hiển thị từng bản ghi attendance
        while ($attendance = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($attendance['id']) . "</td>";
            echo "<td>" . htmlspecialchars($attendance['student_name']) . "</td>";
            echo "<td>" . htmlspecialchars($attendance['class_name']) . "</td>";
            echo "<td>" . htmlspecialchars($attendance['date']) . "</td>";
            echo "<td>" . htmlspecialchars($attendance['status']) . "</td>";
            echo "<td>" . htmlspecialchars($attendance['created_at']) . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
