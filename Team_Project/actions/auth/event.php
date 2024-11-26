<?php
session_start();
require_once '../../db/config.php';

// var_dump($_SESSION['role']);
// exit;

// Ensure only admins can perform these actions
if ($_SESSION['role'] !== 'admin') {
    header('Location: /Team_Project/index.php');
    exit();
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $response = ['success' => false, 'message' => 'Invalid input.'];

    switch ($_POST['action']) {
        // Create Event
        case 'create':
            // var_dump($_POST);
            // exit;
            $title = $_POST['event_name'] ?? '';
            $description = $_POST['description'] ?? '';
            $date = $_POST['date'] ?? '';
            $time = $_POST['time'] ?? '';
            $location = $_POST['location'] ?? '';

            if (empty($title) || empty($date) || empty($location)) {
                $response['message'] = 'Required fields are missing.';
            } else {
                $query = "INSERT INTO events (event_name, description, event_date, event_time, location) 
                          VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "sssss", $title, $description, $date, $time, $location);

                if (mysqli_stmt_execute($stmt)) {
                    $response = ['success' => true, 'message' => 'Event created successfully.'];
                } else {
                    $response['message'] = 'Failed to create event.';
                }
            }
            break;

        // Update Event
        case 'update':
            $event_id = intval($_POST['event_id'] ?? 0);
            $title = $_POST['event_name'] ?? '';
            $description = $_POST['description'] ?? '';
            $date = $_POST['date'] ?? '';
            $time = $_POST['time'] ?? '';
            $location = $_POST['location'] ?? '';

            if ($event_id <= 0 || empty($title) || empty($date) || empty($location)) {
                $response['message'] = 'Invalid or missing input fields.';
            } else {
                $query = "UPDATE events SET event_name=?, description=?, event_date=?, event_time=?, location=? 
                          WHERE event_id=?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "sssssi", $title, $description, $date, $time, $location, $event_id);

                if (mysqli_stmt_execute($stmt)) {
                    $response = ['success' => true, 'message' => 'Event updated successfully.'];
                } else {
                    $response['message'] = 'Failed to update event.';
                }
            }
            break;

        // Delete Event
        case 'delete':
            $event_id = intval($_POST['event_id'] ?? 0);

            if ($event_id <= 0) {
                $response['message'] = 'Invalid event ID.';
            } else {
                $query = "DELETE FROM events WHERE event_id = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $event_id);

                if (mysqli_stmt_execute($stmt)) {
                    $response = ['success' => true, 'message' => 'Event deleted successfully.'];
                } else {
                    $response['message'] = 'Failed to delete event.';
                }
            }
            break;

        default:
            $response['message'] = 'Unsupported action.';
    }

    echo json_encode($response);
    exit();
}
?>
