<?php
include 'includes/db.php';
session_start();

$course_title = $_GET['title'] ?? '';
$user_id = $_SESSION['user_id'] ?? null;

// Get course info
$course = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM courses WHERE title = '$course_title'"));
$course_id = $course['id'];

// Check enrollment
$is_enrolled = false;
if ($user_id) {
    $check = mysqli_query($conn, "SELECT * FROM enrollments WHERE user_id = $user_id AND course_id = $course_id");
    $is_enrolled = mysqli_num_rows($check) > 0;
}
?>

<?php include 'includes/header.php'; ?>

<div style="padding: 30px;">
    <h2><?php echo $course['title']; ?></h2>
    <p><?php echo $course['description']; ?></p>

    <?php if ($user_id && !$is_enrolled && $_SESSION['role'] == 'student'): ?>
        <form action="process/enroll.php" method="POST">
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <button type="submit">Enroll in Course</button>
        </form>
    <?php elseif ($is_enrolled): ?>
        <p><strong>You are enrolled in this course.</strong></p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
