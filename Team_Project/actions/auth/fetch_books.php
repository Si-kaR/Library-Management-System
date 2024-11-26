<?php
// api/fetch_books.php
session_start();
require_once '../../db/config.php';
require_once '../../auth_functions/user_functions.php';
ini_set('display_errors', 0);
header('Content-Type: application/json');

try {
    global $conn;
    
    // Get query parameters
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    $genre = isset($_GET['genre']) ? mysqli_real_escape_string($conn, $_GET['genre']) : '';
    $sort = isset($_GET['sort']) ? mysqli_real_escape_string($conn, $_GET['sort']) : 'title';
    $filter = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : 'all';

    // Calculate offset
    $offset = ($page - 1) * $limit;

    // Build the base query
    $query = "SELECT 
                b.*,
                CASE 
                    WHEN b.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 
                    ELSE 0 
                END as isNew,
                (b.rating >= 4.5) as isPopular,
                (b.rating >= 4.0) as isRecommended
              FROM books b
              WHERE 1=1";
    
    $whereConditions = [];
    $limitClause = " LIMIT $limit OFFSET $offset";

    // Add search condition
    if ($search) {
        $whereConditions[] = "(title LIKE '%$search%' OR author LIKE '%$search%' OR description LIKE '%$search%')";
    }

    // Add genre filter
    if ($genre) {
        $whereConditions[] = "genre = '$genre'";
    }

    // Add quick filter conditions
    switch ($filter) {
        case 'new':
            $whereConditions[] = "created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            break;
        case 'popular':
            $whereConditions[] = "rating >= 4.5";
            break;
        case 'recommended':
            $whereConditions[] = "rating >= 4.0";
            break;
    }

    // Combine where conditions
    if (!empty($whereConditions)) {
        $query .= " AND " . implode(" AND ", $whereConditions);
    }

    // Add sorting
    switch ($sort) {
        case 'title':
            $query .= " ORDER BY title ASC";
            break;
        case 'author':
            $query .= " ORDER BY author ASC";
            break;
        case 'year-new':
            $query .= " ORDER BY publication_year DESC";
            break;
        case 'year-old':
            $query .= " ORDER BY publication_year ASC";
            break;
        case 'popular':
            $query .= " ORDER BY rating DESC";
            break;
        default:
            $query .= " ORDER BY title ASC";
    }

    // Get total count for pagination
    $countQuery = str_replace("SELECT b.*", "SELECT COUNT(*) as total", 
                             preg_replace("/LIMIT\s+\d+\s+OFFSET\s+\d+/i", "", $query));
    
    $countResult = mysqli_query($conn, $countQuery);
    if (!$countResult) {
        throw new Exception(mysqli_error($conn));
    }
    $totalCount = mysqli_fetch_assoc($countResult)['total'];

    // Execute main query with limit
    $query .= $limitClause;
    $result = mysqli_query($conn, $query);
    if (!$result) {
        throw new Exception(mysqli_error($conn));
    }

    // Fetch all books
    $books = [];
    while ($book = mysqli_fetch_assoc($result)) {
        $books[] = [
            'id' => $book['id'],
            'title' => $book['title'],
            'author' => $book['author'],
            'genre' => $book['genre'],
            'year' => $book['publication_year'],
            'cover' => $book['cover_image'] ?? '',
            'description' => $book['description'],
            'rating' => floatval($book['rating']),
            'isNew' => (bool)$book['isNew'],
            'isPopular' => (bool)$book['isPopular'],
            'isRecommended' => (bool)$book['isRecommended'],
            'details' => [
                'isbn' => $book['isbn'],
                'publisher' => $book['publisher'],
                'pages' => intval($book['pages']),
                'language' => $book['language']
            ]
        ];
    }

    // Free result sets
    mysqli_free_result($countResult);
    mysqli_free_result($result);

    echo json_encode([
        'success' => true,
        'total' => $totalCount,
        'books' => $books
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred while fetching books: ' . $e->getMessage()
    ]);
}

// Close the connection
// Note: If you're using a global connection that's needed elsewhere, 
// you might want to remove this line
// mysqli_close($conn);
?>