<?php 
session_start();
include 'includes/header.php';
include 'includes/db.php';

// Initialize variables
$email = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate credentials
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['occupation'] = $user['occupation'] ?? '';
            $_SESSION['phone'] = $user['phone'] ?? '';
            $_SESSION['created_at'] = $user['created_at'];
             $_SESSION['role'] = $user['role'];
            $_SESSION['loggedin'] = true;

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password";
        }
    } else {
        $error = "Invalid email or password";
    }
}

// Show message if redirected from registration
if (isset($_SESSION['temp_message'])) {
    echo '<div class="alert alert-success text-center">'.$_SESSION['temp_message'].'</div>';
    unset($_SESSION['temp_message']);
}
?>

<style>
    :root {
        --primary-color: #502c2c;
        --secondary-color: #65251a;
        --accent-color: #4f241d;
        --light-color: #ecf0f1;
        --dark-color: #1c2c3b;
    }
    
    body {
        background-color: var(--light-color);
        color: var(--dark-color);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .login-container {
        min-height: calc(100vh - 120px);
        display: flex;
        align-items: center;
        padding: 2rem 0;
    }
    
    .login-form {
        background-color: white;
        padding: 2.5rem;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        max-width: 450px;
        margin: 0 auto;
        border-top: 4px solid var(--secondary-color);
    }
    
    .login-title {
        color: var(--primary-color);
        text-align: center;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }
    
    .form-control {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 12px 15px;
        margin-bottom: 1.25rem;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 0.25rem rgba(101, 37, 26, 0.25);
    }
    
    .btn-login {
        background-color: var(--secondary-color);
        border: none;
        padding: 12px;
        font-weight: 500;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        color: white;
    }
    
    .btn-login:hover {
        background-color: var(--accent-color);
        transform: translateY(-2px);
    }
    
    .form-footer {
        text-align: center;
        margin-top: 1.5rem;
        color: #795148;
    }
    
    .form-footer a {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 500;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
        margin-bottom: 1rem;
    }
</style>

<div class="login-container">
    <div class="container">
        <div class="login-form">
            <h2 class="login-title">Login to SkillHub</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" 
                           value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-login btn-block w-100">Login</button>
                
                <div class="form-footer mt-3">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                    <p><a href="forgot-password.php">Forgot password?</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>