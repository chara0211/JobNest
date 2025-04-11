<?php
session_start();
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "login";
$error_message = "";

try {
    $bdd = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error_message = "Erreur : " . $e->getMessage();
}

// Check if the user is logged in and if they are an employer
function isLoggedInEmployer() {
    return isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'employer';
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
function getHomePageURL() {
    if (isLoggedInEmployer()) {
        return 'employer_home.php';
    } else {
        return 'job_seeker.php';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Home</title>
    <link rel="stylesheet" href="style.css">
    <style>
.home-container {
    text-align: center;
}
.welcome {
    margin-bottom: 40px;
}

.welcome h1 {
    font-size: 60px;
    color: #a9e0d4;
}

.welcome p {
    font-size: 30px;
}

.actions {
    margin-top: 40px;
}

.actions h2 {
    font-size: 30px;
}

.button-group {
    font size: 30px;
    display: flex;
    justify-content: center;
}

.btnn {
    transition: background-color 0.3s ease;
    margin: 0 10px; /* Add some margin between buttons*/
    display: inline-block;
    margin-top: 1rem;
    padding: 1rem 3rem;
    cursor: pointer;
    font-size: 20px;
    color:white;
    border-radius: 2rem;
    background-color: rgb(0, 0, 0);
    text-align: center;
    text-transform: capitalize;

}
.btnn:hover {
    color: black;
    background-color: #61ddc2;
} 
 
</style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <section class="flex">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="job_seeker.php" class="logo"><i class="fas fa-briefcase"></i> JobNest</a>
            <nav class="navbar">
                <a href="<?php echo getHomePageURL(); ?>">Home</a>
                <a href="about.php">About</a>
                <a href="jobs.php">All jobs</a>
                <a href="favorites.php">Favorites</a>
                <a href="notifications.php">Notifications <i class="fas fa-bell"></i> <span class="badge" id="notificationCount"></span></a>
            </nav>
            <a href="logout.php" class="btn" style="margin-top: 0%;">Logout</a>
        </section>
    </header>
    <!-- End Header -->

    <!-- Welcome Section -->
    <div class="home-container">
        <section class="home">
            <div class="container">
                <div class="welcome">
                    <h1>Welcome, Job Seeker!</h1>
                    <!-- Customize the welcome message for job seekers -->
                    <p>Welcome back, <?php echo htmlspecialchars(getUsername()); ?>!</p>
                </div>
                <!-- Actions Section -->
                <div class="actions">
                    <h2>What would you like to do today?</h2>
                    <br>
                    <!-- Add appropriate actions for job seekers -->
                    <div class="button-group">
                        <!-- Example action button -->
                        <a href="jobs.php" class="btnn">View available jobs</a>
                        <a href="manage_application.php"class="btnn">Manage your applications</a>
                        <a href="search_jobs.php" class="btnn">Search Jobs</a>
                        <!-- Add more buttons as needed -->
                    </div>
                </div>
            </div>
        </section>
    </div>
    <section class="category">
        <h1 class="heading">Job categories</h1>
        <div class="box-container">
            <a href="#" class="box">
                <i class="fas fa-code"></i>
                <div>
                    <h3>Developement</h3>
                    <span>2200 jobs</span>
                </div>

            </a>
            <!-- Finance -->
            <a href="#" class="box">
                <i class="fas fa-chart-line"></i>
                <div>
                    <h3>Finance</h3>
                    <span>2200 jobs</span>
                </div>
            </a>

            <!-- Transportation -->
            <a href="#" class="box">
                <i class="fas fa-plane"></i>
                <div>
                    <h3>Transportation</h3>
                    <span>1500 jobs</span>
                </div>
            </a>

            <!-- Engineering -->
            <a href="#" class="box">
                <i class="fas fa-cogs"></i>
                <div>
                    <h3>Engineering</h3>
                    <span>3000 jobs</span>
                </div>
            </a>

            <!-- Home Services -->
            <a href="#" class="box">
                <i class="fas fa-hammer"></i>
                <div>
                    <h3>Home Services</h3>
                    <span>1000 jobs</span>
                </div>
            </a>

            <!-- Education -->
            <a href="#" class="box">
                <i class="fas fa-graduation-cap"></i>
                <div>
                    <h3>Education</h3>
                    <span>1800 jobs</span>
                </div>
            </a>

            <!-- Cuisine -->
            <a href="#" class="box">
                <i class="fas fa-utensils"></i>
                <div>
                    <h3>Cuisine</h3>
                    <span>1200 jobs</span>
                </div>
            </a>

            <!-- Healthcare -->
            <a href="#" class="box">
                <i class="fas fa-medkit"></i>
                <div>
                    <h3>Healthcare</h3>
                    <span>2500 jobs</span>
                </div>
            </a>

            <!-- Information Technology (IT) -->
            <a href="#" class="box">
                <i class="fas fa-laptop-code"></i>
                <div>
                    <h3>Information Technology</h3>
                    <span>4000 jobs</span>
                </div>
            </a>

            <!-- Marketing -->
            <a href="#" class="box">
                <i class="fas fa-bullhorn"></i>
                <div>
                    <h3>Marketing</h3>
                    <span>2800 jobs</span>
                </div>
            </a>

            <!-- Legal -->
            <a href="#" class="box">
                <i class="fas fa-balance-scale"></i>
                <div>
                    <h3>Legal</h3>
                    <span>2000 jobs</span>
                </div>
            </a>
                <!-- Services -->
            <a href="#" class="box">
                <i class="fas fa-headphones"></i>
                <div>
                    <h3>Services</h3>
                    <span>1500 jobs</span>
                </div>
            </a>
            </div>

    </section>
    <!--Job section starts-->
    <h1 class="heading">Latest jobs</h1>
    <section class="jobs-conatiner">
        
        <div class="box">
            <div class="company">
                <img src="Photos/logo.jpg" alt="">
                <div>
                    <h3>IT</h3>
                    <span>3 days ago</span>

                </div>
            
                <h3 class="job-title">senior web developer</h3>
                <p class="location"><i class="fas fa-map-marker-alt"></i>
                <span>Casablanca,Morocco</span></p>
                <div class="tags">
                    <p><i class="fas fa-dollar-sign"></i><span>10000DH-30000DH</span></p>
                    <p><i class="fas fa-briefcase"><span>Full-time</span></i></p>
                    <p><i class="fas fa-clock"></i><span>day shift</span></p>

                </div>
            </div>    
        </div>
        <!-- Additional job listings -->
        <div class="box">
            <div class="company">
                <img src="Photos/m.avif" alt="">
                <div>
                    <h3>Marketing</h3>
                    <span>2 days ago</span>
                </div>
                <h3 class="job-title">Digital Marketing Specialist</h3>
                <p class="location"><i class="fas fa-map-marker-alt"></i>
                    <span>London, UK</span>
                </p>
                <div class="tags">
                    <p><i class="fas fa-dollar-sign"></i><span>£30,000 - £40,000</span></p>
                    <p><i class="fas fa-briefcase"></i><span>Full-time</span></p>
                    <p><i class="fas fa-clock"></i><span>Day Shift</span></p>
                </div>
                </div>
        </div>
        <div class="box">
            <div class="company">
                <img src="Photos/images.png" alt="">
                <div>
                    <h3>Education</h3>
                    <span>2 weeks ago</span>
                </div>
                <h3 class="job-title">Part-time Tutor</h3>
                <p class="location"><i class="fas fa-map-marker-alt"></i>
                    <span>Los Angeles, USA</span>
                </p>
                <div class="tags">
                    <p><i class="fas fa-dollar-sign"></i><span>$20 - $30 per hour</span></p>
                    <p><i class="fas fa-briefcase"></i><span>Part-time</span></p>
                    <p><i class="fas fa-clock"></i><span>Flexible Hours</span></p>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="company">
                <img src="Photos/meca.jpg" alt="">
                <div>
                    <h3>Engineering</h3>
                    <span>5 days ago</span>
                </div>
                <h3 class="job-title">Mechanical Engineer</h3>
                <p class="location"><i class="fas fa-map-marker-alt"></i>
                    <span>New York City, USA</span>
                </p>
                <div class="tags">
                    <p><i class="fas fa-dollar-sign"></i><span>$60,000 - $80,000</span></p>
                    <p><i class="fas fa-briefcase"></i><span>Full-time</span></p>
                    <p><i class="fas fa-clock"></i><span>Day Shift</span></p>
                </div>
                </div>
        </div>
        
        <div class="box">
            <div class="company">
                <img src="Photos/Finance.jpg" alt="">
                <div>
                    <h3>Finance</h3>
                    <span>1 week ago</span>
                </div>
                <h3 class="job-title">Financial Analyst</h3>
                <p class="location"><i class="fas fa-map-marker-alt"></i>
                    <span>Chicago, USA</span>
                </p>
                <div class="tags">
                    <p><i class="fas fa-dollar-sign"></i><span>$70,000 - $90,000</span></p>
                    <p><i class="fas fa-briefcase"></i><span>Full-time</span></p>
                    <p><i class="fas fa-clock"></i><span>Day Shift</span></p>
                </div>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="company">
                <img src="Photos/HC.jpg" alt="">
                <div>
                    <h3>Healthcare</h3>
                    <span>3 weeks ago</span>
                </div>
                <h3 class="job-title">Registered Nurse</h3>
                <p class="location"><i class="fas fa-map-marker-alt"></i>
                    <span>San Francisco, USA</span>
                </p>
                <div class="tags">
                    <p><i class="fas fa-dollar-sign"></i><span>$60,000 - $80,000 per year</span></p>
                    <p><i class="fas fa-briefcase"></i><span>Full-time</span></p>
                    <p><i class="fas fa-clock"></i><span>Rotating Shifts</span></p>
                </div>
                </div>
        </div>
        <div class="box">
            <div class="company">
                <img src="Photos/cuisine.jpg" alt="">
                <div>
                    <h3>Cuisine</h3>
                    <span>4 weeks ago</span>
                </div>
                <h3 class="job-title">Sous Chef</h3>
                <p class="location"><i class="fas fa-map-marker-alt"></i>
                    <span>Paris, France</span>
                </p>
                <div class="tags">
                    <p><i class="fas fa-dollar-sign"></i><span>€35,000 - €45,000 per year</span></p>
                    <p><i class="fas fa-briefcase"></i><span>Full-time</span></p>
                    <p><i class="fas fa-clock"></i><span>Evening Shifts</span></p>
                </div>
                </div>
        </div>
    </section>
    <div style="text-align: center;margin-top:1rem;">
        <a href="jobs.php" class="btn">view all</a>
    </div>
    <!-- Footer-->
    <footer class="footer">
        <section class="grid">
            <div class="boxX">
                <h3>Quick links</h3>
                <a href="home.php"><i class="fas fa-angle-right"></i> Home</a>
                <a href="about.php"><i class="fas fa-angle-right"></i> About</a>
                <a href="jobs.php"><i class="fas fa-angle-right"></i> All jobs</a>
                <a href="contact.php"><i class="fas fa-angle-right"></i> Contact us</a>
                <a href="#"><i class="fas fa-angle-right"></i> Filter search</a>
            </div>

            <div class="boxX">
                <h3>Extra links</h3>
                <a href="#"><i class="fas fa-angle-right"></i> Account</a>
                <a href="login.php"><i class="fas fa-angle-right"></i> Login</a>
                <a href="register.php"><i class="fas fa-angle-right"></i> Register</a>
                <a href="jobs.php"><i class="fas fa-angle-right"></i> Contact us</a>
                <a href="#"><i class="fas fa-angle-right"></i> Post jobs</a>
                <a href="#"><i class="fas fa-angle-right"></i> Dashboard</a>
            </div>

            <div class="boxX">
                <h3>Follow us</h3>
                <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
                <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
                <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
                <a href="#"><i class="fab fa-linkedin"></i> LinkedIn</a>
                <a href="#"><i class="fab fa-youtube"></i> YouTube</a>
            </div>
        </section>
        <div class="credit">&copy;copyright @2024 by <span>IAGI-1</span> | all rights reserved</div>
    </footer>
    <!-- End footer -->
</body>
</html>
