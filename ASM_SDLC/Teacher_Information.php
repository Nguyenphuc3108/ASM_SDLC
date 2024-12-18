<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";  // Thay bằng tên người dùng MySQL của bạn
$password = "";      // Thay bằng mật khẩu MySQL của bạn
$dbname = "SE06302";  // Tên cơ sở dữ liệu của bạn

// Kết nối tới MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy giá trị tìm kiếm từ form (nếu có)
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Truy vấn dữ liệu giáo viên
$sql = "SELECT teacher_id, full_name, email, phone_number, subjects, user_id FROM teacher";

// Nếu có giá trị tìm kiếm, thêm điều kiện vào câu truy vấn
if ($search != '') {
    $sql .= " WHERE full_name LIKE '%" . $conn->real_escape_string($search) . "%' 
              OR teacher_id LIKE '%" . $conn->real_escape_string($search) . "%'
              OR email LIKE '%" . $conn->real_escape_string($search) . "%'
              OR subjects LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }
        .search-container {
            padding: 20px;
            text-align: center;
        }
        .search-container input[type="text"] {
            padding: 10px;
            width: 300px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-container input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .table-container {
            padding: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Teacher Information</h1>
</div>
    <!-- Back to Home Button -->
    <div style="margin-bottom: 20px;">
    <a href="Teacher_Home.php">
        <button style="padding: 10px 20px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Back to Home
        </button>
    </a>
</div>
<!-- Form tìm kiếm -->
<div class="search-container">
    <form method="POST" action="teacher_information.php">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by Name, ID, Email, or Subject">
        <input type="submit" value="Search">
    </form>
</div>

<div class="table-container">
    <?php
    if ($result->num_rows > 0) {
        // Hiển thị bảng thông tin giáo viên
        echo "<table>
                <tr>
                    <th>Teacher ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Subjects</th>
                    <th>User ID</th>
                </tr>";
        
        // Lặp qua tất cả các bản ghi và hiển thị chúng
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["teacher_id"] . "</td>
                    <td>" . $row["full_name"] . "</td>
                    <td>" . $row["email"] . "</td>
                    <td>" . $row["phone_number"] . "</td>
                    <td>" . $row["subjects"] . "</td>
                    <td>" . $row["user_id"] . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No teacher records found.";
    }

    // Đóng kết nối
    $conn->close();
    ?>
</div>

</body>
</html>
