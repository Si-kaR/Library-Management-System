<?php
session_start();
require_once '../../db/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to continue";
    header("Location: ../../view/auth/login.php");
    exit();
}

// var_dump($_POST);
// exit;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $borrowing_id = (int)$_POST['borrowed_id'];
    $user_id = $_SESSION['user_id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Get borrowing record and book details
        $check_sql = "SELECT br.*, b.title, b.id as book_id 
                     FROM borrowing_records br 
                     JOIN books b ON br.book_id = b.id 
                     WHERE br.id = ? AND br.user_id = ? AND br.status = 'borrowed'";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $borrowing_id, $user_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Invalid borrowing record");
        }

        $borrowing = $result->fetch_assoc();

        // Update borrowing record
        $return_date = date('Y-m-d H:i:s');
        $return_sql = "UPDATE borrowing_records 
                      SET status = 'returned', return_date = ? 
                      WHERE id = ?";
        $return_stmt = $conn->prepare($return_sql);
        $return_stmt->bind_param("si", $return_date, $borrowing_id);
        $return_stmt->execute();

        // Update available copies
        $update_sql = "UPDATE books 
                      SET available_copies = available_copies + 1 
                      WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $borrowing['book_id']);
        $update_stmt->execute();

        // Check if book was overdue
        $is_overdue = strtotime($borrowing['due_date']) < time();

        // Commit transaction
        $conn->commit();

        // Set success message
        if ($is_overdue) {
            $_SESSION['warning'] = "Book returned late. Late fees may apply.";
            // Redirect back to borrowing history
            header("Location: ../../view/my_account.php?msg=late_fees");
            exit();
        } else {
            $_SESSION['success'] = "Book '" . $borrowing['title'] . "' returned successfully.";
            // Redirect back to borrowing history
            header("Location: ../../view/my_account.php?msg=booked_return");
            exit();
            // var_dump("Returned successfully");
            // exit;
        }
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = $e->getMessage();
    }

    // Close statements
    if (isset($check_stmt)) $check_stmt->close();
    if (isset($return_stmt)) $return_stmt->close();
    if (isset($update_stmt)) $update_stmt->close();
} else {
    $_SESSION['error'] = "Invalid request";
}



$conn->close();
