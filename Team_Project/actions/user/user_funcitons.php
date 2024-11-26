<?php
include 'config.php';
// Check the connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Start session
session_start();

// Authenticate user (temporary login for testing)
if (!isset($_SESSION['id'])) {
    // Simulate login: Replace this with actual authentication logic
    $_SESSION['id'] = 1; // Replace with a valid user ID from your users table
    $_SESSION['role'] = 'admin'; // Set role as 'admin' or 'user'
}

// Action handler
$action = $_REQUEST['action'] ?? '';
if ($_SESSION['role'] !== 'admin' && $action !== 'fetch') {
    echo json_encode(['message' => 'Access denied. Only admins can perform this action.']);
    exit;
}

switch ($action) {
    case 'fetch':
        fetchItems($conn);
        break;
    case 'create':
        createItem($conn);
        break;
    case 'delete':
        deleteItem($conn);
        break;
    default:
        echo json_encode(['message' => 'Invalid action']);
        break;
}

// Fetch items
function fetchItems($conn) {
    $type = $_GET['type'] ?? 'catalog'; // Use 'catalog', 'events', or 'users'
    $result = $conn->query("SELECT * FROM $type");

    if ($result) {
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        echo json_encode($items);
    } else {
        echo json_encode(['message' => 'Error fetching data: ' . $conn->error]);
    }
}

// Create item
function createItem($conn) {
    $type = $_POST['type'] ?? 'catalog'; // Use 'catalog', 'events', or 'users'
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    // $price = $_POST['price'] ?? null;

    $stmt = $conn->prepare("INSERT INTO $type (name, description) VALUES (?, ?)");
    $stmt->bind_param("ssd", $name, $description);

    if ($stmt->execute()) {
        echo "Item created successfully!";
    } else {
        echo "Error creating item: " . $stmt->error;
    }
    $stmt->close();
}

// Delete item
function deleteItem($conn) {
    $type = $_POST['type'] ?? 'catalog'; // Use 'catalog', 'events', or 'users'
    $id = $_POST['id'] ?? 0;

    $stmt = $conn->prepare("DELETE FROM $type WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Item deleted successfully!";
    } else {
        echo "Error deleting item: " . $stmt->error;
    }
    $stmt->close();
}
?>