<?php
session_start();
require_once '../db/config.php';
require_once '../auth_functions/user_functions.php';

// Get all available genres from the database
function getAvailableGenres($conn) {
    $query = "SELECT DISTINCT genre FROM books ORDER BY genre";
    $result = mysqli_query($conn, $query);
    $genres = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $genres[] = $row['genre'];
        }
        mysqli_free_result($result);
    }
    
    return $genres;
}

$genres = getAvailableGenres($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Catalog - Sekondi Library</title>
    <link rel="stylesheet" href="../assets/css/catalog.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sekondi Library</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="my_account.php">My Account</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="catalog-header">
            <h1>Library Catalog</h1>
            <p class="lead">Discover our collection of books and resources</p>
        </div>
        
        <div class="search-section">
            <div class="search-bar">
                <input type="text" id="search-input" class="search-input" placeholder="Search by title, author, or keywords...">
                <button id="search-button" class="search-button">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
            
            <div class="filters">
                <div class="filter-group">
                    <label for="genre-filter">Genre</label>
                    <select id="genre-filter" class="filter-select">
                        <option value="">All Genres</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?php echo htmlspecialchars($genre); ?>">
                                <?php echo htmlspecialchars($genre); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="sort-select">Sort By</label>
                    <select id="sort-select" class="filter-select">
                        <option value="relevance">Relevance</option>
                        <option value="title">Title (A-Z)</option>
                        <option value="author">Author (A-Z)</option>
                        <option value="year-new">Newest First</option>
                        <option value="year-old">Oldest First</option>
                        <option value="popular">Most Popular</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Quick Filters -->
        <div class="quick-filters">
            <button class="quick-filter active" data-filter="all">All Books</button>
            <button class="quick-filter" data-filter="new">New Arrivals</button>
            <button class="quick-filter" data-filter="popular">Most Popular</button>
            <button class="quick-filter" data-filter="recommended">Recommended</button>
        </div>

        <!-- Results Summary -->
        <div class="results-summary">
            <span id="results-count">Loading books...</span>
            <div class="view-options">
                <button class="view-option active" data-view="grid">
                    <i class="fas fa-th-large"></i>
                </button>
                <button class="view-option" data-view="list">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <!-- Search Results -->
        <div id="search-results" class="book-grid">
            <!-- Books will be dynamically inserted here -->
        </div>

        <!-- Loading Indicator -->
        <div id="loading" class="loading">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-4">
            <button id="load-more" class="btn btn-outline-primary" style="display: none;">Load More</button>
        </div>
    </div>

    <!-- Book Details Modal -->
    <div class="modal fade" id="bookModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Book Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Book details will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Debug Info (remove in production) -->
    <div id="debug-info" style="display:none;">
        <pre id="debug-output"></pre>
    </div>

    <script>
        // Add this before loading catalog.js to help debug
        function debugLog(message) {
            console.log(message);
            const debugOutput = document.getElementById('debug-output');
            if (debugOutput) {
                debugOutput.textContent += message + '\n';
            }
        }
    </script>

<script src="../assets/js/catalog.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Add this after loading catalog.js to check if it's working
        document.addEventListener('DOMContentLoaded', function() {
            debugLog('DOM Loaded');
            if (typeof initialize === 'function') {
                debugLog('Initialize function exists');
            } else {
                debugLog('ERROR: Initialize function not found');
            }
        });
    </script>
</body>
</html>