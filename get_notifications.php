<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = ['count' => 0, 'notifications' => []];

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Count unread notifications
    $sql_count = "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->bind_param("i", $user_id);
    $stmt_count->execute();
    $result_count = $stmt_count->get_result();
    if ($result_count) {
        $row_count = $result_count->fetch_assoc();
        $response['count'] = $row_count['count'];
    }

    // Fetch notifications for the logged-in user
    $sql_notifications = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
    $stmt_notifications = $conn->prepare($sql_notifications);
    $stmt_notifications->bind_param("i", $user_id);
    $stmt_notifications->execute();
    $result_notifications = $stmt_notifications->get_result();
    if ($result_notifications && $result_notifications->num_rows > 0) {
        while ($row = $result_notifications->fetch_assoc()) {
            $response['notifications'][] = $row;
        }
    }

    $stmt_count->close();
    $stmt_notifications->close();
}

echo json_encode($response);

$conn->close();
?>
