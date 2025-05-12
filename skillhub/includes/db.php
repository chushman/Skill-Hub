<?php
$host = "localhost";
$user = "root"; 
$password = "";
$dbname = "skillhub";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = $conn->query("SHOW COLUMNS FROM users LIKE 'username'");
if ($result->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD COLUMN username VARCHAR(50) AFTER email");
    $conn->query("UPDATE users SET username = SUBSTRING_INDEX(email, '@', 1)");
}
$conn->query("ALTER TABLE users ADD UNIQUE (username)");


?>