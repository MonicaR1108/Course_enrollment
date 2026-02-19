<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM register_users WHERE id='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

$course = $user['course'];

// W3Schools link based on course
$course_link = "#";

if($course == "PHP"){
    $course_link = "https://www.w3schools.com/php/";
}
elseif($course == "HTML"){
    $course_link = "https://www.w3schools.com/html/";
}
elseif($course == "CSS"){
    $course_link = "https://www.w3schools.com/css/";
    }
elseif($course == "JavaScript"){
    $course_link = "https://www.w3schools.com/js/";
}
elseif($course == "Python"){
    $course_link = "https://www.w3schools.com/python/";
}
elseif($course == "MySQL"){
    $course_link = "https://www.w3schools.com/mysql/";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>



<body>

<!-- NAVBAR -->
<div class="navbar">
    <h2>Moni's Tutorial</h2>

    <ul>
        <li><a href="#" onclick="showSection('home')">Home</a></li>
        <li><a href="#" onclick="showSection('profile')">Profile</a></li>
        <li><a href="#" onclick="showSection('settings')">Settings</a></li>
        <li><a href="#" onclick="showSection('course')">Course Enroll</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- CONTENT -->
<div class="container">

    <div class="card">

        <!-- HOME SECTION -->
        <div id="home" class="section active">
            <h2>Welcome <?php echo $user['fullname']; ?> 🎉</h2>
            <p>Excited to dive into this new chapter of learning!</p>
            <p><b>Selected Course:</b> <?php echo $user['course']; ?></p>
            <p>Leveling up! Can't wait to see where this course takes me.</p>
            <img src="../icons/courseimg.png" alt="icon" width="550px" height="300px">

        </div>

        <!-- PROFILE SECTION -->
        <div id="profile" class="section">
            <h2>Profile Details</h2>

            <img src="../uploads/<?php echo $user['photo']; ?>" class="profile-img">

            <p><b>Full Name:</b> <?php echo $user['fullname']; ?></p>
            <p><b>Email:</b> <?php echo $user['email']; ?></p>
            <p><b>Phone:</b> <?php echo $user['phone']; ?></p>
            <p><b>Gender:</b> <?php echo $user['gender']; ?></p>
            <p><b>Address:</b> <?php echo $user['address']; ?></p>
            <p><b>About Yourself:</b> <?php echo $user['about_yourself']; ?></p>

            <p><b>Resume:</b>
                <a href="../uploads/<?php echo $user['resume']; ?>" target="_blank">View Resume</a>
            </p>

            <p><b>Cover Letter:</b>
                <a href="../uploads/<?php echo $user['cover_letter']; ?>" target="_blank">View Cover Letter</a>
            </p>
        </div>

        <!-- SETTINGS SECTION -->
        <div id="settings" class="section">
            <h3>Theme setting</h3>
            <br>
             <label>
            <input type="radio" name="theme" value="light" checked>
            Light
            </label>
            <label>
             <input type="radio" name="theme" value="dark">
            Dark
            </label>
<br><br>

              <h3>Time Zone</h3><br>
  <label>
    <input type="radio" name="timezone" value="Asia/Kolkata" checked>
    Asia/Kolkata (IST)
  </label>
  <label>
    <input type="radio" name="timezone" value="UTC">
    UTC
  </label>
  <label>
    <input type="radio" name="timezone" value="America/New_York">
    America/New York
  </label><br><br>
            <p><b>Currently Logged In As:</b> <?php echo $user['email']; ?></p>

            <h3>Course Progress</h3>
  <label>
    <input type="radio" name="progress" value="show" <?php if($progress=='show') echo 'checked'; ?>>
    Show Progress
  </label>
  <label>
    <input type="radio" name="progress" value="hide" <?php if($progress=='hide') echo 'checked'; ?>>
    Hide Progress
  </label>
  <button type="submit">Save Settings</button>
</div>


        </div>

        <!-- COURSE ENROLL SECTION -->
        <div id="course" class="section">
            <h2>Course Enrollment</h2>
         <p>You selected this course during registration:</p>

            <div class="course-box">
                <p><b>Click below to learn your course:</b></p>

                <a href="<?php echo $course_link; ?>" target="_blank">
                    <?php echo $course; ?> Tutorial (W3Schools)
                </a>
            </div>

            <a class="btn" href="<?php echo $course_link; ?>" target="_blank">
                Start Learning Now 🚀
            </a>
        </div>

    </div>

</div>

<!-- SCRIPT -->
<script src="../assets/javascript/dashboard.js"></script>
</script>

</body>
</html>
