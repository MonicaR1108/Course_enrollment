
<?php
session_start();
include("../config/db.php");

$error = "";

if(isset($_POST['login'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);

    if(empty($email) || empty($password)){
        $error = "All fields are required.";
    } else {

        $query = "SELECT * FROM register_users WHERE email='$email' AND password='$password'";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) == 1){

            $row = mysqli_fetch_assoc($result);

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['fullname'];

            header("Location: dashboard.php");
            exit();
        }
        else{
            $error = "Invalid Email or Password";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- External CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<form method="POST" id="loginForm" novalidate>
    <h2>Login</h2>

    <?php if($error != "") { ?>
        <div class="server-error"><?php echo $error; ?></div>
    <?php } ?>

    <div class="input-group">
        <label>Email</label>
        <input type="text" id="email" name="email">
        <small class="error" id="emailError"></small>
    </div>

    <div class="input-group">
        <label>Password</label>
        <input type="password" id="password" name="password">
        <small class="error" id="passwordError"></small>
    </div>
    <br>

    <button type="submit" name="login">Login</button>

    <p class="login-link">
        Don't have an account? <a href="register.php">Register</a>
    </p>
</form>

<!-- External JavaScript -->
<script src="../assets/javascript/login.js"></script>

</body>
</html>

