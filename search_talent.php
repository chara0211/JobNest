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

try {
    $conn = new PDO('mysql:host=localhost;dbname=login;charset=utf8', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (isset($_POST['search_talent'])) {
    $experience_level = $_POST['experience_level'];
    $current_position = $_POST['talent_title'];

    $sql = "SELECT * FROM user WHERE experience_level = :experience_level AND current_position = :current_position";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':experience_level', $experience_level);
    $stmt->bindParam(':current_position', $current_position);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['search_results'] = $results;

    header("Location: search_results.php");
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
<div class="home-container">
<section class="home">
<form action="search_talent.php" method="post">
                <h1>Find talented professionals</h1>
                <p>Talent title</p>
                <input type="text" name="talent_title" placeholder="skill, expertise or profession" required maxlength="20" class="input">
                
                <p>Experience Level</p>
                <select name="experience_level" class="input" required>
                    <option value="" disabled selected>Select Experience Level</option>
                    <option value="entry">Entry Level</option>
                    <option value="junior">Junior Level</option>
                    <option value="mid">Mid Level</option>
                    <option value="senior">Senior Level</option>
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
                <input type="submit" value="Search Talent" name="search_talent" class="btn">
                
            </form>
            </section>
            </div>
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