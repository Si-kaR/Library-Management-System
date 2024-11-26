<?php
session_start();
require '../db/config.php';
require '../auth_functions/user_functions.php';

global $conn;

// Admin check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Unauthorized access";
    header("Location: index.php");
    exit();
}

// Get genres for dropdown
$genres = [
    'Fiction',
    'Non-Fiction',
    'Science Fiction',
    'Mystery',
    'Romance',
    'Biography',
    'History',
    'Technology',
    'Science',
    'Arts',
    'Literature',
    'Philosophy'
];

$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);

$book;

if (isset($_GET["book_id"])) {
    $bookId = $_GET['book_id'];

    $book =  viewBookDetails($bookId);

    // var_dump($book);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details - Library System</title>
    <style>
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .book-details {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        .detail-text {
            padding: 8px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-height: 20px;
        }
        .buttons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-size: 14px;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Book Details</h1>
            <div class="book-details">
                <div class="form-group">
                    <label>Title</label>
                    <div class="detail-text"><?php echo htmlspecialchars($book['title']); ?></div>
                </div>
                <div class="form-group">
                    <label>Author</label>
                    <div class="detail-text"><?php echo htmlspecialchars($book['author']); ?></div>
                </div>
                <div class="form-group">
                    <label>Genre</label>
                    <div class="detail-text"><?php echo htmlspecialchars($book['genre']); ?></div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <div class="detail-text"><?php echo htmlspecialchars($book['description']); ?></div>
                </div>
                <div class="form-group">
                    <label>ISBN</label>
                    <div class="detail-text"><?php echo htmlspecialchars($book['isbn']); ?></div>
                </div>
                <div class="form-group">
                    <label>Publisher</label>
                    <div class="detail-text"><?php echo htmlspecialchars($book['publisher']); ?></div>
                </div>
                <div class="form-group">
                    <label>Pages</label>
                    <div class="detail-text"><?php echo htmlspecialchars($book['pages']); ?></div>
                </div>
                <div class="form-group">
                    <label>Language</label>
                    <div class="detail-text"><?php echo htmlspecialchars($book['language']); ?></div>
                </div>
                <div class="buttons">
                    <a href="edit_book.php?id=<?php echo htmlspecialchars($book['id']); ?>" class="btn btn-primary">Edit Book</a>
                    <a href="manage_books.php" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>