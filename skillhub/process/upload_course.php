<?php
include '../includes/db.php';
session_start();

$instructor_id = $_SESSION['user_id'];

$title = $_POST['title'];
$description = $_POST['description'];
mysqli_query($conn, "INSERT INTO courses (title, description, instructor_id) VALUES ('$title', '$description', $instructor_id)");
$course_id = mysqli_insert_id($conn);

// Add Assignment
if ($_POST['assignment_title']) {
    $assignment_title = $_POST['assignment_title'];
    $instructions = $_POST['instructions'];
    mysqli_query($conn, "INSERT INTO assignments (course_id, title, instructions) VALUES ($course_id, '$assignment_title', '$instructions')");
}

// Add Quiz
if ($_POST['question']) {
    $q = $_POST['question'];
    $a = $_POST['option_a'];
    $b = $_POST['option_b'];
    $c = $_POST['option_c'];
    $d = $_POST['option_d'];
    $correct = strtoupper($_POST['correct_option']);

    mysqli_query($conn, "INSERT INTO quizzes (course_id, question, option_a, option_b, option_c, option_d, correct_option)
        VALUES ($course_id, '$q', '$a', '$b', '$c', '$d', '$correct')");
}

header("Location: ../dashboard.php");
