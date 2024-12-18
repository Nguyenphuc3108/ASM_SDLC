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
    <title>User Management</title>
    <style>
        
    </style>
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

<!-- Add User Form -->
<h2>Add User</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <select name="role" required>
        <option value="admin">Admin</option>
        <option value="teacher">Teacher</option>
        <option value="student">Student</option>
    </select>
    <button type="submit" name="add_user">Add User</button>
</form>

<!-- Process Add User -->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Mã hóa mật khẩu
    $role = $_POST['role']; // Lưu vai trò trực tiếp

    $sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password,
            ':role' => $role, // Truyền vai trò trực tiếp
        ]);
        echo "User added successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- Edit User Form -->
<h2>Edit User</h2>
<?php
// Process Edit User
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $edit_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        ?>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <input type="text" name="username" value="<?= $user['username'] ?>" required>
            <input type="email" name="email" value="<?= $user['email'] ?>" required>
            <input type="password" name="password" placeholder="New Password (leave blank to keep)" >
            <select name="role" required>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="teacher" <?= $user['role'] == 'teacher' ? 'selected' : '' ?>>Teacher</option>
                <option value="student" <?= $user['role'] == 'student' ? 'selected' : '' ?>>Student</option>
            </select>
            <button type="submit" name="update_user">Update User</button>
        </form>
        <?php
    }
}

// Handle Update User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    $role = $_POST['role'];

    $sql = "UPDATE users SET username = :username, email = :email, role = :role";
    if ($password) {
        $sql .= ", password = :password";
    }
    $sql .= " WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $params = [
        ':username' => $username,
        ':email' => $email,
        ':role' => $role,
        ':id' => $id,
    ];
    if ($password) {
        $params[':password'] = $password;
    }

    try {
        $stmt->execute($params);
        echo "User updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- List of Users -->
<h2>User List</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    <?php
    $sql = "SELECT * FROM users";
    $stmt = $pdo->query($sql);
    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$user['id']}</td>";
        echo "<td>{$user['username']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "<td>{$user['role']}</td>";
        echo "<td>
        <div>
                <a href='?edit_id={$user['id']}'>
                <button class='edit-btn'>Edit</button>
                </a> 
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='id' value='{$user['id']}'>
                    <button type='submit' name='delete_user' class ='detele-btn'>Delete</button>
                </form>
        </div>
              </td>";
        echo "</tr>";
    }
    ?>
</table>

<!-- Process Delete User -->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([':id' => $id]);
        echo "User deleted successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

</body>
</html>
