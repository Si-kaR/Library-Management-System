<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Sekondi Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sekondi Library</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light me-2" href="../Team_Project/view/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-light text-dark" href="../Team_Project/view/register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold">Discover a World of Knowledge</h1>
                    <p class="lead">Access thousands of books, join engaging discussions, and participate in exciting events at Sekondi Library.</p>
                    <div class="cta-buttons">
                        <a href=".../Team/view/catalog.php" class="btn btn-primary btn-lg">Get Started</a>
                        <a href="#features" class="btn btn-outline-light btn-lg ms-3">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="assets/images/Library_background.jpg" alt="Library Interior" class="hero-image">
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose Sekondi Library?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-book-reader feature-icon"></i>
                        <h3>Digital Library</h3>
                        <p>Access our vast collection of books, journals, and digital resources anytime, anywhere.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-users feature-icon"></i>
                        <h3>Community Forums</h3>
                        <p>Join discussions, share insights, and connect with fellow book lovers.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-calendar-alt feature-icon"></i>
                        <h3>Events & Programs</h3>
                        <p>Participate in workshops, book clubs, and educational programs.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta">
        <div class="container text-center">
            <h2>Ready to Begin Your Journey?</h2>
            <p class="lead">Join our growing community of readers and learners today.</p>
            <a href="../Team_Project/view/register.php" class="btn btn-primary btn-lg">Get Started Now</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About Us</h5>
                    <p>Sekondi Library is dedicated to fostering knowledge, creativity, and community engagement through our comprehensive resources and programs.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#contact">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Library Street, Sekondi</li>
                        <li><i class="fas fa-phone"></i> (233) 123-456-789</li>
                        <li><i class="fas fa-envelope"></i> info@sekondi-library.com</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2024 Sekondi Library. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>