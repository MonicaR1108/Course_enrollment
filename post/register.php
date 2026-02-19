<link rel="stylesheet" href="../assets/css/style.css">

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../config/db.php");

if(isset($_POST['register'])){

    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);  // simple encryption
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender   = mysqli_real_escape_string($conn, $_POST['gender']);
    $course   = mysqli_real_escape_string($conn, $_POST['course']);
    $address  = mysqli_real_escape_string($conn, $_POST['address']);
    $about    = mysqli_real_escape_string($conn, $_POST['about']);

    // PHOTO upload
    $photo_name = $_FILES['photo']['name'];
    $photo_tmp  = $_FILES['photo']['tmp_name'];

    // RESUME upload
    $resume_name = $_FILES['resume']['name'];
    $resume_tmp  = $_FILES['resume']['tmp_name'];

    // COVER LETTER upload
    $cover_name = $_FILES['cover_letter']['name'];
    $cover_tmp  = $_FILES['cover_letter']['tmp_name'];

    // upload folder path
    $photo_path  = "../uploads/" . $photo_name;
    $resume_path = "../uploads/" . $resume_name;
    $cover_path  = "../uploads/" . $cover_name;

    // Move files to uploads folder
    move_uploaded_file($photo_tmp, $photo_path);
    move_uploaded_file($resume_tmp, $resume_path);
    move_uploaded_file($cover_tmp, $cover_path);

    // Insert query
    $query = "INSERT INTO register_users 
    (fullname, email, password, phone, gender, course, address, about_yourself, photo, resume, cover_letter)
    VALUES 
    ('$fullname', '$email', '$password', '$phone', '$gender', '$course', '$address', '$about', '$photo_name', '$resume_name', '$cover_name')";

    if(mysqli_query($conn, $query)){
        echo "<script>alert('Registration Successful'); window.location='register.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <head>
    <title>Registration Form</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h2>Registration Form</h2>

<form method="POST" enctype="multipart/form-data">

    Full Name:
    <input type="text" name="fullname" required>
    <br><br>

    Email:
    <input type="email" name="email" required>
    <br><br>

    Password:
    <div id="passwordRules" style="font-size:13px; margin-top:8px;">
    <p id="len" style="color:red;">• Minimum 8 characters</p>
    <p id="upper" style="color:red;">• At least one uppercase letter</p>
    <p id="num" style="color:red;">• At least one number</p>
    <p id="special" style="color:red;">• At least one special character</p>
    </div>
    <input type="password" name="password" id="password" required>
    <br>
    <input type="checkbox" onclick="togglePassword()" style="margin-top:10px"> Show Password
    <br><br>

    Phone:
    <input type="text" name="phone" required>
    <br><br>

    Gender:
    <input type="radio" name="gender" value="Male" required> Male
    <input type="radio" name="gender" value="Female" required> Female
    <input type="radio" name="gender" value="Other" required> Other
    <br><br>

    Course:
    <select name="course" required>
        <option value="">Select Course</option>
        <option value="PHP">PHP</option>
        <option value="Java">Java</option>
        <option value="Python">Python</option>
        <option value="Software Testing">Software Testing</option>
    </select>
    <br><br>

    Address:
    <br>
    <textarea name="address" rows="4" cols="40" required></textarea>
    <br><br>

    About Yourself:
    <br>
    <textarea name="about" rows="4" cols="40" required></textarea>
    <br><br>

    Upload Photo:
    <input type="file" name="photo" required>
    <br><br>

    Upload Resume:
    <input type="file" name="resume" required>
    <br><br>

    Upload Cover Letter:
    <input type="file" name="cover_letter" required>
    <br><br>

    <button type="submit" name="register">Register</button>

    <p class="login-link">
    Already have an account? <a href="login.php">Login</a>
    </p>


</form>
<script src="/assets/javascript/register.js">
</script>


</body>
</html>
