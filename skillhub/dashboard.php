<?php
include 'includes/db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
    $name = isset($_SESSION['name']) ? $_SESSION['name'] : 'User';

?>

<?php include 'includes/header.php'; ?>

<style>
    .dashboard-container {
        padding: 40px 0;
        min-height: calc(100vh - 120px);
    }
    
    .welcome-message {
        margin-bottom: 40px;
        text-align: center;
    }
    
    .courses-section, .panel-section {
        margin-bottom: 40px;
    }
    
    .course-card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .progress {
        height: 10px;
        margin: 10px 0;
    }
    
    .btn-dashboard {
        background-color: #502c2c;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        text-decoration: none;
        display: inline-block;
        margin-top: 10px;
    }
    
    .btn-dashboard:hover {
        background-color: #65251a;
        color: white;
    }
    
    .instructor-panel {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
</style>

<div class="container dashboard-container">
    <div class="welcome-message">
        <h2>Welcome to Your Dashboard, <?php echo htmlspecialchars($name); ?></h2>
        <p class="lead"><?php echo $role == 'student' ? 'Track your learning progress' : 'Manage your courses'; ?></p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?php if ($role == 'student'): ?>
                <div class="courses-section">
                    <h3>Your Enrolled Courses</h3>
                    <?php
                    $sql = "SELECT courses.id, courses.title, enrollments.progress 
                            FROM enrollments 
                            JOIN courses ON enrollments.course_id = courses.id 
                            WHERE enrollments.user_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="course-card">';
                            echo '<h4>' . htmlspecialchars($row['title']) . '</h4>';
                            echo '<div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: ' . $row['progress'] . '%;" 
                                         aria-valuenow="' . $row['progress'] . '" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                  </div>';
                            echo '<p>Progress: ' . $row['progress'] . '%</p>';
                            echo '<a href="course_detail.php?id=' . $row['id'] . '" class="btn-dashboard">Continue Learning</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="alert alert-info">You haven\'t enrolled in any courses yet. <a href="courses.php">Browse courses</a> to get started!</div>';
                    }
                    ?>
                </div>
            <?php else: ?>
                <div class="panel-section">
                    <div class="instructor-panel">
                        <h3>Instructor Panel</h3>
                        <a href="instructor_upload.php" class="btn btn-primary">Upload New Course</a>
                    </div>
                    
                    <h4>Your Courses:</h4>
                    <?php
                    $sql = "SELECT * FROM courses WHERE instructor_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result && $result->num_rows > 0) {
                        while ($course = $result->fetch_assoc()) {
                            echo '<div class="course-card">';
                            echo '<h4>' . htmlspecialchars($course['title']) . '</h4>';
                            echo '<p>' . htmlspecialchars(substr($course['description'], 0, 100)) . '...</p>';
                            echo '<a href="course_detail.php?id=' . $course['id'] . '" class="btn-dashboard">Manage Course</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="alert alert-info">You haven\'t created any courses yet.</div>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Quick Actions</h4>
                </div>
                <div class="card-body">
                    <?php if ($role == 'student'): ?>
                        <a href="courses.php" class="btn btn-secondary w-100 mb-2">Browse Courses</a>
                        <a href="profile.php" class="btn btn-outline-secondary w-100 mb-2">Update Profile</a>
                    <?php else: ?>
                        <a href="instructor_upload.php" class="btn btn-secondary w-100 mb-2">Create New Course</a>
                        <a href="instructor_stats.php" class="btn btn-outline-secondary w-100 mb-2">View Statistics</a>
                    <?php endif; ?>
                    <a href="settings.php" class="btn btn-outline-dark w-100">Account Settings</a>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Recent Activity</h4>
                </div>
                <div class="card-body">
                    <?php
                    $activity_sql = "SELECT * FROM user_activity 
                                    WHERE user_id = ? 
                                    ORDER BY activity_date DESC 
                                    LIMIT 5";
                    $stmt = $conn->prepare($activity_sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $activity_result = $stmt->get_result();
                    
                    if ($activity_result && $activity_result->num_rows > 0) {
                        echo '<ul class="list-group list-group-flush">';
                        while ($activity = $activity_result->fetch_assoc()) {
                            echo '<li class="list-group-item">' 
                                 . htmlspecialchars($activity['description']) 
                                 . '<br><small class="text-muted">' 
                                 . date('M j, Y g:i a', strtotime($activity['activity_date'])) 
                                 . '</small></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<p class="text-muted">No recent activity</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>