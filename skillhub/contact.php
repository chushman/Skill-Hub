<?php 
include 'includes/header.php';

// Database connection using your existing credentials
$host = "localhost";
$user = "root";
$password = ""; // default XAMPP password is empty
$dbname = "skillhub";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$name = $email = $message = '';
$name_err = $email_err = $message_err = '';
$notification_message = '';
$notification_class = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate name
    if (empty(trim($_POST['name']))) {
        $name_err = 'Please enter your name.';
    } else {
        $name = trim($_POST['name']);
    }
    
    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = 'Please enter your email address.';
    } elseif (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        $email_err = 'Please enter a valid email address.';
    } else {
        $email = trim($_POST['email']);
    }
    
    // Validate message
    if (empty(trim($_POST['message']))) {
        $message_err = 'Please enter your message.';
    } else {
        $message = trim($_POST['message']);
    }
    
    // If no errors, insert into database
    if (empty($name_err) && empty($email_err) && empty($message_err)) {
        $sql = "INSERT INTO contact_submissions (name, email, message, submission_date) VALUES (?, ?, ?, NOW())";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sss", $param_name, $param_email, $param_message);
            
            $param_name = $name;
            $param_email = $email;
            $param_message = $message;
            
            if ($stmt->execute()) {
                $notification_message = "Your message has been sent successfully!";
                $notification_class = "success";
                
                // Clear form fields on success
                $name = $email = $message = '';
            } else {
                $notification_message = "Oops! Something went wrong. Please try again later.";
                $notification_class = "error";
            }
            
            $stmt->close();
        }
    } else {
        $notification_message = "Please fix the errors in the form.";
        $notification_class = "error";
    }
    
    $conn->close();
}
?>

<style>
    :root {
        --primary-color: #502c2c;
        --secondary-color: #65251a;
        --accent-color: #4f241d;
        --light-color: #f5f5f5;
        --dark-color: #1c2c3b;
    }
    
    .form-container {
        min-height: calc(100vh - 120px);
        display: flex;
        align-items: center;
        padding: 2rem 0;
        background-color: var(--light-color);
    }
    
    .custom-form {
        background-color: white;
        padding: 2.5rem;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        max-width: 450px;
        margin: 0 auto;
        border-top: 4px solid var(--secondary-color);
    }
    
    .form-title {
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
        margin-bottom: 0.5rem;
        width: 100%;
        transition: all 0.3s;
        font-size: 1rem;
    }
    
    .form-control:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 0.25rem rgba(101, 37, 26, 0.25);
        outline: none;
    }
    
    textarea.form-control {
        height: 120px;
        resize: vertical;
    }
    
    .btn-submit {
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
        margin-top: 1rem;
    }
    
    .btn-submit:hover {
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
    
    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-bottom: 1rem;
        display: block;
    }

    /* Notification styles */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        z-index: 1000;
        animation: slideIn 0.5s, fadeOut 0.5s 2.5s forwards;
    }
    
    .notification.success {
        background-color: #4CAF50;
        color: white;
    }
    
    .notification.error {
        background-color: #f44336;
        color: white;
    }

    @keyframes slideIn {
        from {right: -300px; opacity: 0;}
        to {right: 20px; opacity: 1;}
    }

    @keyframes fadeOut {
        from {opacity: 1;}
        to {opacity: 0;}
    }
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
        .custom-form {
            padding: 1.5rem;
            margin: 0 15px;
        }
        
        .form-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="form-container">
    <div class="container">
        <div class="custom-form">
            <h2 class="form-title">Contact Us</h2>
            <form id="contactForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="text" name="name" class="form-control" placeholder="Your Name" value="<?php echo htmlspecialchars($name); ?>">
                <?php if (!empty($name_err)): ?>
                    <span class="error-message"><?php echo $name_err; ?></span>
                <?php endif; ?>
                
                <input type="email" name="email" class="form-control" placeholder="Your Email" value="<?php echo htmlspecialchars($email); ?>">
                <?php if (!empty($email_err)): ?>
                    <span class="error-message"><?php echo $email_err; ?></span>
                <?php endif; ?>
                
                <textarea name="message" class="form-control" placeholder="Your Message"><?php echo htmlspecialchars($message); ?></textarea>
                <?php if (!empty($message_err)): ?>
                    <span class="error-message"><?php echo $message_err; ?></span>
                <?php endif; ?>
                
                <button type="submit" class="btn-submit">Send Message</button>
                
                <div class="form-footer">
                    <p><a href="faq.php">Need help?</a> • <a href="privacy.php">Privacy Policy</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (!empty($notification_message)): ?>
    <div class="notification <?php echo $notification_class; ?>">
        <?php echo $notification_message; ?>
    </div>
    
    <script>
        // Hide notification after 3 seconds
        setTimeout(() => {
            document.querySelector('.notification').style.display = 'none';
        }, 3000);
    </script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>