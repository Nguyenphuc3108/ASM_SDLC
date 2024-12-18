<?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "SE06302";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if (! $conn) {
        die("Connection failed:". mysqli_connect_error());
    }else{
        //echo "<script>alert('Connected successfully!');</script>" . "<br>";
    }
    $sql = "SELECT * FROM students";
    $result = mysqli_query($conn,$sql);
    ?>