<link rel="stylesheet" href="../assets/css/style.css">

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../config/db.php");

$emailError = "";

if(isset($_POST['register'])){

    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender   = mysqli_real_escape_string($conn, $_POST['gender']);
    $course   = mysqli_real_escape_string($conn, $_POST['course']);
    $address  = mysqli_real_escape_string($conn, $_POST['address']);
    $about    = mysqli_real_escape_string($conn, $_POST['about']);

    // Check if email exists
    $checkEmail = "SELECT id FROM register_users WHERE email = '$email'";
    $result = mysqli_query($conn, $checkEmail);

    if(mysqli_num_rows($result) > 0){
        $emailError = "This email already exists. <a href='login.php'>Login</a>";
    } 
    else {

        // Allowed extensions
        $allowed_photo = ['jpg','jpeg','png'];
        $allowed_docs  = ['pdf','doc','docx'];

        // Max file size (2MB)
        $maxSize = 2 * 1024 * 1024;

        // PHOTO
        $photo_name = $_FILES['photo']['name'];
        $photo_tmp  = $_FILES['photo']['tmp_name'];
        $photo_size = $_FILES['photo']['size'];
        $photo_ext  = strtolower(pathinfo($photo_name, PATHINFO_EXTENSION));

        // RESUME
        $resume_name = $_FILES['resume']['name'];
        $resume_tmp  = $_FILES['resume']['tmp_name'];
        $resume_size = $_FILES['resume']['size'];
        $resume_ext  = strtolower(pathinfo($resume_name, PATHINFO_EXTENSION));

        // COVER LETTER
        $cover_name = $_FILES['cover_letter']['name'];
        $cover_tmp  = $_FILES['cover_letter']['tmp_name'];
        $cover_size = $_FILES['cover_letter']['size'];
        $cover_ext  = strtolower(pathinfo($cover_name, PATHINFO_EXTENSION));

        // Validate extensions
        if(!in_array($photo_ext, $allowed_photo)){
            die("Photo must be JPG, JPEG or PNG only.");
        }

        if(!in_array($resume_ext, $allowed_docs)){
            die("Resume must be PDF, DOC or DOCX only.");
        }

        if(!in_array($cover_ext, $allowed_docs)){
            die("Cover Letter must be PDF, DOC or DOCX only.");
        }

        // Validate file size
        if($photo_size > $maxSize || $resume_size > $maxSize || $cover_size > $maxSize){
            die("Each file must be less than 2MB.");
        }

        // Rename files
        $photo_new  = time() . "_photo." . $photo_ext;
        $resume_new = time() . "_resume." . $resume_ext;
        $cover_new  = time() . "_cover." . $cover_ext;

        $upload_folder = "../uploads/";

        // Move files
        move_uploaded_file($photo_tmp, $upload_folder . $photo_new);
        move_uploaded_file($resume_tmp, $upload_folder . $resume_new);
        move_uploaded_file($cover_tmp, $upload_folder . $cover_new);

        // Insert into database
        $query = "INSERT INTO register_users 
        (fullname, email, password, phone, gender, course, address, about_yourself, photo, resume, cover_letter)
        VALUES 
        ('$fullname', '$email', '$password', '$phone', '$gender', '$course', '$address', '$about', '$photo_new', '$resume_new', '$cover_new')";

        if(mysqli_query($conn, $query)){
            header("Location: register.php?success=1");
            exit();
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
</head>
<body>

<?php
if(isset($_GET['success'])){
    echo "<div style='color:green; text-align:center; font-weight:bold;'>
            Registration Successful
          </div>";
}

if($emailError != ""){
    echo "<div style='color:red; text-align:center; font-weight:bold;'>
            $emailError
          </div>";
}
?>

<h2>Registration Form</h2>

<form method="POST" enctype="multipart/form-data">

Full Name:
<input type="text" name="fullname" required>
<br><br>

Email:
<input type="email" name="email" required>
<br><br>

Password:
<input type="password" name="password" id="password" required onkeyup="validatePassword()">
<br>
<small id="passwordError" style="color:red;"></small>
<br>
<input type="checkbox" onclick="togglePassword()"> Show Password

<div id="passwordRules" style="font-size:13px; margin-top:8px;">
    <p id="len" style="color:red;">• Minimum 8 characters</p>
    <p id="upper" style="color:red;">• At least one uppercase letter</p>
    <p id="num" style="color:red;">• At least one number</p>
    <p id="special" style="color:red;">• At least one special character</p>
</div>
<br>


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
<input type="file" name="photo" accept=".jpg,.jpeg,.png" required>
<br><br>

Upload Resume:
<input type="file" name="resume" accept=".pdf,.doc,.docx" required>
<br><br>

Upload Cover Letter:
<input type="file" name="cover_letter" accept=".pdf,.doc,.docx" required>
<br><br>

<button type="submit" name="register">Register</button>

<p>
Already have an account? <a href="login.php">Login</a>
</p>

</form>
<br>

<script src="../assets/javascript/register.js"></script>

</body>
</html>