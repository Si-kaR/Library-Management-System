<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sekondi Library</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <a href="index.php" class="logo">Sekondi Library</a>
            <h2>Welcome Back</h2>
            <p class="auth-description">Sign in to access your library account</p>

            <form id="loginForm" action="../actions/auth/user_login.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                    <div class="password-options">
                        <label class="checkbox-label">
                            <input type="checkbox" id="rememberMe">
                            Remember me
                        </label>
                        <a href="#" class="forgot-password">Forgot password?</a>
                    </div>
                </div>
                <button type="submit" class="auth-btn">Sign In</button>
            </form>
            
            <p class="auth-text">Don't have an account? <a href="register.php">Create Account</a></p>
        </div>
    </div>
    <script src="../assets/js/login.js"></script>

</body>
</html>