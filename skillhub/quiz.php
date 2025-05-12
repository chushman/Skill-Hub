<?php
include 'includes/db.php';
session_start();

$course_id = $_GET['course_id'];
$user_id = $_SESSION['user_id'];

$quiz = mysqli_query($conn, "SELECT * FROM quizzes WHERE course_id = $course_id");
?>

<?php include 'includes/header.php'; ?>
<h2>Quiz</h2>
<form action="process/submit_quiz.php" method="POST">
    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
    <?php while ($q = mysqli_fetch_assoc($quiz)) : ?>
        <p><strong><?php echo $q['question']; ?></strong></p>
        <input type="radio" name="answer[<?php echo $q['id']; ?>]" value="A"> <?php echo $q['option_a']; ?><br>
        <input type="radio" name="answer[<?php echo $q['id']; ?>]" value="B"> <?php echo $q['option_b']; ?><br>
        <input type="radio" name="answer[<?php echo $q['id']; ?>]" value="C"> <?php echo $q['option_c']; ?><br>
        <input type="radio" name="answer[<?php echo $q['id']; ?>]" value="D"> <?php echo $q['option_d']; ?><br><br>
    <?php endwhile; ?>
    <button type="submit">Submit Quiz</button>
</form>
<?php include 'includes/footer.php'; ?>
