<?php
$db_path = __DIR__ . '/../includes/db.php';
if (!file_exists($db_path)) {
    die("Database configuration file not found at: " . $db_path);
}
require_once $db_path;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required!";
        header("Location: ../login.php");
        exit();
    }

    // Fetch user with all fields
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set all session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['username'] = strtolower($user['name']);
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['occupation'] = $user['occupation'];
            $_SESSION['created_at'] = $user['created_at'];
            $_SESSION['loggedin'] = true;

            header("Location: ../dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password!";
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "User not found!";
        header("Location: ../login.php");
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}
?>