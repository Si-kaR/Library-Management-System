<?php
session_start();
require_once '../../db/config.php';


// Check if user is logged in and is an admin/librarian  ['admin', 'librarian']))
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin'])) {
    $_SESSION['error'] = "Unauthorized access";
    header("Location: ../../view/login.php");  //../../view/login.php"
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
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
        header("Location: ../../view/add_book.php");  //../../view/books/add_book.php")
        exit();
    }

// Handle book cover upload
$cover = 'default-book.jpg'; // Default cover
$upload_dir = '../uploads/';

// Ensure the upload directory exists
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $filename = $_FILES['cover']['name'];
    $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (in_array($filetype, $allowed)) {
        $new_filename = uniqid() . '.' . $filetype; // Corrected file naming
        $upload_path = $upload_dir . $new_filename;

        if (move_uploaded_file($_FILES['cover']['tmp_name'], $upload_path)) {
            $cover = $upload_path;
        } else {
            echo "Failed to move uploaded file.";
        }
    } else {
        echo "File type not allowed.";
    }
} else {
    echo "No file uploaded or error during upload.";
}


    try {
        // Prepare SQL statement
        $sql = "INSERT INTO books (title, author, genre, year, cover, description, 
                isbn, publisher, pages, language, total_copies, available_copies) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssissssissi", 
            $title, $author, $genre, $year, $cover, $description, 
            $isbn, $publisher, $pages, $language, $total_copies, $total_copies
        );

        if ($stmt->execute()) {
            $_SESSION['success'] = "Book added successfully";
            $_SESSION['success_time'] = time(); // Add timestamp
            header("Location: ../../view/manage.php");
            exit();
        } else {
            throw new Exception("Error adding book");
        }



        // if ($stmt->execute()) {
        //     $_SESSION['success'] = "Book added successfully";
        //     header("Location: ../../view/manage.php");
        //     exit();
        // } else {
        //     throw new Exception("Error adding book");
        // }

    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: ../../view/add.php");
        exit();
    }

    $stmt->close();
} else {
    // If accessed directly without POST
    header("Location: ../../view/add.php");   //../../view/books/add.php"
    exit();
}

$conn->close();
?>