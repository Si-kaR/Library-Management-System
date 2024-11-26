<?php
session_start();
require_once '../../db/config.php';
include '../auth_functions/user_functions.php';

// Check if user is logged in and is an admin/librarian
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin'])) {
    $_SESSION['error'] = "Unauthorized access";
    header("Location: ../../view/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the book ID
    $book_id = $_POST['book_id'];
    
    // Check if book exists
    $check_sql = "SELECT * FROM books WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $book_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Book not found";
        header("Location: ../../view/manage.php");
        exit();
    }
    $current_book = $result->fetch_assoc();

    // Get and sanitize form data
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $genre = trim($_POST['genre']);
    $year = (int)$_POST['year'];
    $description = trim($_POST['description']);
    $isbn = trim($_POST['isbn']);
    $publisher = trim($_POST['publisher']);
    $pages = (int)$_POST['pages'];
    $language = trim($_POST['language']);
    $total_copies = (int)$_POST['total_copies'];

    // Basic validation
    if (empty($title) || empty($author) || empty($genre)) {
        $_SESSION['error'] = "Title, author, and genre are required";
        header("Location: ../../view/add.php?id=" . $book_id);   //header("Location: ../../view/edit_view.php?id=" . $book_id);
        exit();
    }

    // Handle book cover upload if new file is uploaded
    $cover = $current_book['cover']; // Keep existing cover by default
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['cover']['name'];
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($filetype, $allowed)) {
            $new_filename = uniqid() . '.' . $filetype;
            $upload_path = '../../assets/images/books/' . $new_filename;
            
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $upload_path)) {
                // Delete old cover if it's not the default
                if ($current_book['cover'] !== 'default-book.jpg') {
                    $old_cover_path = '../../assets/images/books/' . $current_book['cover'];
                    if (file_exists($old_cover_path)) {
                        unlink($old_cover_path);
                    }
                }
                $cover = $new_filename;
            }
        }
    }

    try {
        // Prepare SQL statement
        $sql = "UPDATE books SET 
                title = ?, 
                author = ?, 
                genre = ?, 
                year = ?, 
                cover = ?,
                description = ?, 
                isbn = ?, 
                publisher = ?, 
                pages = ?, 
                language = ?, 
                total_copies = ?
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssissssisii", 
            $title, 
            $author, 
            $genre, 
            $year, 
            $cover, 
            $description, 
            $isbn, 
            $publisher, 
            $pages, 
            $language, 
            $total_copies,
            $book_id
        );

        if ($stmt->execute()) {
            $_SESSION['success'] = "Book updated successfully";
            header("Location: ../../view/manage.php");
            exit();
        } else {
            throw new Exception("Error updating book");
        }

    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: ../../view/add.php?id=" . $book_id);  //header("Location: ../../view/edit_book.php?id=" . $book_id);
        exit();
    }

    $stmt->close();
} else {
    // If someone tries to access this file directly
    header("Location: ../../view/manage.php");
    exit();
}

$conn->close();
?>