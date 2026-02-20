<?php
include("../config/db.php");

if(isset($_POST['email'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $query = "SELECT * FROM register_users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        echo "exists";
    } else {
        echo "available";
    }
}
?>