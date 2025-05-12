<?php
require_once 'includes/header.php';
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch fresh data from database
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        throw new Exception("User not found");
    }
    
    // Update session with fresh data
    $_SESSION = array_merge($_SESSION, $user);
    $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$regDate = date('F j, Y', strtotime($user['created_at']));
?>

<style>
    .profile-container {
        max-width: 800px;
        margin: 30px auto;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .profile-card {
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: none;
        overflow: hidden;
    }
    .profile-header {
        background: #502c2c;
        color: white;
        padding: 30px 20px;
        text-align: auto;
    }
    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        margin: 0 auto 15px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 25px;
    }
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .profile-header h1 {
        font-size: 1.8rem;
        margin: 0;
        font-weight: 600;
    }
    .profile-header h2 {
        font-size: 1.5rem;
        margin: 0 0 10px;
        font-weight: 400;
        color: rgba(255,255,255,0.9);
    }
    .profile-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 15px;
    }
    .profile-table th {
        width: 30%;
        text-align: left;
        padding: 12px 20px;
        vertical-align: top;
        color: #502c2c;
        font-weight: 600;
    }
    .profile-table td {
        padding: 12px 20px;
        background: #f9f9f9;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .profile-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
    }
    .btn-edit {
        background: #502c2c;
        color: white;
        border: none;
        padding: 10px 25px;
        transition: all 0.3s;
    }
    .btn-edit:hover {
        background: #65251a;
        transform: translateY(-2px);
    }
    .btn-logout {
        transition: all 0.3s;
    }
    .btn-logout:hover {
        transform: translateY(-2px);
    }
    .section-title {
        color: #502c2c;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }
</style>

<div class="profile-container">
    <div class="profile-card card">
        <div class="profile-header">
            <div class="profile-avatar">
                <?php 
                $photoPath = 'uploads/profile_photos/' . ($_SESSION['user_id'] ?? 'default') . '.*';
                $photos = glob($photoPath);
                if (!empty($photos)): ?>
                    <img src="<?php echo $photos[0]; ?>" alt="Profile Photo">
                <?php else: ?>
                    <i class="fas fa-user" style="font-size: 40px;"></i>
                <?php endif; ?>
            </div>
            <h1><?php echo htmlspecialchars($_SESSION['first_name'] ?? ''); ?> 
             <?php echo htmlspecialchars($_SESSION['last_name'] ?? ''); ?></h1>
            <p class="mb-0">Member since <?php echo $regDate; ?></p>
        </div>
        
        <div class="card-body p-4">
            <h3 class="section-title">Profile Information</h3>
            
            <table class="profile-table">
                <tr>
                    <th>Full Name</th>
                    <td><?php echo htmlspecialchars($_SESSION['first_name'] ?? '') . ' ' . htmlspecialchars($_SESSION['last_name'] ?? ''); ?></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?php echo htmlspecialchars($_SESSION['username'] ?? $_SESSION['email']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></td>
                </tr>
                <tr>
                    <th>Occupation</th>
                    <td><?php echo !empty($_SESSION['occupation']) ? htmlspecialchars($_SESSION['occupation']) : 'Not specified'; ?></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><?php echo !empty($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : 'Not provided'; ?></td>
                </tr>
            </table>
            
            <div class="profile-actions">
                <a href="settings.php" class="btn btn-edit">
                    <i class="fas fa-edit me-2"></i>Edit Profile
                </a>
                <a href="logout.php" class="btn btn-danger btn-logout">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
        </div>
    </div>
</div>

<?php 
require_once 'includes/footer.php';
?>