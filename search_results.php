<?php
include 'session_helper.php';

if (!isset($_SESSION['search_results'])) {
    header("Location: search_talent.php");
    exit();
}
$results = $_SESSION['search_results'];
unset($_SESSION['search_results']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="results.css">
    <link rel="stylesheet" href="style.css">
</head>
<body><header class="header">
    <section class="flex">
        <div id="menu-btn" class="fas fa-bars"></div>
        <a href="<?php echo $homepageURL; ?>" class="logo"><i class="fas fa-briefcase"></i> JobNest</a>
        <nav class="navbar">
            <a href="<?php echo $homepageURL; ?>">Home</a>
            <a href="about.php">About</a>
            <a href="jobs.php">All jobs</a>
            <a href="favorites.php">Favorites</a>
            <?php if(isEmployer()):?>
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

<?php if (empty($results)) : ?>
    <p>No matching talents found.</p>
<?php else : ?>
    <div class="results-container">
        <?php foreach ($results as $result) : ?>
            <div class="result-item">
                <h3><?php echo htmlspecialchars($result['username']); ?></h3>
                <p>Experience Level: <?php echo htmlspecialchars($result['experience_level']); ?></p>
                <p>Current Position: <?php echo htmlspecialchars($result['current_position']); ?></p>
                <p>Country: <?php echo htmlspecialchars($result['country']); ?></p>
                <p>City: <?php echo htmlspecialchars($result['city']); ?></p>
                <p>Region: <?php echo htmlspecialchars($result['region']); ?></p>
                <p>Postal Code: <?php echo htmlspecialchars($result['postal_code']); ?></p>
                <p>LinkedIn: <a href="<?php echo htmlspecialchars($result['linkedin_url']); ?>"><?php echo htmlspecialchars($result['linkedin_url']); ?></a></p>
                <p>CV:
                    <?php
                    $cv_filename = htmlspecialchars($result['cv']);
                    $cv_url = $cv_filename;
                    ?>
                    <a href="<?php echo $cv_url; ?>" target="_blank">View CV</a>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<footer class="footer">
    <section class="grid">
        <div class="boxX">
            <h3>Quick links</h3>
            <a href="home.html"><i class="fas fa-angle-right"></i>home</a>
            <a href="about.html"><i class="fas fa-angle-right"></i>about</a>
            <a href="jobs.php"><i class="fas fa-angle-right"></i>all jobs</a>
            <a href="contact.html"><i class="fas fa-angle-right"></i>contact us</a>
            <a href="#"><i class="fas fa-angle-right"></i>Filter search</a>
        </div>

        <div class="boxX">
            <h3>Extra links</h3>
            <a href="#"><i class="fas fa-angle-right"></i>account</a>
            <a href="login.html"><i class="fas fa-angle-right"></i>login</a>
            <a href="register.html"><i class="fas fa-angle-right"></i>register</a>
            <a href="http://localhost/WP/POST/jobs.php"><i class="fas fa-angle-right"></i>contact us</a>
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
    <div class="credit">&copy;copyright @2024 by<span>IAGI-1</span> | all rights reserved</div>
</footer>

<!-- js  -->
<script src="javascript.js"></script>

</html>
