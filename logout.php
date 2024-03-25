<?php
    session_start();
    unset($_SESSION['alex_id']);

    header('Location: index.php')
?>