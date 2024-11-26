<?php
include '../../db/config.php';

session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debugging: Check if book_id is received
    if (!isset($_POST['book_id']) || empty($_POST['book_id'])) {
        echo "Book ID not received. Debug Info: ";
        var_dump($_POST); // Print the entire $_POST array
        exit();
    }

    $book_id = $_POST['book_id']; // This should now work
    $user_id = $_SESSION['user_id'] ?? null; // Ensure user_id exists

    if (!$user_id) {
        echo "User not logged in.";
        exit();
    }

    // Check if the book is available
    $stmt = $conn->prepare("SELECT available_copies FROM books WHERE id = ? AND available_copies > 0");
    if ($stmt) {
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update the book status to 'borrowed'
            $insert = $conn->prepare("
            INSERT INTO borrowing_records 
            (book_id, user_id, status, borrow_date, due_date) 
            VALUES (?, ?, 'borrowed', NOW(), DATE_ADD(NOW(), INTERVAL 2 WEEK))
        ");
        $insert->bind_param("ii", $book_id, $user_id);
        
            if ($insert->execute()) {
                header("Location: ../../view/my_account.php"); // Adjust the redirect URL as needed
            } else {
                echo "Failed to borrow the book. Please try again.";
            }
        } else {
             var_dump($book_id); 
            echo "The book is not available for borrowing.";
        }

        $stmt->close();
    } else {
        echo "Failed to prepare the query.";
    }
}

?>