<?php
include 'includes/db.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$users = mysqli_query($conn, "SELECT * FROM users");
$courses = mysqli_query($conn, "SELECT * FROM courses");

?>

<h2>Admin Dashboard</h2>

<h3>Manage Users</h3>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = mysqli_fetch_assoc($users)) : ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['role']; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<h3>Manage Courses</h3>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Instructor</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($course = mysqli_fetch_assoc($courses)) : ?>
            <tr>
                <td><?php echo $course['id']; ?></td>
                <td><?php echo $course['title']; ?></td>
                <td><?php echo $course['instructor_id']; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
