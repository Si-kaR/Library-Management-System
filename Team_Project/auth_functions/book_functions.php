<?php
include '../db/config.php';
function displayBooks($conn) {
    // SQL query to fetch book details
    $sql = "SELECT `id`, `title`, `author`, `cover`, `isbn` FROM `books`";
    $result = $conn->query($sql);

    // Check if the query returns any rows
    if ($result->num_rows > 0) {
        // Loop through each book and create a table row for each
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            
            // Cover
            if (!empty($row['cover'])) {
                echo "<td><img src='../Team_Project/uploads" . htmlspecialchars($row['cover']) . "' alt='" . htmlspecialchars($row['title']) . " cover' style='width:50px;height:auto;' /></td>";
            } else {
                echo "<td>No Cover</td>";
            }
            
            // Title
            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
            
            // Author
            echo "<td>" . htmlspecialchars($row['author']) . "</td>";
            
            // ISBN
            echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
            
            // Status (example status: Available or Checked Out)
            $status = rand(0, 1) ? "Available" : "Checked Out"; // Replace with actual status logic if available
            echo "<td>" . $status . "</td>";
            
            // Actions (example actions: View/Edit/Delete)
            echo "<td>";
            echo "<a href='view_book.php?book_id=" . $row['id'] . "'>View</a> | ";
            echo "<a href='edit_book.php?id=" . $row['id'] . "'>Edit</a> | ";
            echo "<a href='../actions/books/delete_book_action.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this book?');\">Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No books found.</td></tr>";
    }
}


function displayCatalogBook($conn, $title, $author, $isbn, $genre, $cover = null) {
    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, genre, cover) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $author, $isbn, $genre, $cover);

    // Execute the query
    if ($stmt->execute()) {
        return true; // Book added successfully
    } else {
        return false; // Error occurred
    }
}

//Calling the function
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $genre = $_POST['genre'];
    $cover = null;

    // Handle file upload for cover
    if (!empty($_FILES['cover']['name'])) {
        $upload_dir = "../uploads/";
        $cover = basename($_FILES['cover']['name']);
        $target_file = $upload_dir . $cover;

        if (!move_uploaded_file($_FILES['cover']['tmp_name'], $target_file)) {
            $cover = null; // Fallback if upload fails
        }
    }

    if (displayCatalogBook($conn, $title, $author, $isbn, $genre, $cover)) {
        echo "Book added successfully!";
    } else {
        echo "Failed to add book.";
    }
}



?>






