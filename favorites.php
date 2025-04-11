<?php
include 'session_helper.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $conn = new PDO('mysql:host=localhost;dbname=login;charset=utf8', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch favorite jobs
    $stmt = $conn->prepare("SELECT jobs.id, jobs.category, jobs.title, jobs.location, jobs.salary, jobs.work_type, jobs.working_hours, jobs.company_image, jobs.created_at, jobs.contract_type, jobs.company_name 
                            FROM jobs 
                            INNER JOIN favorites ON jobs.id = favorites.job_id 
                            WHERE favorites.user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $favorite_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites</title>
    <link rel="stylesheet" href="style.css">
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
</header>
    <h1 class="heading">Favorite Jobs</h1>
    <section class="jobs-conatiner">
        <?php foreach ($favorite_jobs as $job): ?>
            <div class="box">
                <div class="company">
                    <img src="Photos/<?php echo htmlspecialchars($job['company_image']); ?>" alt="">
                    <div>
                        <h3><?php echo htmlspecialchars($job['category']); ?></h3>
                        <span><?php echo (new DateTime())->diff(new DateTime($job['created_at']))->days; ?> days ago</span>
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
                        <a href="apply.php?job_id=<?php echo $job['id']; ?>" class="btn">View Details</a>
                        <i class="fa-solid fa-heart fa-2xl" style="color: #000000;"></i>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
    <!-- js  -->
<script src="javascript.js"></script>
</body>
</html>
