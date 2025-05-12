<?php
include 'includes/db.php';
session_start();
?php
include '../includes/db.php';
session_start();

$user_id = $_SESSION['user_id'];
$course_id = $_POST['course_id'];
$assignment_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM assignments WHERE course_id = $course_id"))['id'];

$filename = $_FILES['assignment_file']['name'];
$temp = $_FILES['assignment_file']['tmp_name'];
$path = "../uploads/" . $filename;

move_uploaded_file($temp, $path);

mysqli_query($conn, "INSERT INTO submissions (user_id, assignment_id, file_path) VALUES ($user_id, $assignment_id, '$path')");
header("Location: ../dashboard.php");

 


