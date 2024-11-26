<?php
include '../../db/config.php';
include '../../auth_functions/user_functions.php';
// Check if an ID is provided via GET
if (isset($_GET['id'])) {
    $bookId = intval($_GET['id']); // Sanitize the input
    deleteBook($conn, $bookId);
    // Redirect back to the book list
    header("Location: ../../view/manage.php"); // Adjust the redirect URL as needed
    exit;
} else {
    echo "No book ID provided.";
}

// Close the connection
$conn->close();
?>