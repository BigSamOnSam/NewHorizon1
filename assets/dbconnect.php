<?php
    $conn = mysqli_connect('localhost', 'root', '', 'horizon_db');
    if (!$conn) {
        die("Database Connection Failed " . mysqli_connect_error());
    }
?>