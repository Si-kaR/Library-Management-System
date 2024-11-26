<?php
session_start();
require_once '../../db/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to reserve books";
    header("Location: ../../view/auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $book_id = (int)$_POST['book_id'];
    $user_id = $_SESSION['user_id'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Check if book exists
        $check_sql = "SELECT title, available_copies FROM books WHERE id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $book_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Book not found");
        }
        
        $book = $result->fetch_assoc();
        
        // Check if user already has borrowed or reserved this book
        $check_existing = "SELECT 
            (SELECT COUNT(*) FROM borrowing_records 
             WHERE user_id = ? AND book_id = ? AND status = 'borrowed') as has_borrowed,
            (SELECT COUNT(*) FROM reservations 
             WHERE user_id = ? AND book_id = ? AND status = 'pending') as has_reserved";
        
        $existing_stmt = $conn->prepare($check_existing);
        $existing_stmt->bind_param("iiii", $user_id, $book_id, $user_id, $book_id);
        $existing_stmt->execute();
        $existing_result = $existing_stmt->get_result();
        $existing = $existing_result->fetch_assoc();
        
        if ($existing['has_borrowed'] > 0) {
            throw new Exception("You already have this book borrowed");
        }
        
        if ($existing['has_reserved'] > 0) {
            throw new Exception("You already have this book reserved");
        }
        
        // Check if book is available (don't allow reservations if copies are available)
        if ($book['available_copies'] > 0) {
            throw new Exception("This book is currently available. You can borrow it instead.");
        }
        
        // Check user's total active reservations
        $check_limit = "SELECT COUNT(*) as active_reservations 
                       FROM reservations 
                       WHERE user_id = ? AND status = 'pending'";
        $limit_stmt = $conn->prepare($check_limit);
        $limit_stmt->bind_param("i", $user_id);
        $limit_stmt->execute();
        $limit_result = $limit_stmt->get_result();
        $active_reservations = $limit_result->fetch_assoc()['active_reservations'];
        
        if ($active_reservations >= 3) { // Maximum 3 active reservations
            throw new Exception("You have reached the maximum number of active reservations");
        }
        
        // Create reservation
        $reservation_date = date('Y-m-d H:i:s');
        $reserve_sql = "INSERT INTO reservations (user_id, book_id, reservation_date, status) 
                       VALUES (?, ?, ?, 'pending')";
        $reserve_stmt = $conn->prepare($reserve_sql);
        $reserve_stmt->bind_param("iis", $user_id, $book_id, $reservation_date);
        $reserve_stmt->execute();
        
        // Commit transaction
        $conn->commit();
        
        $_SESSION['success'] = "You have successfully reserved '" . $book['title'] . 
                             "'. We will notify you when it becomes available.";
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = $e->getMessage();
    }
    
    // Close statements
    if (isset($check_stmt)) $check_stmt->close();
    if (isset($existing_stmt)) $existing_stmt->close();
    if (isset($limit_stmt)) $limit_stmt->close();
    if (isset($reserve_stmt)) $reserve_stmt->close();
    
} else {
    $_SESSION['error'] = "Invalid request";
}

// Redirect back
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../../view/books/catalog.php';
header("Location: " . $redirect);
exit();

$conn->close();
?>