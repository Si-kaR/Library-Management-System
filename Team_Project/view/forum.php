<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Forum - Sekondi Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/forum.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/Team_Project/index.php">Sekondi Library</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/Team_Project/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Team_Project/view/catalog.php">Catalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/Team_Project/forum.php">Forum</a>
                    </li>
                </ul>
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> My Account
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <!-- <li><a class="dropdown-item" href="my-account.html">Dashboard</a></li> -->
                        <li><a class="dropdown-item" href="my_account.php?section=profile">My Profile</a></li>
                        <li><a class="dropdown-item" href="my_account.php?section=discussions">My Discussions</a></li>
                        <li><a class="dropdown-item" href="my_account.php?section=notifications">Notifications</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/Team_Project/view/login.php" id="logoutBtn">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Forum Header -->
        <div class="forum-header">
            <h1>Community Forum</h1>
            <p class="lead">Join discussions, share insights, and connect with fellow readers</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTopicModal">
                <i class="fas fa-plus"></i> Start New Discussion
            </button>
        </div>

        <!-- Forum Categories -->
        <div class="row mt-4">
            <div class="col-md-3">
                <!-- Categories Sidebar -->
                <div class="categories-sidebar">
                    <h5>Categories</h5>
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action active" data-category="all">
                            All Discussions
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" data-category="book-discussions">
                            Book Discussions
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" data-category="reading-challenges">
                            Reading Challenges
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" data-category="book-clubs">
                            Book Clubs
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" data-category="local-history">
                            Local History
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" data-category="study-groups">
                            Study Groups
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" data-category="recommendations">
                            Book Recommendations
                        </a>
                    </div>

                    <!-- Popular Tags -->
                    <div class="mt-4">
                        <h5>Popular Tags</h5>
                        <div class="tags-cloud">
                            <a href="#" class="tag">#fiction</a>
                            <a href="#" class="tag">#classics</a>
                            <a href="#" class="tag">#bookclub</a>
                            <a href="#" class="tag">#history</a>
                            <a href="#" class="tag">#newreleases</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <!-- Search and Filters -->
                <div class="forum-controls mb-4">
                    <div class="search-bar">
                        <input type="text" class="form-control" placeholder="Search discussions...">
                    </div>
                    <div class="filters">
                        <select class="form-select">
                            <option value="recent">Most Recent</option>
                            <option value="popular">Most Popular</option>
                            <option value="unanswered">Unanswered</option>
                        </select>
                    </div>
                </div>

                <!-- Discussions List -->
                <div class="discussions-list">
                    <!-- Discussion items will be dynamically added here -->
                </div>

                <!-- Pagination -->
                <nav aria-label="Discussions pagination" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- New Topic Modal -->
    <div class="modal fade" id="newTopicModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Start New Discussion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="newTopicForm" action="../actions/forum/create_discussion.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" required>
                                <option value="">Select a category</option>
                                <option value="book-discussions">Book Discussions</option>
                                <option value="reading-challenges">Reading Challenges</option>
                                <option value="book-clubs">Book Clubs</option>
                                <option value="local-history">Local History</option>
                                <option value="study-groups">Study Groups</option>
                                <option value="recommendations">Book Recommendations</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea class="form-control" rows="6" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tags</label>
                            <input type="text" class="form-control" placeholder="Add tags separated by commas">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Create Discussion</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/forum.js"></script>
</body>
</html>