<?php
    // Adding the header
    $pagetitle = "Student Registration";
    require_once "assets/header.php";

    $conn = mysqli_connect('localhost', 'root', '', 'horizon_db');
    if (!$conn) {
        die("Database Connection Failed " . mysqli_connect_error());
    }
    $pherr = $emerr = $pwerr = $pdperr = "";
    $msg = $firstname = $lastname= $middlename = $phone_number = $email = $profile_dp = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $middlename = $_POST['middlename'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $phone_number = $_POST['phone_number'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];
        $profile_dp = $_FILES['profile_dp'];

        // Validate Profile Picture
        $uploadDirectory = "students_dp/";
        $maxFileSize = 3 * 1024 * 1024;
        if($profile_dp['size'] <= $maxFileSize) {
            if(isset($profile_dp) && $profile_dp['error'] == 0) {
                $tempFilePath = 
            } else {
                $pdperr = "There was an error uploading this file";
            }
        } else {
            $pdperr = "File is too large please uploade 3mb image";
        }

        // Validating the phone number 
        if(preg_match("/^0[789][01]\d{8}$/", $phone_number)) {
            $sql = "SELECT * FROM students WHERE phone_number=$phone_number";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0) {
                $pherr = "Phone Number already exist";
            }
        } else {
            $pherr = "Invalid Phone Number";
        }

        // Validating the email address
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Using prepared statement to avoid SQL injection
            $sql = "SELECT * FROM students WHERE email=?";
            $stmt = mysqli_prepare($conn, $sql);

            // Bind the parameter
            mysqli_stmt_bind_param($stmt, "s", $email);

            // Execute the statement
            mysqli_stmt_execute($stmt);

            // Get the result
            $result = mysqli_stmt_get_result($stmt);

            // Check if the email already exists
            if (mysqli_num_rows($result) > 0) {
                $emerr = "Email Address already exists";
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            $emerr = "Invalid Email Address";
        }


        // Validating the passwords
        if(strlen($password) >= 8) {
            if($password != $cpassword) {
                $pwerr = "The password don't match";
            } else {
                $hashpass = password_hash($password, PASSWORD_DEFAULT);
            }    
        } else {
            $pwerr = "Your password is weak";
        }
        
        // Inserting to database
        if($pherr == "" && $pwerr == "" && $emerr == "") {

            // Use prepared statements to avoid SQL injection
            $query = $conn->prepare("INSERT INTO students(firstname, lastname, middlename, dob, gender, phone_number, email, password) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
            $query->bind_param("ssssssss", $firstname, $lastname, $middlename, $dob, $gender, $phone_number, $email, $hashpass);

            // Execute the query
            $query->execute();

            // Close the statement and connection
            $query->close();
            $conn->close();

            $firstname = $lastname= $middlename = $phone_number = $email = "";
            $msg = "Registration Successful";

        }
    }
?>
<h1>Student Registration</h1>
<h2><?=$msg?> </h2>
<div>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="profile_dp" id="image" onchange="previewImage()"/>
        <img src=""  id="imagePreview" style="max-width: 300px; max-height:300px" />
        <input type="text" name="firstname" placeholder="Enter Firstname" value="<?= $firstname ?>" required/> 
        <input type="text" name="middlename" placeholder="Enter Middlename" value="<?= $middlename ?>" /> 
        <input type="text" name="lastname" placeholder="Enter Lastname" value="<?= $lastname ?>" required/> <br/>
        <fieldset>
        <legend>Date Of Birth</legend>
            <input type="date" name="dob"/>
        </fieldset>
        <select name="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Others" selected>Others</option>
        </select>
        <input type="tel" name="phone_number" placeholder="Enter Phone Number" value="<?= $phone_number ?>" required/>
        <span><?= $pherr ?></span>
        <input type="email" name="email" placeholder="Enter E-Mail Address" value="<?= $email ?>" required/>
        <span><?= $emerr ?></span>
        <input type="password" name="password" placeholder="Enter Password" required/>
        <span><?= $pwerr ?></span>
        <input type="password" name="cpassword" placeholder="Confirm Password" required/>
        <span><?= $pwerr ?></span>
        <input type="Submit" value="Register"/>
    </form>
</div>
<script>
    const imageInput = document.getElementById('image');
    function previewImage() {
        const imagePreview = document.getElementById('imagePreview');

        if(imageInput.files && imageInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            };
            reader.readAsDataURL(imageInput.files[0])
        }
    }
</script>
</body>
</html>