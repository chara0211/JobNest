<?php
session_start();
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "login";
// Check if the user is logged in and if they are an employer
function isLoggedInEmployer() {
    return isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'employer';
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
function isEmployer() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'employer';
}
// Function to get the username from the session
function getUsername() {
    if (isset($_SESSION['user_id'])) {
        global $bdd; // Access the global PDO object
        $user_id = $_SESSION['user_id'];
        $stmt = $bdd->prepare("SELECT username FROM user WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return $result['username'];
        } else {
            return 'Job Seeker';
        }
    } else {
        return 'Job Seeker';
    }
}
// Dynamically set the homepage URL based on the user type
if (isLoggedInEmployer()) {
    $homepageURL = 'employer_home.php';
} elseif (isLoggedIn() && !isEmployer()) {
    $homepageURL = 'job_seeker.php';
} else {
    $homepageURL = 'home.php';
}
try {
    $bdd = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

if (isset($_POST['title']) && isset($_POST['category']) && isset($_POST['location']) && isset($_POST['contract_type'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    $contract_type = $_POST['contract_type'];

    // Prepare and execute the query
    $stmt = $bdd->prepare("SELECT * FROM jobs WHERE title LIKE :title AND category = :category AND location = :location AND contract_type = :contract_type");
    $stmt->execute([
        ':title' => '%' . $title . '%',
        ':category' => $category,
        ':location' => $location,
        ':contract_type' => $contract_type
    ]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Pass the results to the search_results_jobs.php page
    session_start();
    $_SESSION['search_results'] = $results;

    header("Location: search_results_jobs.php");
    exit();
} 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Talent</title>
    <link rel="stylesheet" href="style.css">
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
            <a href="favorites.php">Favorites</a>
            <?php if(isEmployer()):?>
                <a href="post1.php">Post a job</a>
            <?php endif; ?>
            <a href="notifications.php">Notifications <i class="fas fa-bell"></i> <span class="badge" id="notificationCount"></span></a>
        </nav>
            <a href="logout.php" class="btn" style="margin-top: 0%;">Logout</a>
    </section>
</header>
<!-- header section ends-->
<div class="home-container">
        <section class="home">
            <form action="search_jobs.php"method="post">
                <h1>Find your next job easily</h1>
                <p>Job title</p>
                <input type="text" name="title" placeholder="keyword, category or company" required maxlength="20" class="input">
                <p>Category:</p>
                <select class="input" required name="category">
                    <option value="development">Development</option>
                    <option value="finance">Finance</option>
                    <option value="transportation">Transportation</option>
                    <option value="engineering">Engineering</option>
                    <option value="home-services">Home Services</option>
                    <option value="education">Education</option>
                    <option value="cuisine">Cuisine</option>
                    <option value="healthcare">Healthcare</option>
                    <option value="information-technology">Information Technology</option>
                    <option value="marketing">Marketing</option>
                    <option value="legal">Legal</option>
                    <option value="services">Services</option>
                    <!-- Add more categories here -->
                </select><br>
                <p>Job location</p>
                <input type="text" name="location" placeholder="location" required maxlength="200" class="input">

                </select>
                <p>Contract</p>
                <select name="contract_type" class="input" required>
                    <option value="" disabled selected>Select Contract Type</option>
                    <option value="stage">Stage (Internship) Contract</option>
                    <option value="cdd">CDD (Fixed-Term Contract)</option>
                    <option value="cdi">CDI (Permanent Contract)</option>
                    <option value="freelance">Freelance Contract</option>
                    <option value="temporary">Temporary Agency Contract</option>
                </select>
                <input type="submit" value="search job" name="search" class="btn">
                

            </form></section></div>
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
</body>
</html>
