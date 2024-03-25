<?php 
    // Adding the header
    $pagetitle = "Login to New Horizon";
    require_once "assets/header.php";
    
    if (isset($_SESSION['alex_id'])) {
        echo "Welcome to Dashboad";
    } else {
        header('Location: login.php');
    }
?>