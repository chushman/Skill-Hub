<?php
ob_start();
require_once 'includes/header.php';
$pageTitle = "Account Settings";

require_once 'includes/db.php';
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $occupation = trim($_POST['occupation']);
        $phone = trim($_POST['phone'] ?? '');
        $username = trim($_POST['username']);

        // Validation
        if (empty($first_name) || empty($last_name) || empty($username)) {
            throw new Exception("First name, last name and username are required");
        }
        if (preg_match('/\s/', $username)) {
            throw new Exception("Username cannot contain spaces");
        }
        if (strlen($username) < 4) {
            throw new Exception("Username must be at least 4 characters");
        }

        // Check if username exists
        $check = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $check->bind_param("si", $username, $_SESSION['user_id']);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            throw new Exception("Username already taken");
        }

        // Handle file upload
        if (!empty($_FILES['profile_photo']['name'])) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            
            if ($_FILES['profile_photo']['size'] > $maxSize) {
                throw new Exception("Profile photo must be less than 2MB");
            }
            
            if (!in_array($_FILES['profile_photo']['type'], $allowedTypes)) {
                throw new Exception("Only JPG, PNG, and GIF images are allowed");
            }
            
            $uploadDir = 'uploads/profile_photos/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $extension = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
            $filename = $_SESSION['user_id'] . '.' . $extension;
            $destination = $uploadDir . $filename;
            
            // Remove old profile photo if exists
            array_map('unlink', glob($uploadDir . $_SESSION['user_id'] . ".*"));
            
            if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $destination)) {
                throw new Exception("Failed to upload profile photo");
            }
        }

        // Update database
        $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, occupation=?, phone=?, username=? WHERE id=?");
        $stmt->bind_param("sssssi", $first_name, $last_name, $occupation, $phone, $username, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            // Update session
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['name'] = "$first_name $last_name";
            $_SESSION['occupation'] = $occupation;
            $_SESSION['phone'] = $phone;
            $_SESSION['username'] = $username;
            
            $_SESSION['success'] = "Profile updated successfully!";
            echo '<script>window.location.href = "profile.php";</script>';
            exit();
        } else {
            throw new Exception("Update failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}

// Fetch current user data
try {
    $stmt = $conn->prepare("SELECT first_name, last_name, occupation, phone, username, email FROM users WHERE id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        throw new Exception("User not found");
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
ob_end_flush();
?>

<style>
    .settings-container {
        max-width: 800px;
        margin: 30px auto;
    }
    .settings-card {
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: none;
    }
    .section-title {
        border-bottom: 2px solid #502c2c;
        padding-bottom: 8px;
        margin-bottom: 20px;
        color: #502c2c;
    }
    .form-group {
        margin-bottom: 25px;
    }
    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }
    .form-control {
        border-radius: 5px;
        padding: 10px 15px;
    }
    .file-upload {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .file-upload-label {
        padding: 8px 15px;
        background: #f8f9fa;
        border: 1px solid #ced4da;
        border-radius: 5px;
        cursor: pointer;
    }
    .photo-requirements {
        font-size: 14px;
        color: #6c757d;
        margin-top: 5px;
    }
    .save-btn {
        background: #502c2c;
        border: none;
        padding: 10px 25px;
        font-weight: 500;
        transition: all 0.3s ease;
        color: white;
    }
    .save-btn:hover {
        background: #65251a;
        transform: translateY(-2px);
    }
    .alert {
        margin-bottom: 20px;
    }
    .current-photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #502c2c;
    }
</style>

<div class="settings-container">
    <div class="settings-card card">
        <div class="card-header bg-dark text-white">
            <h3>Account Settings</h3>
        </div>
        
        <div class="card-body p-4">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="settings.php" enctype="multipart/form-data">
                <div class="mb-5">
                    <h4 class="section-title">Profile Information</h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                               value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                        <div class="photo-requirements">
                            4+ characters, no spaces, only letters, numbers and underscores
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="occupation">Skill/Occupation</label>
                        <input type="text" class="form-control" id="occupation" name="occupation" 
                               value="<?php echo htmlspecialchars($user['occupation'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="Phone Number">
                    </div>
                </div>
                
                <div class="mb-5">
                    <h4 class="section-title">Profile Media</h4>
                    <div class="form-group">
                        <label>Profile Photo</label>
                        
                        <div class="mb-3">
                            <?php 
                            $photoPath = 'uploads/profile_photos/' . ($_SESSION['user_id'] ?? 'default') . '.*';
                            $photos = glob($photoPath);
                            if (!empty($photos)): ?>
                                <img src="<?php echo $photos[0]; ?>" class="current-photo" alt="Current Profile Photo">
                            <?php else: ?>
                                <div class="current-photo" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user fa-2x text-secondary"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="file-upload">
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*" style="display: none;">
                            <label for="profile_photo" class="file-upload-label">Choose File</label>
                            <span id="file-chosen">No file chosen</span>
                        </div>
                        <div class="photo-requirements">
                            Maximum size: 2MB | Accepted formats: JPG, PNG, GIF
                        </div>
                    </div>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn save-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('profile_photo').addEventListener('change', function() {
    var fileName = this.files[0] ? this.files[0].name : "No file chosen";
    document.getElementById('file-chosen').textContent = fileName;
});
</script>

<?php require_once 'includes/footer.php'; ?>