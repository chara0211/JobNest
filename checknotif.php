<?php
// check_notifications.php
session_start();
$userId = $_SESSION['user_id'];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = '$userId' AND is_read = FALSE";
$result = $conn->query($query);
$row = $result->fetch_assoc();

echo json_encode(['unread_count' => $row['unread_count']]);
?>