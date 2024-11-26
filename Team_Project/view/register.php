<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sekondi Library</title>
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <a href="index.php" class="logo">Sekondi Library</a>
            <h2>Create An Account</h2>
            <p class="auth-description">Join our library community to access books, events, and more.</p>
            
            <form id="registrationForm" action="../actions/auth/user_register.php" method = "POST">
                <div class="name-fields">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" id="firstName" name="fname" required>
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" id="lastName" name="lname" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <small class="password-hint">At least 8 characters with letters, numbers, and symbols</small>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <button type="submit" class="auth-btn" name="submitBtn" id="submitBtn">Create Account</button>
            </form>
            <p class="auth-text">Already have an account? <a href="../view/login.php">Sign In</a></p>
        </div>
    </div>
    <script src="../assets/js/register.js"></script>
</body>
</html>