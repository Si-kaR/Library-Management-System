<?php
// Start the session at the very beginning of the PHP script

include '../db/config.php'; // Include the database connection after session_start

function getAllBorrowedBooks($conn) {

    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        return ["error" => "You need to be logged in to view borrowed books."];
    }

    $user_id = $_SESSION['user_id']; // Retrieve user ID from session

    // Prepare the SQL query to fetch borrowed books for the user
    $stmt = $conn->prepare("
        SELECT 
            b.id AS book_id, 
            b.title AS bookTitle, 
            br.borrow_date, 
            br.due_date, 
            br.return_date, 
            br.status, 
            br.id AS borrowed_id
        FROM 
            borrowing_records br
        JOIN 
            books b ON br.book_id = b.id
        WHERE 
            br.user_id = ? AND br.status = 'borrowed'
    ");

    if ($stmt) {
        $stmt->bind_param("i", $user_id); // Bind the user ID to the query
        $stmt->execute();
        $result = $stmt->get_result();

        $borrowedBooks = [];
        if ($result->num_rows > 0) {
            // Fetch all the results into an array
            while ($row = $result->fetch_assoc()) {
                $borrowedBooks[] = $row;
            }
        }

        $stmt->close();
        return $borrowedBooks; // Return the array of borrowed books
    } else {
        return ["error" => "Error retrieving borrowed books."];
    }
}
?>
