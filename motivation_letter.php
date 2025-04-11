<?php
session_start();

// Database connection
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "login";

try {
    $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Fetch the motivation letter
if (isset($_GET['id'])) {
    $applicationId = $_GET['id'];
    $stmt_motivation_letter = $conn->prepare("SELECT motivation_letter FROM applications WHERE id = :id");
    $stmt_motivation_letter->bindParam(':id', $applicationId);
    $stmt_motivation_letter->execute();
    $motivationLetter = $stmt_motivation_letter->fetchColumn();
} else {
    // If no ID provided, redirect back
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motivation Letter</title>
    <style>
        /* CSS for motivation letter */
        .motivation-letter {
            border: 2px solid #61ddc2; /* Border color */
            padding: 20px; /* Adjust padding */
            background-color: #f8f9fa; /* Background color */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
            max-width: 600px; /* Limit the width of the letter */
            margin: 20px auto; /* Center the letter */
            font-family: Arial, sans-serif; /* Font style */
            font-size: 16px; /* Font size */
            line-height: 1.5; /* Line height */
        }

        /* Additional styling for links inside motivation letter */
        .motivation-letter a {
            color: #61ddc2; /* Link color */
            text-decoration: none; /* Remove underline */
        }

        .motivation-letter a:hover {
            text-decoration: underline; /* Add underline on hover */
        }
    </style>
</head>
<body>
    <div class="motivation-letter">
        <h1>Motivation Letter</h1>
        <p><?php echo $motivationLetter; ?></p>
    </div>
</body>
</html>