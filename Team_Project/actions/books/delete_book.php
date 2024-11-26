<?php
session_start();
require_once '../../db/config.php';

// Check if user is logged in and is an admin/librarian
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'librarian'])) {
    $_SESSION['error'] = "Unauthorized access";
    header("Location: ../../view/login.php");
    exit();
}

// Check if it's a POST request and has book_id
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $book_id = (int)$_POST['book_id'];
    
    // First, check if the book exists and get its cover image
    $check_sql = "SELECT cover FROM books WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $book_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Book not found";
        header("Location: ../../view/manage.php");
        exit();
    }
    
    $book = $result->fetch_assoc();
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Check if book has any active borrowings
        $check_borrows = "SELECT COUNT(*) as active_borrows FROM borrowing_records 
                         WHERE book_id = ? AND status = 'borrowed'";
        $borrow_stmt = $conn->prepare($check_borrows);
        $borrow_stmt->bind_param("i", $book_id);
        $borrow_stmt->execute();
        $borrow_result = $borrow_stmt->get_result();
        $active_borrows = $borrow_result->fetch_assoc()['active_borrows'];
        
        if ($active_borrows > 0) {
            throw new Exception("Cannot delete book: There are active borrowings");
        }
        
        // Delete related records first
        // Delete reservations
        $delete_reservations = "DELETE FROM reservations WHERE book_id = ?";
        $res_stmt = $conn->prepare($delete_reservations);
        $res_stmt->bind_param("i", $book_id);
        $res_stmt->execute();
        
        // Delete borrowing history
        $delete_borrows = "DELETE FROM borrowing_records WHERE book_id = ?";
        $borr_stmt = $conn->prepare($delete_borrows);
        $borr_stmt->bind_param("i", $book_id);
        $borr_stmt->execute();
        
        // Finally, delete the book
        $delete_book = "DELETE FROM books WHERE id = ?";
        $book_stmt = $conn->prepare($delete_book);
        $book_stmt->bind_param("i", $book_id);
        $book_stmt->execute();
        
        // If successful, commit transaction
        $conn->commit();
        
        // Delete book cover if it exists and is not the default
        if ($book['cover'] !== 'default-book.jpg') {
            $cover_path = '../../assets/images/books/' . $book['cover'];
            if (file_exists($cover_path)) {
                unlink($cover_path);
            }
        }
        
        $_SESSION['success'] = "Book deleted successfully";
        
    } catch (Exception $e) {
        // If there's an error, rollback changes
        $conn->rollback();
        $_SESSION['error'] = $e->getMessage();
    }
    
    // Close all statements
    $check_stmt->close();
    if (isset($res_stmt)) $res_stmt->close();
    if (isset($borr_stmt)) $borr_stmt->close();
    if (isset($book_stmt)) $book_stmt->close();
    
} else {
    $_SESSION['error'] = "Invalid request";
}

// Redirect back to manage books page
header("Location: ../../view/manage.php");
exit();

$conn->close();
?>