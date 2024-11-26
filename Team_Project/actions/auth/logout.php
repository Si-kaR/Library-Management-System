<?php

// // Check if a session is active before starting or destroying it
// if (session_status() === PHP_SESSION_ACTIVE) {
//     session_start();

// }
// Start session if not already started
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: ../../view/login.php");
exit();
?>