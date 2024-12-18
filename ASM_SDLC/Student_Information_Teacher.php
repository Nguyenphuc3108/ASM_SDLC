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

// Truy vấn dữ liệu sinh viên, với điều kiện tìm kiếm (nếu có)
$sql = "SELECT Student_ID, Name, Age, Gender, Email, Phone, user_id, class_name FROM students";

// Nếu có giá trị tìm kiếm, thêm điều kiện vào câu truy vấn
if ($search != '') {
    $sql .= " WHERE Name LIKE '%" . $conn->real_escape_string($search) . "%' 
              OR Student_ID LIKE '%" . $conn->real_escape_string($search) . "%'
              OR Email LIKE '%" . $conn->real_escape_string($search) . "%'
              OR class_name LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color:white;
            color:black;
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
    <h1>Student Information</h1>
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
    <form method="POST" action="student_information.php">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by Name, ID, Email, or Class">
        <input type="submit" value="Search">
    </form>
</div>

<div class="table-container">
    <?php
    if ($result->num_rows > 0) {
        // Hiển thị bảng thông tin sinh viên
        echo "<table>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>User ID</th>
                    <th>Class Name</th>
                </tr>";
        
        // Lặp qua tất cả các bản ghi và hiển thị chúng
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["Student_ID"] . "</td>
                    <td>" . $row["Name"] . "</td>
                    <td>" . $row["Age"] . "</td>
                    <td>" . $row["Gender"] . "</td>
                    <td>" . $row["Email"] . "</td>
                    <td>" . $row["Phone"] . "</td>
                    <td>" . $row["user_id"] . "</td>
                    <td>" . $row["class_name"] . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No student records found.";
    }

    // Đóng kết nối
    $conn->close();
    ?>
</div>

</body>
</html>
