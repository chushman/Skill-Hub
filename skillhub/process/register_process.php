<?php
$db_path = __DIR__ . '/../includes/db.php';
if (!file_exists($db_path)) {
    die("Database configuration file not found at: " . $db_path);
}
require_once $db_path;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = trim($_POST['role']);
    
    // Extract first and last names
    $name_parts = explode(' ', $name);
    $first_name = $name_parts[0];
    $last_name = $name_parts[1] ?? '';

    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../register.php");
        exit();
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email already registered!";
        header("Location: ../register.php");
        exit();
    }

    // Insert new user with all fields
    $stmt = $conn->prepare("INSERT INTO users (name, first_name, last_name, email, password, role, occupation) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $occupation = 'UX Designer'; // Default value
    $stmt->bind_param("sssssss", $name, $first_name, $last_name, $email, $password, $role, $occupation);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
        // Get the newly created user
        $user_id = $stmt->insert_id;
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Set session variables
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
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: ../register.php");
        exit();
    }
} else {
    header("Location: ../register.php");
    exit();
}
?>