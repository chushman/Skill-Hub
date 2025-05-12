<?php 


session_start();
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Your existing registration processing code
    
    // After successful registration, add this:
    $_SESSION['temp_message'] = "Registration successful! Please login.";
    header("Location: login.php");
    exit();
}
?>
<style>
    :root {
        --primary-color: #502c2c;       /* Dark brown */
        --secondary-color: #65251a;     /* Dark red-brown */
        --accent-color: #4f241d;        /* Darker red-brown */
        --light-color: #f5f5f5;         /* Light gray for backgrounds */
        --dark-color: #1c2c3b;          /* Dark blue-gray */
    }
    
    .register-container {
        min-height: calc(100vh - 120px);
        display: flex;
        align-items: center;
        padding: 2rem 0;
        background-color: var(--light-color);
    }
    
    .register-form {
        background-color: white;
        padding: 2.5rem;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        max-width: 450px;
        margin: 0 auto;
        border-top: 4px solid var(--secondary-color);
    }
    
    .register-title {
        color: var(--primary-color);
        text-align: center;
        margin-bottom: 1.5rem;
        font-weight: 600;
        font-size: 2rem;
    }
    
    .form-control {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 12px 15px;
        margin-bottom: 1.25rem;
        width: 100%;
        transition: all 0.3s;
        font-size: 1rem;
    }
    
    .form-control:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 0.25rem rgba(101, 37, 26, 0.25);
        outline: none;
    }
    
    .form-select {
        padding: 12px 15px;
        border-radius: 5px;
        border: 1px solid #ddd;
        width: 100%;
        margin-bottom: 1.25rem;
        font-size: 1rem;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
    }
    
    .form-select:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 0.25rem rgba(101, 37, 26, 0.25);
        outline: none;
    }
    
    .btn-register {
        background-color: var(--secondary-color);
        color: white;
        border: none;
        padding: 12px;
        width: 100%;
        font-weight: 600;
        letter-spacing: 0.5px;
        border-radius: 5px;
        transition: all 0.3s;
        font-size: 1rem;
        cursor: pointer;
    }
    
    .btn-register:hover {
        background-color: #452215;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .form-footer {
        text-align: center;
        margin-top: 1.5rem;
        color: #7f8c8d;
        font-size: 0.9rem;
    }
    
    .form-footer a {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .form-footer a:hover {
        text-decoration: underline;
    }
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
        .register-form {
            padding: 1.5rem;
            margin: 0 15px;
        }
        
        .register-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="register-container">
    <div class="container">
        <div class="register-form">
            <h2 class="register-title">Join SkillHub</h2>
            <form action="process/register_process.php" method="POST">
                <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                
                <input type="email" name="email" class="form-control" placeholder="Email" required>
                    
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                
                <select name="role" class="form-select" required>
                    <option value="" disabled selected>Select your role</option>
                    <option value="student">Student</option>
                    <option value="instructor">Instructor</option>
                </select>
                
                <button type="submit" class="btn-register">Register</button>
                
                <div class="form-footer">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>