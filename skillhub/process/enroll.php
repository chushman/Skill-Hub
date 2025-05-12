<?php
include '../includes/db.php';
include '../includes/activity_logger.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$course_id = $_POST['course_id'];

// Check if already enrolled
$check = $conn->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
$check->bind_param("ii", $user_id, $course_id);
$check->execute();

if ($check->get_result()->num_rows == 0) {
    // Enroll user
    $stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id, progress) VALUES (?, ?, 0)");
    $stmt->bind_param("ii", $user_id, $course_id);
    
    if ($stmt->execute()) {
        // Log activity
        $course = $conn->query("SELECT title FROM courses WHERE id = $course_id")->fetch_assoc();
        log_activity($conn, $user_id, 'enrollment', 'Enrolled in course: ' . $course['title']);
        $_SESSION['success'] = "Enrollment successful!";
    } else {
        $_SESSION['error'] = "Enrollment failed";
    }
}

header("Location: ../course_detail.php?title=" . urlencode($course['title']));
?>