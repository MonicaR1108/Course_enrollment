<link rel="Stylesheet" href="../assets/css/dashboard.css">

<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
// Fetch logged in user data
$getUser = mysqli_query($conn, "SELECT * FROM register_users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($getUser);

//================course=============================

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

// ================= PROJECTS FOLDER =================
if(!is_dir("../uploads/projects")){
    mkdir("../uploads/projects", 0777, true);
}

// ================= MULTIPLE PROJECT UPLOAD =================
if(isset($_POST['upload_project'])){

    foreach($_FILES['project_files']['name'] as $key => $fileName){

        if(!empty($fileName)){

            $tmpName = $_FILES['project_files']['tmp_name'][$key];
            $newName = time() . "_" . $fileName;
            $uploadPath = "../uploads/projects/" . $newName;

            if(move_uploaded_file($tmpName, $uploadPath)){
                mysqli_query($conn, "INSERT INTO user_projects (user_id, file_name)
                                     VALUES ('$user_id', '$newName')");
            }
        }
    }

    header("Location: dashboard.php?section=projects");
    exit();
}

// ================= DELETE PROJECT =================
if(isset($_GET['delete_project'])){

    $project_id = $_GET['delete_project'];

    $getFile = mysqli_query($conn, "SELECT * FROM user_projects 
                                    WHERE id='$project_id' AND user_id='$user_id'");
    $file = mysqli_fetch_assoc($getFile);

    if($file){
        $filePath = "../uploads/projects/" . $file['file_name'];

        if(file_exists($filePath)){
            unlink($filePath);
        }

        mysqli_query($conn, "DELETE FROM user_projects WHERE id='$project_id'");
    }

    header("Location: dashboard.php?section=projects");
    exit();
}

if(isset($_POST['update_profile'])){

    // Fetch current user data
    $getUser = mysqli_query($conn, "SELECT * FROM register_users WHERE id='$user_id'");
    $currentUser = mysqli_fetch_assoc($getUser);

    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $gender   = mysqli_real_escape_string($conn, $_POST['gender']);
    $address  = mysqli_real_escape_string($conn, $_POST['address']);
    $about    = mysqli_real_escape_string($conn, $_POST['about']);

    // OLD FILE VALUES
    $photo_name  = $currentUser['photo'];
    $resume_name = $currentUser['resume'];
    $cover_name  = $currentUser['cover_letter'];

    // ================= PHOTO =================
    if(!empty($_FILES['photo']['name'])){

        $newPhoto = time() . "_" . $_FILES['photo']['name'];
        $photoPath = "../uploads/" . $newPhoto;

        if(move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)){

            if(!empty($photo_name) && file_exists("../uploads/" . $photo_name)){
                unlink("../uploads/" . $photo_name);
            }

            $photo_name = $newPhoto;
        }
    }

    // ================= RESUME =================
    if(!empty($_FILES['resume']['name'])){

        $newResume = time() . "_" . $_FILES['resume']['name'];
        $resumePath = "../uploads/" . $newResume;

        if(move_uploaded_file($_FILES['resume']['tmp_name'], $resumePath)){

            if(!empty($resume_name) && file_exists("../uploads/" . $resume_name)){
                unlink("../uploads/" . $resume_name);
            }

            $resume_name = $newResume;
        }
    }

    // ================= COVER LETTER =================
    if(!empty($_FILES['cover_letter']['name'])){

        $newCover = time() . "_" . $_FILES['cover_letter']['name'];
        $coverPath = "../uploads/" . $newCover;

        if(move_uploaded_file($_FILES['cover_letter']['tmp_name'], $coverPath)){

            if(!empty($cover_name) && file_exists("../uploads/" . $cover_name)){
                unlink("../uploads/" . $cover_name);
            }

            $cover_name = $newCover;
        }
    }

    // ================= UPDATE QUERY =================
    $updateQuery = "UPDATE register_users SET 
                    fullname='$fullname',
                    phone='$phone',
                    gender='$gender',
                    address='$address',
                    about_yourself='$about',
                    photo='$photo_name',
                    resume='$resume_name',
                    cover_letter='$cover_name'
                    WHERE id='$user_id'";

    if(mysqli_query($conn, $updateQuery)){
        header("Location: dashboard.php?section=profile&success=1");
        exit();
    } else {
        echo "Update Failed: " . mysqli_error($conn);
    }
}


// ================= FETCH UPDATED DATA =================
$query = "SELECT * FROM register_users WHERE id='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="navbar">
    <h2>Moni's Tutorial</h2>

    <ul>
        <li><a href="dashboard.php">Home</a></li>
        <li><a href="dashboard.php?section=profile">Profile</a></li>
        <li><a href="dashboard.php?section=projects">projects</a></li>
        <li><a href="dashboard.php?section=course">Course</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="container">
<div class="card">

<!-- HOME -->
<div id="home" class="section <?php if(!isset($_GET['section'])) echo 'active'; ?>">
    <h2>Welcome <?php echo $user['fullname']; ?> 🎉</h2>
    <p><b>Selected Course:</b> <?php echo $user['course']; ?></p>
</div>

<!-- PROFILE -->
<div id="profile" class="section <?php if(isset($_GET['section']) && $_GET['section']=='profile') echo 'active'; ?>">


<!-- 
    <form method="POST"> -->
    <form method="POST" enctype="multipart/form-data">
        <h2>Edit Profile</h2>
        <img src="../uploads/<?php echo $user['photo']; ?>" width="120">
        
        <p>
            Full Name:<br>
            <input type="text" name="fullname"
                   value="<?php echo $user['fullname']; ?>" required>
        </p>

        <p>Email: <?php echo $user['email']; ?></p>

        <p>
            Phone:<br>
            <input type="text" name="phone"
                   value="<?php echo $user['phone']; ?>" required>
        </p>

        <p>
            Gender:<br>
            <select name="gender">
                <option value="Male" <?php if($user['gender']=="Male") echo "selected"; ?>>Male</option>
                <option value="Female" <?php if($user['gender']=="Female") echo "selected"; ?>>Female</option>
                <option value="Other" <?php if($user['gender']=="Other") echo "selected"; ?>>Other</option>
            </select>
        </p>

        <p>
            Address:<br>
            <textarea name="address"><?php echo $user['address']; ?></textarea>
        </p>

        <p>
            About:<br>
            <textarea name="about"><?php echo $user['about_yourself']; ?></textarea>
        </p>

         
    <p>
    Change Profile Photo:<br>
    <input type="file" name="photo">
</p>

<p>
    Change Resume:<br>
    <input type="file" name="resume">
</p>

<p>
    Change Cover Letter:<br>
    <input type="file" name="cover_letter">
</p>

        <button type="submit" name="update_profile">Update Profile</button>

    </form>
</div>

<!-- SETTINGS -->
<div id="projects" class="section <?php if(isset($_GET['section']) && $_GET['section']=='projects') echo 'active'; ?>">

<h2>Upload Projects</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="project_files[]" multiple required>
    <br><br>
    <button type="submit" name="upload_project">Upload Projects</button>
</form>

<hr>

<h3>Your Uploaded Projects</h3>

<?php
$projectQuery = mysqli_query($conn, "SELECT * FROM user_projects 
                                     WHERE user_id='$user_id' 
                                     ORDER BY id DESC");

if(mysqli_num_rows($projectQuery) > 0){

    while($project = mysqli_fetch_assoc($projectQuery)){
?>

<div style="margin-bottom:10px; padding:8px; border:1px solid #ccc; border-radius:5px;">

    <a href="../uploads/projects/<?php echo $project['file_name']; ?>" target="_blank">
        <?php echo $project['file_name']; ?>
    </a>

    <a href="dashboard.php?section=projects&delete_project=<?php echo $project['id']; ?>"
       onclick="return confirm('Are you sure you want to delete this project?')"
       style="color:red; margin-left:15px;">
       Remove
    </a>

</div>

<?php 
    }
} else {
    echo "<p>No projects uploaded yet.</p>";
}
?>

</div>

<!-- COURSE -->
<div id="course" class="section <?php if(isset($_GET['section']) && $_GET['section']=='course') echo 'active'; ?>">
   <!-- <div id="course" class="section"> -->
            <h2>Course Enrollment</h2>
         <p>You selected this course during registration:</p>

            <div class="course-box">
                <p><b>Click below to learn your course:</b></p>
<br>
                <a href="<?php echo $course_link; ?>" target="_blank">
                    <?php echo $course; ?> Tutorial (W3Schools)
                </a>
            </div>
<br>
            <a class="btn" href="<?php echo $course_link; ?>" target="_blank">
                Start Learning Now 🚀
            </a>
        </div>

</div>
</div>

<style>
.section { display:none; }
.section.active { display:block; }
.navbar ul { list-style:none; }
.navbar li { display:inline; margin-right:15px; }
</style>

</body>
</html>











