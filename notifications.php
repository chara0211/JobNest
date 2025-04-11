<?php
session_start();
// Function to check if the user is logged in and if they are an employer
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
if (isLoggedInEmployer()) {
    $homepageURL = 'employer_home.php';
} elseif (isLoggedIn() && !isEmployer()) {
    $homepageURL = 'job_seeker.php';
} else {
    $homepageURL = 'home.php';
}

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM notifications ORDER BY created_at DESC";
    $result = $conn->query($sql);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="notif.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <section class="flex">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="<?php echo $homepageURL; ?>" class="logo"><i class="fas fa-briefcase"></i> JobNest</a>
            <nav class="navbar">
                <a href="<?php echo $homepageURL; ?>">Home</a>
                <a href="about.php">About</a>
                <a href="jobs.php">All jobs</a>
                <?php if(isLoggedIn()): ?>
                    <a href="favorites.php">Favorites</a>
                <?php endif; ?>
                <?php if(isLoggedInEmployer()): ?>
                    <a href="post1.php">Post a job</a>
                <?php endif; ?>
                <a href="notifications.php">Notifications <i class="fas fa-bell"></i> <span class="badge" id="notificationCount"></span></a>
            </nav>
            <?php if($isLoggedIn) : ?>
                <a href="logout.php" class="btn" style="margin-top: 0%;">Logout</a>
            <?php else : ?>
                <a href="login.php" class="btn" style="margin-top: 0%;">Login</a>
            <?php endif; ?>
        </section>
    </header>
    <!-- End Header -->

    <!-- Main Section -->
    <div class="home-container">
        <section class="home">
            <div class="notifications-container" id="notificationList"></div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <section class="grid">
            <div class="boxX">
                <h3>Quick links</h3>
                <a href="home.html"><i class="fas fa-angle-right"></i>home</a>
                <a href="about.html"><i class="fas fa-angle-right"></i>about</a>
                <a href="jobs.html"><i class="fas fa-angle-right"></i>all jobs</a>
                <a href="jobs.html"><i class="fas fa-angle-right"></i>contact us</a>
                <a href="#"><i class="fas fa-angle-right"></i>Filter search</a>
            </div>

            <div class="boxX">
                <h3>Extra links</h3>
                <a href="#"><i class="fas fa-angle-right"></i>account</a>
                <a href="login.html"><i class="fas fa-angle-right"></i>login</a>
                <a href="register.html"><i class="fas fa-angle-right"></i>register</a>
                <a href="jobs.html"><i class="fas fa-angle-right"></i>contact us</a>
                <a href="#"><i class="fas fa-angle-right"></i>Post jobs</a>
                <a href="#"><i class="fas fa-angle-right"></i>dashboard</a>
            </div>

            <div class="boxX">
                <h3>Follow us</h3>
                <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
                <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
                <a href="#"><i class="fab fa-instagram"></i>instagram</a>
            <a href="#" ><i class="fab fa-linkedin"></i> linkedin</a>
            <a href="#" ><i class="fab fa-youtube"></i> youtube</a>
        </div>
    </section>
    <div class="credit">&copy;copyright @2024 by<span>IAGI-1</span> | all right deserved</div>
</footer>


    <script>
        fetch('get_notifications.php')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('notificationList');
                if (data.notifications && data.notifications.length > 0) {
                    data.notifications.forEach(notification => {
                        const notificationItem = document.createElement('div');
                        notificationItem.className = 'notification';

                        notificationItem.classList.add(notification.type || 'message'); 

                        notificationItem.innerHTML = `
                            <div class="content">
                                <div class="icon"><i class="fas fa-bell"></i></div>
                                <div class="details">
                                    <h4>${notification.message}</h4>
                                    <p class="time">${new Date(notification.created_at).toLocaleString()}</p>
                                </div>
                            </div>
                            <button class="close-btn"><i class="fas fa-times"></i></button>
                        `;
                        list.appendChild(notificationItem);
                    });
                } else {
                    const noNotification = document.createElement('p');
                    noNotification.textContent = 'Aucune notification';
                    list.appendChild(noNotification);
                }

                
                if (data.count > 0) {
                    document.getElementById('notificationCount').textContent = data.count;
                }
            });
    </script>
</body>
</html>
