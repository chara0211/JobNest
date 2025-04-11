<?php
session_start();

// Check if the user is logged in and is an employer
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'employer') {
    // Redirect to login page if not logged in or not an employer
    header('Location: login.php');
    exit;
}

// Check if the job ID is provided in the URL
if (!isset($_GET['id'])) {
    // Redirect to manage jobs page if job ID is not provided
    header('Location: manage_jobs.php');
    exit;
}

// Include database connection
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "login";

try {
    $bdd = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error_message = "Erreur : " . $e->getMessage();
}

// Delete the job from the database
$id = $_GET['id'];
$stmt = $bdd->prepare("DELETE FROM jobs WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

// Redirect back to manage jobs page after deleting the job
header('Location: manage_jobs.php');
exit;
?>
