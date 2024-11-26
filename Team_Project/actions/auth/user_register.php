<?php
// Include the database configuration file to connect to the database
session_start();
require_once '../../db/config.php';
// require_once '../../utilities/validators.php';

// Enable error reporting to display errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];


    // var_dump($_POST);
    // exit;

    // Basic validation
    $errors = [];

    if (empty($fname)) {
        $errors[] = "First name is required";
    }

    if (empty($lname)) {
        $errors[] = "Last name is required";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }

    if (empty($password) || strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }

    if ($confirmPassword !== $password) {
        $errors[] = "Passwords do not match";
    }

    // Stop execution if there are any validation errors
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
        exit;
    }

    // Check if email already exists in the database
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $results = $stmt->get_result();

    if ($results->num_rows > 0) {
        echo "<script>alert('User already registered.');</script>";
        echo "<script>window.location.href = '../view/register.php';</script>";
        exit;
    }

    $stmt->close(); // Close the previous prepared statement

    // Proceed with registration
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (fname, lname, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fname, $lname, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful. Redirecting to login page...');</script>";
        echo "<script>window.location.href = '/view/login.php';</script>";
    } else {
        echo "<p style='color: red;'>Registration failed: " . $stmt->error . "</p>";
    }

    $stmt->close(); // Close the prepared statement
    $conn->close(); // Close the database connection
}
?>
