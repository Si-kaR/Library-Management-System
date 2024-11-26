<?php

// require_once '../db/config.php';



function deleteBook($conn, $bookId) {
    global $conn;
    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM `books` WHERE `id` = ?");
    
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    
    // Bind the book ID to the statement
    $stmt->bind_param("i", $bookId);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Check if a row was actually deleted
        if ($stmt->affected_rows > 0) {
            echo "Book with ID $bookId successfully deleted.";
        } else {
            echo "No book found with ID $bookId.";
        }
    } else {
        echo "Error deleting book: " . $stmt->error;
    }
    
    // Close the statement
    $stmt->close();
}


// Function to view book details
function viewBookDetails($bookId) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM `books` WHERE `id` = ?");
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        return $book; // Return book details as an associative array
    } else {
        echo "No book found with ID $bookId.";
        return null;
    }
    $stmt->close();
}

// Function to edit book details
function editBookDetails($bookId, $newData) {
    global $conn;
    $stmt = $conn->prepare(
        "UPDATE `books` SET 
        `title` = ?, 
        `author` = ?, 
        `genre` = ?, 
        `description` = ?, 
        `isbn` = ?, 
        `publisher` = ?, 
        `pages` = ?, 
        `language` = ?, 
        `total_copies` = ?, 
        `cover` = ? 
        WHERE `id` = ?"
    );
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }
    
    // Bind new data and book ID
    $stmt->bind_param(
        "ssssssisssi",
        $newData['title'],
        $newData['author'],
        $newData['genre'],
        $newData['description'],
        $newData['isbn'],
        $newData['publisher'],
        $newData['pages'],
        $newData['language'],
        $newData['total_copies'],
        $newData['cover'],
        $bookId
    );

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Book with ID $bookId successfully updated.";
        } else {
            echo "No changes were made to book with ID $bookId.";
        }
    } else {
        echo "Error updating book: " . $stmt->error;
    }
    $stmt->close();
}










?>
