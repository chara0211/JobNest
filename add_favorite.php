<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$job_id = $_POST['job_id'];

try {
    $conn = new PDO('mysql:host=localhost;dbname=login;charset=utf8', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the job is already in favorites
    $stmt = $conn->prepare("SELECT * FROM favorites WHERE user_id = :user_id AND job_id = :job_id");
    $stmt->execute(['user_id' => $user_id, 'job_id' => $job_id]);

    if ($stmt->rowCount() == 0) {
        // Add to favorites
        $stmt = $conn->prepare("INSERT INTO favorites (user_id, job_id) VALUES (:user_id, :job_id)");
        $stmt->execute(['user_id' => $user_id, 'job_id' => $job_id]);
        echo json_encode(['status' => 'success', 'message' => 'Job added to favorites']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Job already in favorites']);
    }
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
