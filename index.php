<?php
    // Adding the header
    $pagetitle = "Welcome to New Horizon";
    require_once "assets/header.php";

    $pdperr = "";
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $profile_dp = $_FILES['profile_dp'];

        $uploadDirectory = "students_dp/";
        $maxSize = 3 * 1024 * 1024;
        if($profile_dp['size'] <= $maxSize) {
            $pdperr = "File is Good " . ($profile_dp['size'])/ (1024 * 1024) . "mb";
        } else {
            $pdperr = "File size is too large " . ($profile_dp['size'])/ (1024 * 1024) . "mb";
        }
    
    }
?>

<h1>Welcome To New horizon</h1>
<h3><?= $pdperr; ?></h3>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="profile_dp" id="image" onchange="previewImage()"/>
    <img src=""  id="imagePreview" style="max-width: 300px; max-height:300px" />
    <input type="submit" value="Submit">
</form>

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