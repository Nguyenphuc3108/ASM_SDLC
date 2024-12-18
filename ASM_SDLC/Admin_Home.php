<?php
// home.php
session_start();
require 'db_Connection.php';

// Kiểm tra quyền admin
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <style>
       /* General Body Styles */
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f7fc;
    color: #333;
}

/* Header Styles */
.header {
    background-color: #004d99;
    color: white;
    text-align: center;
    padding: 30px 0;
}

.header h1 {
    margin: 0;
    font-size: 2.5rem;
}

/* Menu Styles */
.menu {
    display: flex;
    justify-content: center;
    background-color: #007acc;
    padding: 15px 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.menu a {
    color: white;
    text-decoration: none;
    margin: 0 20px;
    font-size: 1.2rem;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.menu a:hover {
    background-color: #005b8d;
}
.menu a.logout-btn {
    margin-left: auto; /* Đẩy nút này sang bên phải */
    background-color: #ff4d4d; /* Màu nền nổi bật cho nút */
    color: white;
    font-weight: bold;
}

.menu a.logout-btn:hover {
    background-color: #cc0000; /* Màu nền khi rê chuột */
}


/* Image Section Styles */
.img {
    text-align: center;
    margin-top: 30px;
}

.img img {
    width: 100%;
    max-width: 600px;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Footer Styles */
footer {
    background-color: #f1f1f1;
    padding: 30px 20px;
    text-align: center;
    font-size: 0.9rem;
    border-top: 2px solid #007acc;
}

footer h4 {
    color: #004d99;
    margin-bottom: 20px;
}

footer a {
    color: #007acc;
    text-decoration: none;
}

footer a:hover {
    text-decoration: underline;
}

footer p {
    margin: 5px 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .menu {
        flex-direction: column;
        align-items: center;
    }

    .menu a {
        margin: 10px 0;
    }

    .img img {
        max-width: 100%;
    }
}

    </style>
</head>

<body>
    <header>
        <div class="header">
            <h1>Welcome to Admin </h1>
        </div>

        <div class="menu">
                <a href="User_Management.php">User Management</a>
                <a href="Course_management.php">Course Management</a>
                <a href="Class_Management.php">Class Management</a>
                <a href="Attendance_Management.php">Attendance Management</a>
                <a href="login.php" class="logout-btn">Logout</a>
        </div>    
    </header>
    <div class="img">
        <img src="tuyensinh.jpg" alt="">
    </div>
    <footer>
        <div class="footer">
            <h4>For more information or any queries, please contact:</h4>
            <p><strong>FPT Polytechnic International in Hanoi</strong></p>
            <p><strong>Address:</strong></p>
            <p>BTEC: BTEC FPT Building, Trinh Van Bo Street, Nam Tu Liem District, Hanoi.</p>
            <p>Melbourne: Melbourne Polytechnic Building D, Trinh Van Bo Street, Nam Tu Liem District, Hanoi.</p>
            <p><strong>Email:</strong></p>
            <p>Academic Department: <a href="mailto:Academic.btec.hn@fe.edu.vn">Academic.btec.hn@fe.edu.vn</a></p>
            <p>SRO Department: <a href="mailto:sro.btec.hn@fe.edu.vn">sro.btec.hn@fe.edu.vn</a></p>
            <p>SE Department: <a href="mailto:se.btec.hn@fe.edu.vn">se.btec.hn@fe.edu.vn</a></p>
            <p>Finance Department: <a href="mailto:accounting.btec.hn@fe.edu.vn">accounting.btec.hn@fe.edu.vn</a></p>
            <p><strong>Hotline:</strong> 024 730 99 588</p>
        </div>
    </footer>

</body>

</html>