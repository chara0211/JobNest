<?php
include 'session_helper.php';

// Database connection
try {
    $conn = new PDO('mysql:host=localhost;dbname=login;charset=utf8', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All jobs</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <!--header-->
    <header class="header">
    <section class="flex">
        <div id="menu-btn" class="fas fa-bars"></div>
        <a href="<?php echo $homepageURL; ?>" class="logo"><i class="fas fa-briefcase"></i> JobNest</a>
        <nav class="navbar">
            <a href="<?php echo $homepageURL; ?>">Home</a>
            <a href="about.php">About</a>
            <a href="jobs.php">All jobs</a>
            <?php if(isLoggedIn()):?>
            <a href="favorites.php">Favorites</a>
            <?php endif; ?>
            <?php if(isEmployer()):?>
                <a href="post1.php">Post a job</a>
            <?php endif; ?>
            <?php if(isLoggedIn()):?>
                <a href="notifications.php">Notifications <i class="fas fa-bell"></i> <span class="badge" id="notificationCount"></span></a>
            <?php endif; ?>
        </nav>
        <?php if($isLoggedIn) : ?>
            <a href="logout.php" class="btn" style="margin-top: 0%;">Logout</a>
        <?php else : ?>
            <a href="login.php" class="btn" style="margin-top: 0%;">Login</a>
        <?php endif; ?>
    </section>
</header>
<!--Job section starts-->
        <h1 class="heading">Posted jobs</h1>
        <section class="jobs-conatiner">
        <?php
        // Fetch jobs from the database
        $stmt = $conn->query("SELECT id, category, title, location, salary, work_type, working_hours, company_image, created_at,contract_type,company_name FROM jobs");
        while ($job = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Calculate the days ago
            $created_at = new DateTime($job['created_at']);
            $now = new DateTime();
            $interval = $now->diff($created_at);
            $days_ago = $interval->days;
            ?>
            <div class="box">
                <div class="company">
                    <img src="Photos/<?php echo htmlspecialchars($job['company_image']); ?>" alt="">
                    <div>
                        <h3><?php echo htmlspecialchars($job['category']); ?></h3>
                        <span><?php echo $days_ago; ?> days ago</span>
                    </div>
                    <h3 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h3>
                    <p class="company-name"><i class="fas fa-building"></i><span><?php echo htmlspecialchars($job['company_name']); ?></span></p>
                    <p class="location"><i class="fas fa-map-marker-alt"></i><span><?php echo htmlspecialchars($job['location']); ?></span></p>
                    <div class="tags">
                        <p><i class="fas fa-dollar-sign"></i><span><?php echo htmlspecialchars($job['salary']); ?></span></p>
                        <p><i class="fas fa-briefcase"></i><span><?php echo htmlspecialchars($job['work_type']); ?></span></p>
                        <p><i class="fas fa-clock"></i><span><?php echo htmlspecialchars($job['working_hours']); ?></span></p>
                        <p><i class="fas fa-file-contract"></i><span><?php echo htmlspecialchars($job['contract_type']); ?></span></p>

                    </div>
                    <div class="flex-btn">
                        <a href="apply.php?job_id=<?php echo $job['id']; ?>" class="btn">view details</a>
                        <button type="button" class="far fa-heart heart-btn" onclick="addFavorite(<?php echo $job['id']; ?>)"></button>
                    </div>
                </div>    
            </div>
            <?php
        }
        ?>
    </section>
    
    <!-- footer-->
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
        <div class="credit">&copy;copyright @2024 by <span>IAGI-1</span> | all rights reserved</div>
    </footer>

    <!-- js  -->
    <script src="javascript.js"></script>


<!-- js  -->
<script src="javascript.js"></script>
    <script>
    function addFavorite(jobId) {
        fetch('add_favorite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `job_id=${jobId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Job added to favorites');
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    </script>
</body>
</html>    
       
