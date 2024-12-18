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

// Kiểm tra nếu form được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $username = $_POST['username'];
    $password = $_POST['password'];  // $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Mã hóa mật khẩu
    $email = $_POST['email'];
    $role = $_POST['role']; // 'admin', 'teacher', 'student'

    // Kiểm tra xem username đã tồn tại trong bảng users chưa
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $userCount = $stmt->fetchColumn();

    if ($userCount > 0) {
        // Nếu username đã tồn tại, thông báo lỗi
        $message = "Username already exists. Please choose another username.";
    } else {
        // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
        $pdo->beginTransaction();

        try {
            // Thêm người dùng vào bảng 'users'
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            
            // Lấy user_id mới nhất (ID của người dùng vừa thêm vào)
            $user_id = $pdo->lastInsertId();
            
            // Xử lý thêm cho từng role
            if ($role == 'teacher') {
                $full_name = $_POST['full_name'];
                $phone_number = $_POST['phone_number'];
                $subjects = $_POST['subjects'];

                // Thêm giáo viên vào bảng 'teachers'
                $stmt = $pdo->prepare("INSERT INTO teacher (full_name, email, phone_number, subjects, user_id) VALUES (:full_name, :email, :phone_number, :subjects, :user_id)");
                $stmt->bindParam(':full_name', $full_name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone_number', $phone_number);
                $stmt->bindParam(':subjects', $subjects);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
            } elseif ($role == 'student') {
                $name = $_POST['name'];
                $age = $_POST['age'];
                $gender = $_POST['gender'];
                $phone = $_POST['phone'];
                $class_name = $_POST['class_name'];

                // Thêm sinh viên vào bảng 'students'
                $stmt = $pdo->prepare("INSERT INTO students (Name, Age, Gender, Email, Phone, class_name, user_id) VALUES (:name, :age, :gender, :email, :phone, :class_name, :user_id)");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':age', $age);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':class_name', $class_name);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
            }elseif ($role == 'admin') {
                // Không cần thêm vào bảng khác, chỉ cần xác nhận
                echo "Admin has been successfully added.";
            }
            
            // Commit transaction
            $pdo->commit();
            $message = "User has been successfully added.";
        } catch (Exception $e) {
            // Rollback transaction nếu có lỗi
            $pdo->rollBack();
            $message = "Failed to add user: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
        }
        form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            text-decoration: none;
            background-color:cadetblue;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 14px;
        }
        .back-button:hover {
            background-color:brown;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        #teacher_fields, #student_fields {
            display: none;
        }
        .message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<?php if (!empty($message)): ?>
    <p class="message"><?= htmlspecialchars($message); ?></p>
<?php endif; ?>
<form action="" method="POST">
    <h2>User Registration</h2>
    <!-- Nút Back to Login -->
    <a href="login.php" class="back-button">Back to Login</a>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="role">Role:</label>
    <select id="role" name="role" required>
        <option value="admin">Admin</option>
        <option value="teacher">Teacher</option>
        <option value="student">Student</option>
    </select>

    <div id="teacher_fields">
        <h3>Teacher Information</h3>
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name">

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number">

        <label for="subjects">Subjects:</label>
        <input type="text" id="subjects" name="subjects">
    </div>

    <div id="student_fields">
        <h3>Student Information</h3>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name">

        <label for="age">Age:</label>
        <input type="number" id="age" name="age">

        <label for="gender">Gender:</label>
        <select id="gender" name="gender">
            <option value="None">None</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone">

        <label for="class_name">Class Name:</label>
        <input type="text" id="class_name" name="class_name">
    </div>

    <button type="submit">Register</button>
</form>

<script>
    const roleSelect = document.getElementById("role");
    const teacherFields = document.getElementById("teacher_fields");
    const studentFields = document.getElementById("student_fields");

    roleSelect.addEventListener("change", function () {
        const role = roleSelect.value;
        teacherFields.style.display = role === "teacher" ? "block" : "none";
        studentFields.style.display = role === "student" ? "block" : "none";
    });

    roleSelect.dispatchEvent(new Event("change"));
</script>
</body>
</html>
