<?php
session_start();
require_once '../db/config.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Unauthorized access";
    header("Location: ../login.php");
    exit();
}

// Check if book ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "No book specified";
    header("Location: manage.php");
    exit();
}

// Get book data
$book_id = (int)$_GET['id'];
$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

if (!$book) {
    $_SESSION['error'] = "Book not found";
    header("Location: manage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Edit Book</h1>
                <a href="manage.php" class="text-blue-500 hover:text-blue-700">
                    &larr; Back to Books
                </a>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <!-- Edit Form -->
            <div class="bg-white shadow rounded-lg p-6">
                <form action="../actions/books/edit_book.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Title *</label>
                            <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Author -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Author *</label>
                            <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Genre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Genre *</label>
                            <select name="genre" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <?php
                                $genres = ['Fiction', 'Non-Fiction', 'Mystery', 'Science Fiction', 'Romance', 'Thriller', 'Biography', 'History', 'Science', 'Technology'];
                                foreach ($genres as $genre):
                                    $selected = ($book['genre'] === $genre) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $genre; ?>" <?php echo $selected; ?>>
                                        <?php echo $genre; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Year -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Publication Year</label>
                            <input type="number" name="year" value="<?php echo $book['year']; ?>"
                                   min="1000" max="<?php echo date('Y'); ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- ISBN -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ISBN</label>
                            <input type="text" name="isbn" value="<?php echo htmlspecialchars($book['isbn']); ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Publisher -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Publisher</label>
                            <input type="text" name="publisher" value="<?php echo htmlspecialchars($book['publisher']); ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Language -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Language</label>
                            <input type="text" name="language" value="<?php echo htmlspecialchars($book['language']); ?>"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Pages -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pages</label>
                            <input type="number" name="pages" value="<?php echo $book['pages']; ?>" min="1"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Total Copies -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total Copies</label>
                            <input type="number" name="total_copies" value="<?php echo $book['total_copies']; ?>" min="1"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Book Cover -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Book Cover</label>
                            <div class="mt-1 flex items-center">
                                <img src="../../assets/images/books/<?php echo htmlspecialchars($book['cover']); ?>" 
                                     alt="Current cover" class="h-32 w-auto object-cover mr-4">
                                <input type="file" name="cover" accept=".jpg,.jpeg,.png,.webp"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                              file:rounded-md file:border-0 file:text-sm file:font-semibold
                                              file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Leave empty to keep current cover. JPG, PNG or WebP only.</p>
                        </div>

                        <!-- Description -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo htmlspecialchars($book['description']); ?></textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-6 flex items-center justify-end gap-4">
                        <button type="button" onclick="history.back()" 
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit"
                                class="bg-blue-500 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const title = document.querySelector('input[name="title"]').value.trim();
        const author = document.querySelector('input[name="author"]').value.trim();
        const genre = document.querySelector('select[name="genre"]').value;

        if (!title || !author || !genre) {
            e.preventDefault();
            alert('Please fill in all required fields (Title, Author, and Genre)');
            return;
        }

        const year = parseInt(document.querySelector('input[name="year"]').value);
        const currentYear = new Date().getFullYear();
        
        if (year < 1000 || year > currentYear) {
            e.preventDefault();
            alert(`Please enter a valid year between 1000 and ${currentYear}`);
            return;
        }
    });
    </script>
</body>
</html>