<?php
session_start();
require_once '../db/config.php';
// require_once '../auth_functions/user_functions.php';

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Edit Book - Library System</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #2B4570;
            min-height: 100vh;
        }

        .container {
            max-width: 75rem;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 1rem rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #1976d2;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #2196f3;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 0.75rem;
            border: 0.0625rem solid #90caf9;
            border-radius: 0.25rem;
            font-size: 1rem;
            margin-top: 0.25rem;
            background: #fff;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 0.125rem rgba(25, 118, 210, 0.1);
        }

        input[type="file"] {
            padding: 0.5rem 0;
        }

        small {
            display: block;
            margin-top: 0.25rem;
            color: #64b5f6;
            font-size: 0.875rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn.primary {
            background-color: #2196f3;
            color: white;
        }

        .btn.primary:hover {
            background-color: #1976d2;
            transform: translateY(-0.125rem);
        }

        .btn:not(.primary) {
            background-color: #e3f2fd;
            color: #1976d2;
            border: 0.0625rem solid #90caf9;
        }

        .btn:not(.primary):hover {
            background-color: #bbdefb;
            transform: translateY(-0.125rem);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 50rem;
            background-color: white;
            box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.15);
            z-index: 1000;
            padding: 1.5rem;
            border-radius: 0.5rem;
            border: 0.0625rem solid #90caf9;
        }

        .modal.active {
            display: block;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 0.0625rem solid #e3f2fd;
        }

        .modal-header h2 {
            font-size: 1.5rem;
            margin: 0;
            color: #1976d2;
        }

        #closeModal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            color: #64b5f6;
            transition: color 0.3s ease;
        }

        #closeModal:hover {
            color: #1976d2;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(25, 118, 210, 0.2);
            backdrop-filter: blur(0.25rem);
            z-index: 999;
        }

        .modal-overlay.active {
            display: block;
        }

        /* Error/Success Messages */
        .error-message {
            color: #d32f2f;
            background-color: #ffebee;
            border: 0.0625rem solid #ef9a9a;
            padding: 0.75rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }

        .success-message {
            color: #388e3c;
            background-color: #e8f5e9;
            border: 0.0625rem solid #a5d6a7;
            padding: 0.75rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }

        /* Responsive Design */
        @media screen and (max-width: 64rem) {
            .container {
                padding: 1.5rem;
            }

            .card {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }

        @media screen and (max-width: 48rem) {
            h1 {
                font-size: 1.75rem;
            }

            .modal {
                width: 95%;
                padding: 1rem;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }
        }

        @media screen and (max-width: 36rem) {
            .container {
                padding: 1rem;
            }

            .card {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            h1 {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            input[type="text"],
            input[type="number"],
            textarea,
            select {
                padding: 0.5rem;
            }

            .btn {
                padding: 0.625rem 1.25rem;
            }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Existing Add Book Form -->
        <div class="card">
            <h1>Add New Book</h1>
            <form action="../actions/books/add_book.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" required maxlength="255">
                </div>
                <div class="form-group">
                    <label for="author">Author *</label>
                    <input type="text" id="author" name="author" required maxlength="100">
                </div>
                <div class="form-group">
                    <label for="genre">Genre *</label>
                    <select id="genre" name="genre" required>
                        <option value="">Select Genre</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?php echo htmlspecialchars($genre); ?>">
                                <?php echo htmlspecialchars($genre); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" id="isbn" name="isbn" maxlength="13">
                </div>
                <div class="form-group">
                    <label for="publisher">Publisher</label>
                    <input type="text" id="publisher" name="publisher" maxlength="100">
                </div>
                <div class="form-group">
                    <label for="available_copies">Pages</label>
                    <input type="number" id="available_copies" name="pages" min="1">
                </div>
                <div class="form-group">
                    <label for="language">Language</label>
                    <input type="text" id="language" name="language" value="English">
                </div>
                <div class="form-group">
                    <label for="total_copies">Total Copies *</label>
                    <input type="number" id="total_copies" name="total_copies" required min="1" value="1">
                </div>
                <div class="form-group">
                    <label for="cover">Book Cover</label>
                    <input type="file" id="cover" name="cover" accept=".jpg,.jpeg,.png,.webp">
                    <small>Supported: JPG, PNG, WEBP (Max: 5MB)</small>
                </div>
                <div class="form-group">
                    <label for="publication_year">Year</label>
                    <input type="number" id="year" name="year">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn primary">Add Book</button>
                    <a href="manage.php" class="btn">Cancel</a>
                </div>
            </form>
        </div>

        <!-- Modal for Editing -->
        <div class="modal-overlay"></div>
        <div id="editModal" class="modal">
            <div class="modal-header">
                <h2>Edit Book</h2>
                <button id="closeModal">&times;</button>
            </div>
            <form id="editForm" action="../actions/books/edit_book.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="book_id" id="book_id">
                <div class="form-group">
                    <label for="edit_title">Title *</label>
                    <input type="text" id="edit_title" name="title" required maxlength="255">
                </div>
                <div class="form-group">
                    <label for="edit_author">Author *</label>
                    <input type="text" id="edit_author" name="author" required maxlength="100">
                </div>
                <div class="form-group">
                    <label for="edit_genre">Genre *</label>
                    <select id="edit_genre" name="genre" required>
                        <option value="">Select Genre</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?php echo htmlspecialchars($genre); ?>">
                                <?php echo htmlspecialchars($genre); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_description">Description</label>
                    <textarea id="edit_description" name="description" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_isbn">ISBN</label>
                    <input type="text" id="edit_isbn" name="isbn" maxlength="13">
                </div>
                <div class="form-group">
                    <label for="edit_publisher">Publisher</label>
                    <input type="text" id="edit_publisher" name="publisher" maxlength="100">
                </div>
                <div class="form-group">
                    <label for="edit_pages">Pages</label>
                    <input type="number" id="edit_pages" name="available_copies" min="1">
                </div>
                <div class="form-group">
                    <label for="edit_language">Language</label>
                    <input type="text" id="edit_language" name="language" value="English">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn primary">Save Changes</button>
                    <button type="button" id="cancelEdit" class="btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal Functionality
        const editModal = document.getElementById('editModal');
        const overlay = document.querySelector('.modal-overlay');
        const closeModal = document.getElementById('closeModal');
        const cancelEdit = document.getElementById('cancelEdit');

        function openEditModal(bookData) {
            document.getElementById('book_id').value = bookData.id;
            document.getElementById('edit_title').value = bookData.title;
            document.getElementById('edit_author').value = bookData.author;
            document.getElementById('edit_genre').value = bookData.genre;
            editModal.classList.add('active');
            overlay.classList.add('active');
        }

        function closeModalFunction() {
            editModal.classList.remove('active');
            overlay.classList.remove('active');
        }

        closeModal.addEventListener('click', closeModalFunction);
        cancelEdit.addEventListener('click', closeModalFunction);
        overlay.addEventListener('click', closeModalFunction);
    </script>
</body>

</html>