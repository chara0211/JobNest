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
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "login";
$error_message = "";

try {
    $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error_message = "Erreur : " . $e->getMessage();
}

// Check if the user is logged in and is an employer
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'employer') {
    // Redirect to login page if not logged in or not an employer
    header('Location: login.php');
    exit;
}
// Fetch jobs associated with the logged-in employer
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM jobs WHERE employer_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<header class="header">
    <section class="flex">
        <div id="menu-btn" class="fas fa-bars"></div>
        <a href="<?php echo $homepageURL; ?>" class="logo"><i class="fas fa-briefcase"></i> JobNest</a>
        <nav class="navbar">
            <a href="<?php echo $homepageURL; ?>">Home</a>
            <a href="about.php">About</a>
            <a href="jobs.php">All jobs</a>
            <a href="favorites.php">Favorites</a>
            <a href="post1.php">Post a job</a>
            <a href="notifications.php">Notifications <i class="fas fa-bell"></i> <span class="badge" id="notificationCount"></span></a>
        </nav>
        <?php if($isLoggedIn) : ?>
            <a href="logout.php" class="btn" style="margin-top: 0%;">Logout</a>
        <?php else : ?>
            <a href="login.php" class="btn" style="margin-top: 0%;">Login</a>
        <?php endif; ?>
    </section>
</header>


    <!-- Job Management Section -->
    <section class="job-management">
    <h1 class="heading">Your posted jobs</h1>
        <section class="jobs-conatiner">
            <?php foreach ($jobs as $job): ?>
                <div class="box">
                    <div class="company">
                        <img src="Photos/<?php echo htmlspecialchars($job['company_image']); ?>" alt="">
                        <div>
                            <h3><?php echo htmlspecialchars($job['category']); ?></h3>
                            <span><?php echo (new DateTime())->diff(new DateTime($job['created_at']))->days; ?> days ago</span>
                        </div>
                        <h3 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h3>
                        <p class="location"><i class="fas fa-map-marker-alt"></i><span><?php echo htmlspecialchars($job['location']); ?></span></p>
                        <div class="tags">
                            <p><i class="fas fa-dollar-sign"></i><span><?php echo htmlspecialchars($job['salary']); ?></span></p>
                            <p><i class="fas fa-briefcase"></i><span><?php echo htmlspecialchars($job['work_type']); ?></span></p>
                            <p><i class="fas fa-clock"></i><span><?php echo htmlspecialchars($job['working_hours']); ?></span></p>
                            <p><i class="fas fa-file-contract"></i><span><?php echo htmlspecialchars($job['contract_type']); ?></span></p>
                        </div>
                        <div class="flex-btn">
                            <a href="delete_job.php?id=<?php echo $job['id']; ?>" class="btn" onclick="confirmDelete(<?php echo $job['id']; ?>)">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- footer-->
<footer class="footer">
    <section class="grid">
        <div class="boxX">
            <h3>Quick links</h3>
            <a href="home.php"><i class="fas fa-angle-right"></i>home</a>
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
            <a href="#" ><i class="fab fa-facebook-f"></i> Facebook</a>
            <a href="#" ><i class="fab fa-twitter"></i> Twitter</a>
            <a href="#" ><i class="fab fa-instagram"></i>instagram</a>
            <a href="#" ><i class="fab fa-linkedin"></i> linkedin</a>
            <a href="#" ><i class="fab fa-youtube"></i> youtube</a>
        </div>
    </section>
    <div class="credit">&copy;copyright @2024 by <span>IAGI-1</span> | all right deserved</div>
</footer>
   <!-- js  -->
   <script src="javascript.js"></script>
   <script>
function confirmDelete(jobId) {
    var confirmDelete = confirm('Are you sure you want to delete this job?');
    if (confirmDelete) {
        window.location.href = 'delete_job.php?id=' + jobId;
    }
}
</script>

</body>
</html>
