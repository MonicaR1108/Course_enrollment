<?php
$conn = mysqli_connect("localhost", "root", "", "registration_form");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
