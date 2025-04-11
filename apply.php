<?php
include 'session_helper.php';

try {
    // Establish database connection
    $conn = new PDO('mysql:host=localhost;dbname=login;charset=utf8', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '<div class="message error">Connection failed: ' . htmlspecialchars($e->getMessage()) . '</div>';
    exit; // Exit if connection fails
}

// Initialize job variable
$job = null;

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Fetch the job to ensure it exists
    try {
        $stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
        $stmt->execute([$job_id]);
        $job = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '<div class="message error">Error fetching job: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply'])) {
    $job_id = $_POST['job_id'];
    $user_id = $_POST['user_id'];
    $motivation_letter = $_POST['motivation_letter'];

    // Fetch the job to ensure it exists
    try {
        $stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
        $stmt->execute([$job_id]);
        $job = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '<div class="message error">Error fetching job: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }

    if ($job) {
        // Insert application into the database
        try {
            $stmt = $conn->prepare("INSERT INTO applications (job_id, user_id, motivation_letter, employer_id) VALUES (:job_id, :user_id, :motivation_letter, :employer_id)");
            $stmt->bindParam(':job_id', $job_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':motivation_letter', $motivation_letter);
            $stmt->bindParam(':employer_id', $job['employer_id']);
            $stmt->execute();

            // Insert notification for the employer
            $notification_message = "New application received for the job: " . htmlspecialchars($job['title']);
            $stmt = $conn->prepare("INSERT INTO notifications (user_id, message, is_read) VALUES (:user_id, :message, 0)");
            $stmt->bindParam(':user_id', $job['employer_id']); // Send notification only to the employer
            $stmt->bindParam(':message', $notification_message);
            $stmt->execute();

        } catch (PDOException $e) {
            echo '<div class="message error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    } else {
        echo '<div class="message error">No job found with the provided ID.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="apply.css">
    <style>
        .introduction {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .introduction h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
        }
        .introduction p {
            font-size: 10px;
            color: #555;
            line-height: 1.5;
        }
        .message {
            margin: 20px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .message.success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .message.error {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
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
            <?php if(isLoggedIn()): ?>
                <a href="favorites.php">Favorites</a>
            <?php endif; ?>
            <?php if(isEmployer()): ?>
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

<div class="container">
    <?php if ($job): ?>
        <h1 class="heading">Apply for <?php echo htmlspecialchars($job['title']); ?></h1>
        <script>
        function checkLoginAndApply() {
            // Check if the user is logged in
            <?php if (!isset($_SESSION['user_id'])): ?>
                // If not logged in, show alert and redirect to login page
                alert("You need to log in before applying for a job.");
                window.location.href = "login.php";
            <?php endif; ?>
        }
    </script>
        <!-- Application success message and introduction -->
        <div class="application-info">
            <!-- Application success message -->
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply'])): ?>
                <div class="message success">
                    <p>Your application was sent to the company employer. Good luck with your application!</p>
                    <p>Stay tuned and check for the response of the employer. If accepted, you'll proceed to round 2 which is the interview.</p>
                </div>
            <?php endif; ?>

            <!-- Introduction section -->
            <section class="introduction">
                <h2>Application Procedure</h2>
                <p>After applying for a job, your application will be sent to the company employer. In round 1, the employer will review your motivation letter and CV along with other information provided.</p>
                <p>The employer will then decide whether to accept or refuse your application to proceed to round 2.</p>
            </section>
        </div>

        <!-- Job details -->
        <div class="job-details">
            <p><strong>Category:</strong> <i class="fas fa-tag"></i> <?php echo htmlspecialchars($job['category']); ?></p>
            <p><strong>Company:</strong> <i class="fas fa-building"></i> <?php echo htmlspecialchars($job['company_name']); ?></p>
            <p><strong>Location:</strong> <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?></p>
            <p><strong>Salary:</strong> <i class="fas fa-dollar-sign"></i> <?php echo htmlspecialchars($job['salary']); ?></p>
            <p><strong>Work Type:</strong> <i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($job['work_type']); ?></p>
            <p><strong>Contract Type:</strong> <i class="fas fa-file-contract"></i> <?php echo htmlspecialchars($job['contract_type']); ?></p>
            <p><strong>Working Hours:</strong> <i class="far fa-clock"></i> <?php echo htmlspecialchars($job['working_hours']); ?></p>
        </div>

        <!-- Application form -->
        <form action="apply.php" method="POST">
            <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_id); ?>">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
            <div>
                <label for="motivation_letter">Motivation Letter</label>
                <textarea name="motivation_letter" id="motivation_letter" required></textarea>
            </div>
            <button type="submit" name="apply" onclick="checkLoginAndApply()">Apply</button>
        </form>
    <?php else: ?>
        <p>No job found with the provided ID.</p>
    <?php endif; ?>
</div>

<!-- Footer -->
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
            <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
            <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
            <a href="#"><i class="fab fa-instagram"></i>instagram</a>
            <a href="#"><i class="fab fa-linkedin"></i> linkedin</a>
            <a href="#"><i class="fab fa-youtube"></i> youtube</a>
        </div>
    </section>
    <div class="credit">&copy;copyright @2024 by<span>IAGI-1</span> | all rights reserved</div>
</footer>

<!-- JavaScript -->
<script src="javascript.js"></script>
</body>
</html>
