<?php
    require_once '../db/config.php';
    require_once '../auth_functions/borrow_functions.php';
    session_start();
    // Fetch the borrowed books data
    $borrowedBooks = getAllBorrowedBooks($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Sekondi Library</title>
    <link rel="stylesheet" href="../assets/css/events.css">
    <link rel="stylesheet" href="../assets/css/account_styles.css">
</head>
<body> 
    <!-- Header -->
    <header class="header" role="banner">
        <nav class="nav" role="navigation">
            <a href="index.html" class="nav-logo">Sekondi Library</a>
            <div class="nav-links">
                <a href="/Team_Project/index.php" class="nav-link">Home</a>
                <a href="../view/catalog.php" class="nav-link">Catalog</a>
                <a href="../view/events.php" class="nav-link">Events</a>
                <a href="../view/forum.php" class="nav-link">Forum</a>
                <a href="../view/my_account.php" class="nav-link" active>My Account</a>
                <a href="../actions/auth/logout.php">Logout</a>
            </div>
        </nav>
    </header>
    <main class="main-content" role="main">
        <div class="account-container">
            <!-- Profile Overview Section -->
            <section class="account-section">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <img src="/api/placeholder/150/150" alt="Profile Picture" id="profileImage">
                        <button class="change-avatar-btn" id="changeAvatarBtn">Change Photo</button>
                    </div>
                    <div class="profile-info">
                        <h1 id="userName">Loading...</h1>
                        <p class="member-since">Member since: <span id="memberSince">Loading...</span></p>
                        <div class="quick-stats">
                            <div class="stat-item">
                                <span class="stat-number" id="booksCount">0</span>
                                <span class="stat-label">Books Borrowed</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number" id="eventsCount">0</span>
                                <span class="stat-label">Events Attended</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number" id="reservationsCount">0</span>
                                <span class="stat-label">Active Reservations</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Navigation -->
                <div class="account-nav">
                    <button class="account-nav-btn active" data-section="profile">Profile</button>
                    <button class="account-nav-btn" data-section="borrowing">Borrowing</button>
                    <button class="account-nav-btn" data-section="reservations">Reservations</button>
                    <button class="account-nav-btn" data-section="events">Events</button>
                    <button class="account-nav-btn" data-section="recommendations">For You</button>
                    <button class="account-nav-btn" data-section="notifications">
                        Notifications
                        <span class="notification-badge" id="notificationBadge">0</span>
                    </button>

                    <button class="account-nav-btn" data-section="discussions">Discussions</button>
                </div>

                <!-- Profile Section -->
                <div class="account-content" id="profileSection">
                    <h2>Personal Information</h2>
                    <form id="profileForm" class="profile-form">
                        <div class="form-group">
                            <label class="form-label required">Full Name</label>
                            <input type="text" class="form-input" id="fullName" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label required">Email</label>
                            <input type="email" class="form-input" id="email" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-input" id="phone">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <textarea class="form-input" id="address" rows="3"></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Save Changes</button>
                            <button type="button" class="btn-secondary">Cancel</button>
                        </div>
                    </form>
                    <div class="section-divider"></div>

                    <h2>Reading Interests</h2>
                    <div class="interests-container">
                        <div class="interest-tags" id="interestTags">
                            <!-- Will be populated dynamically -->
                        </div>
                        <button class="btn-outline" id="addInterestBtn">Add Interest</button>
                    </div>
                </div>

                <!-- Borrowing Section -->
                <div class="account-content hidden" id="borrowingSection">
                <h2>Borrowing History</h2>
                <div class="borrowing-tabs">
                    <button class="tab-btn active" data-tab="current">Current Loans</button>
                    <button class="tab-btn" data-tab="history">History</button>
                </div>
                <div class="borrowing-list" id="borrowingList">
                    <?php
                    // Check if there was an error
                    if (isset($borrowedBooks['error'])) {
                        echo "<p>" . htmlspecialchars($borrowedBooks['error']) . "</p>";
                    } else {
                        // Loop through the borrowed books and display them
                        foreach ($borrowedBooks as $book) {
                            echo "
                                <div class='borrowing-item'>
                                    <div class='item-details'>
                                        <h4>" . htmlspecialchars($book['bookTitle']) . "</h4>
                                        <p>Borrowed: " . htmlspecialchars($book['borrow_date']) . "</p>
                                        <p class='due-date'>Due: " . htmlspecialchars($book['due_date']) . "</p>
                                    </div>
                                    <div class='item-status status-borrowed'>
                                        Borrowed
                                    </div>
                                    <div class='item-actions'>
                                    
                                        <form action = '../actions/books/return_book.php' method = 'POST'>
                                        <input type = 'hidden' name = 'borrowed_id' value =" . htmlspecialchars($book['borrowed_id']) . ">
                                        <button type = 'submit' class='btn btn-outline'>Return</button>
                                        </form>
                                    </div>
                                </div>
                            ";
                        }
                    }
                    ?>
                </div>
            </div>



                <!-- Reservations Section -->
                <!-- <div class="account-content hidden" id="reservationsSection">
                    <h2>My Reservations</h2>
                    <div class="reservations-list" id="reservationsList">
                        Will be populated dynamically
                    </div>
                </div> -->

                <!-- Events Section -->
                <div class="account-content hidden" id="eventsSection">
                    <h2>My Events</h2>
                    <div class="events-tabs">
                        <button class="tab-btn active" data-tab="upcoming">Upcoming Events</button>
                        <button class="tab-btn" data-tab="past">Past Events</button>
                    </div>
                    <div class="events-list" id="eventsList">
                        <!-- Will be populated dynamically -->
                    </div>
                </div>

                <!-- Recommendations Section -->
                <div class="account-content hidden" id="recommendationsSection">
                    <h2>Recommended for You</h2>
                    <div class="recommendations-books">
                        <h3>Books You Might Like</h3>
                        <div class="recommendations-grid" id="bookRecommendations">
                            <!-- Will be populated dynamically -->
                        </div>
                    </div>
                    <div class="recommendations-events">
                        <h3>Recommended Events</h3>
                        <div class="recommendations-grid" id="eventRecommendations">
                            <!-- Will be populated dynamically -->
                        </div>
                    </div>
                </div>

                <!-- Notifications Section -->
                <div class="account-content hidden" id="notificationsSection">
                    <h2>Notifications</h2>
                    <div class="notification-filters">
                        <button class="filter-btn active" data-filter="all">All</button>
                        <button class="filter-btn" data-filter="unread">Unread</button>
                        <button class="filter-btn" data-filter="books">Books</button>
                        <button class="filter-btn" data-filter="events">Events</button>
                    </div>
                    <div class="notifications-list" id="notificationsList">
                        <!-- Will be populated dynamically -->
                    </div>
                </div>

                
                <div class="account-content hidden" id="discussionsSection">
                    <h2>My Discussions</h2>
                    <div class="discussions-tabs">
                        <button class="tab-btn active" data-tab="my-topics">My Topics</button>
                        <button class="tab-btn" data-tab="replies">My Replies</button>
                        <button class="tab-btn" data-tab="saved">Saved Discussions</button>
                    </div>
                    <div class="discussions-list" id="discussionsList">
                        <!-- Will be populated dynamically -->
                    </div>
                </div>



            </section>
        </div>
    </main>

    <!-- Add Interest Modal -->
    <div class="modal" id="interestModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Reading Interest</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="interest-categories">
                    <!-- Will be populated dynamically -->
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/account.js"></script>

</body>
</html>