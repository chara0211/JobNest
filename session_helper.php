<?php
session_start();

function isLoggedInEmployer() {
    return isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'employer';
}

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
function isEmployer() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'employer';
}
// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
// Dynamically set the homepage URL based on the user type
        if (isLoggedInEmployer()) {
            $homepageURL = 'employer_home.php';
        } elseif (isLoggedIn() && !isEmployer()) {
            $homepageURL = 'job_seeker.php';
        } else {
            $homepageURL = 'home.php';
        }
?>
