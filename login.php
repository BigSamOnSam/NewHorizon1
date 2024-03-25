<?php
    // Adding the header
    $pagetitle = "Login to New Horizon";
    require_once "assets/header.php";

    

    // Initializing the email and password
    $email = $err = "";

    // Validation the login
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $pass = $_POST['password'];

        // Using prepared statement to avoid SQL injection
        $sql = $conn->prepare("SELECT * FROM students WHERE email=?");

        // Bind the parameter
        $sql->bind_param("s", $email);

        // Execute the statement
        $sql->execute();

        // Get the result
        // $result = mysqli_get_result($sql);
        $result = mysqli_stmt_get_result($sql);

        // Check if the email already exists
        if (mysqli_num_rows($result) == 1) {
            $studinfo = mysqli_fetch_assoc($result);
            if(password_verify($pass, $studinfo['password'])) {
                // echo "<h1>Login Successfully</h1>";
                $_SESSION['alex_id'] = $studinfo['student_id'];
                header('Location: dashboard.php');
            } else {
                $err = "Invalid login details";
            }
        } else {
            $err = "Invalid login details";
        }

        // Close the statement
        $sql->close();
    }
?>

<form method="post">
    <input type="email" name="email" placeholder="Enter E-Mail Address or Phone Number" value="<?= $email ?>" required/>
    <input type="password" name="password" placeholder="Enter Password" required/>
    <span><?= $err ?></span>
    <input type="Submit" value="Login"/>
</form>