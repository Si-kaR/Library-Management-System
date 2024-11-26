<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../db/config.php';
// $events = fetchEvents($conn);

// foreach ($events as $event) {
//     echo "Event Name: " . $event['event_name'] . "<br>";
//     echo "Description: " . $event['description'] . "<br>";
//     echo "Location: " . $event['location'] . "<br>";
//     echo "Date: " . $event['event_date'] . "<br><br>";
// }

$response = ['success' => false, 'data' => ''];


$query = "SELECT event_id, event_name, description, location, event_date FROM events ORDER BY event_date ASC";
$result = mysqli_query($conn, $query);

try {
    $events = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }
    }

    $response['success'] = true;
    $response['data'] = $events;

} catch (\Throwable $th) {
    $response['success'] = false;
    $response['data'] = [];
}

echo json_encode($response);

// function fetchEvents($conn)
// {
   
// }
