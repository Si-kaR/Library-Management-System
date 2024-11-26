<?php
// Start session
session_start();

// Include database configuration
require_once '../../db/config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields";
        header("Location: /Team_Project/view/login.php");
        exit();
    }

    // Prepare SQL statement to prevent SQL injection
    $sql = "SELECT id, fname, lname, email, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, create session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fname'] = $user['fname'];
            $_SESSION['lname'] = $user['lname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: ../../view/manage.php");
            } else {
                header("Location: ../../view/my_account.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password";
            header("Location: ../../view/login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid email or password";
        header("Location: ../../view/login.php");
        exit();
    }

    $stmt->close();
} else {
    // If someone tries to access this file directly
    header("Location: ../../view/login.php");
    exit();
}

$conn->close();
?>