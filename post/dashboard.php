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

//========================project=====================
/* ================= CREATE PROJECT ================= */
/*if(isset($_POST['create_project'])){
    $project_name = mysqli_real_escape_string($conn, $_POST['project_name']);
    $description  = mysqli_real_escape_string($conn, $_POST['description']);

    mysqli_query($conn, "INSERT INTO projects (user_id, project_name, description)
                         VALUES ('$user_id','$project_name','$description')");
    header("Location: dashboard.php?section=projects");
    exit();
}
*/
if(isset($_POST['create_project'])){

    $project_name = mysqli_real_escape_string($conn, $_POST['project_name']);
    $description  = mysqli_real_escape_string($conn, $_POST['description']);

    // MULTI-SELECT TECHNOLOGIES
    $techs = $_POST['technologies'] ?? ''; 
            //  ? implode(',', $_POST['technologies']) 
            //  : '';

    mysqli_query($conn, "INSERT INTO projects (user_id, project_name, description, technologies)
                         VALUES ('$user_id', '$project_name', '$description', '$techs')");

    header("Location: dashboard.php?section=projects");
    exit();
}

/* ================= UPLOAD PROJECT FILES ================= */
if(isset($_POST['upload_project'])){
    foreach($_FILES['project_files']['name'] as $key => $fileName){
        if(!empty($fileName)){
            $tmpName = $_FILES['project_files']['tmp_name'][$key];
            $newName = time()."_".$fileName;
            move_uploaded_file($tmpName,"../uploads/projects/".$newName);
            mysqli_query($conn,"INSERT INTO user_projects(user_id,file_name)
                                VALUES('$user_id','$newName')");
        }
    }
    header("Location: dashboard.php?section=projects");
    exit();
}



/* ================= IMPORT CSV ================= */
if(isset($_POST['import_csv'])){
    $project_id = $_POST['project_id'];
    $csv = $_FILES['csv_file']['name'];
    $tmp = $_FILES['csv_file']['tmp_name'];
    $newName = time().'_'.$csv;
    move_uploaded_file($tmp,"../uploads/projects/".$newName);

    mysqli_query($conn,"INSERT INTO project_files(project_id,file_name,file_type)
                        VALUES('$project_id','$newName','csv')");
    header("Location: dashboard.php?section=projects");
    exit();
}
//===========export=============


/* ================= DELETE PROJECT ================= */
if(isset($_GET['delete_project'])){
    $id = $_GET['delete_project'];
    $q = mysqli_query($conn,"SELECT * FROM user_projects WHERE id='$id' AND user_id='$user_id'");
    if($r=mysqli_fetch_assoc($q)){
        unlink("../uploads/projects/".$r['file_name']);
        mysqli_query($conn,"DELETE FROM user_projects WHERE id='$id'");
    }
    header("Location: dashboard.php?section=projects");
    exit();
}


//==========================profile==update=============

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
<div class="projects-flex">

    <!-- CREATE PROJECT -->
    <div class="project-box">
        <h3>Create Project</h3>
        <form method="POST">
            <input type="text" name="project_name" required>
            <textarea name="description"></textarea>
            <label><b>Technologies Used:</b></label>
            <input type="text" id="techDisplay" readonly>
            <input type="hidden" name="technologies" id="techHidden">
            <input type="text" id="techInput">
     <div id="techSuggestions"></div>
</form>
     </div>

    <!-- UPLOAD FILES -->
    <div class="project-box">
        <h3>Upload Files</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="project_files[]" multiple required>
            <button name="upload_project">Upload</button>
            <br><br>
         <button name="create_project">Create</button>
        </form>
    </div>

</div>

<hr>

<h3>Your Uploaded Projects</h3>

<div class="project-list">

<?php
$q = mysqli_query($conn,"SELECT * FROM user_projects WHERE user_id='$user_id' ORDER BY id DESC");
if(mysqli_num_rows($q)>0){
while($p=mysqli_fetch_assoc($q)){
?>
<div class="project-item">




    <div class="project-name">
        <a href="../uploads/projects/<?php echo $p['file_name']; ?>" target="_blank">
            <?php echo $p['file_name']; ?>
        </a>
    </div>

    <div class="project-actions">
        <a href="../uploads/projects/<?php echo $p['file_name']; ?>" download>Download</a>
        <a href="edit_project.php?id=<?php echo $p['id']; ?>">Edit</a>
        <a href="dashboard.php?section=projects&delete_project=<?php echo $p['id']; ?>"
           onclick="return confirm('Delete this project?')" class="danger">
           Delete
        </a>
        <a href="dashboard.php?export_project=<?php echo $p['id']; ?>"
   style="margin-left:10px; color:#16a34a; font-weight:500;">
   Export CSV
</a>

    </div>

    <!-- <form method="POST" enctype="multipart/form-data" class="csv-form">
        <input type="hidden" name="project_id" value="<?php echo $p['id']; ?>">
        <input type="file" name="csv_file" required>
        <button name="import_csv">Import CSV</button>
    </form> -->

</div>
<?php }} else { echo "<p>No projects uploaded yet.</p>"; } ?>

</div>
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
.section{display:none}
.section.active{display:block}

/* ===== PROJECT STYLES ONLY ===== */
.projects-flex{
    display:flex;
    gap:20px;
    margin-bottom:20px;
}
.project-box{
    flex:1;
    background:#f8fafc;
    padding:15px;
    border-radius:8px;
}
.project-box input,
.project-box textarea{
    width:100%;
    padding:8px;
    margin-bottom:10px;
}
.project-list{
    display:flex;
    flex-direction:column;
    gap:15px;
}
.project-item{
    border:1px solid #ccc;
    padding:12px;
    border-radius:8px;
}
.project-actions a{
    margin-right:10px;
}
.csv-form{
    margin-top:10px;
    display:flex;
    gap:10px;
}
.danger{color:red}

#techInput{
    width:100%;
    padding:8px;
    margin-bottom:6px;
}

#techSuggestions{
    border:1px solid #cbd5e1;
    background:#fff;
    max-height:140px;
    overflow-y:auto;
    border-radius:6px;
}

#techSuggestions div{
    padding:8px;
    cursor:pointer;
}

#techSuggestions div:hover{
    background:#2563eb;
    color:#fff;
}


</style>
<!-- <script src="../assets/javascript/dashboard.js"></script> -->
 <script>
const technologies = [
    "HTML","CSS","JavaScript","PHP","MySQL","Python",
    "Bootstrap","React","Node.js","Laravel","Django",
    "MongoDB","Git","jQuery"
];

let selectedTech = [];

const input = document.getElementById("techInput");
const display = document.getElementById("techDisplay");
const hidden = document.getElementById("techHidden");
const box = document.getElementById("techSuggestions");

input.addEventListener("keyup", function () {
    const val = this.value.toLowerCase();
    box.innerHTML = "";

    if (!val) return;

    technologies.forEach(t => {
        if (t.toLowerCase().includes(val) && !selectedTech.includes(t)) {
            let div = document.createElement("div");
            div.textContent = t;
            div.onclick = () => addTech(t);
            box.appendChild(div);
        }
    });
});

function addTech(tech){
    selectedTech.push(tech);
    display.value = selectedTech.join(", ");
    hidden.value = selectedTech.join(",");
    input.value = "";
    box.innerHTML = "";
}
</script>

</body>
</html>











