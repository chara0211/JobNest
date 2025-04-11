<?php
include 'session_helper.php';

// Database connection
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "login";

try {
    $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Check if the user is logged in and is an employer
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'employer') {
    header("Location: login.php");
    exit();
}

// Fetch applications for jobs posted by the logged-in employer
$stmt_applications = $conn->prepare("
    SELECT applications.*, user.username AS job_seeker_username, user.email AS job_seeker_email, user.phone_number AS job_seeker_phone, user.cv AS job_seeker_cv, jobs.title AS job_name 
    FROM applications 
    INNER JOIN user ON applications.user_id = user.id 
    INNER JOIN jobs ON applications.job_id = jobs.id 
    WHERE applications.employer_id = :employer_id
");
$stmt_applications->bindParam(':employer_id', $_SESSION['user_id']);
$stmt_applications->execute();
$applications = $stmt_applications->fetchAll(PDO::FETCH_ASSOC);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['application_id'], $_POST['status'])) {
        $applicationId = $_POST['application_id'];
        $newStatus = $_POST['status'];

        // Update status
        $stmt_update = $conn->prepare("UPDATE applications SET status = :status WHERE id = :application_id");
        $stmt_update->bindParam(':status', $newStatus);
        $stmt_update->bindParam(':application_id', $applicationId);
        $stmt_update->execute();

        // If accepted, update interview date
        if ($newStatus === 'Accepted' && isset($_POST['interview_datetime'])) {
            $interviewDatetime = $_POST['interview_datetime'];
            $stmt_update = $conn->prepare("UPDATE applications SET interview_date = :interview_date WHERE id = :application_id");
            $stmt_update->bindParam(':interview_date', $interviewDatetime);
            $stmt_update->bindParam(':application_id', $applicationId);
            $stmt_update->execute();
        }

        // Reload to reflect changes
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications for you</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* View application */
table {
    width: 100%; /* Set width to 70% of the container */
    margin: 20px auto; /* Center the table horizontally */
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px; /* Decreased padding */
    text-align: left;
    font-size: 14px; /* Decreased font size */
}

th {
    background-color: #f4f4f4;
}

tr:nth-child(even) {
    background-color: #e3ffe8;
}

.cv-link {
    color: #007bff;
    text-decoration: underline;
}

.cv-link:hover {
    color: #0056b3;
}

/* CSS for buttons */
button[type="submit"] {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 5px;
}

/* CSS for specific status colors */
select[name="status"] {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 5px;
    font-size: 14px; /* Increased font size */
}

option[value="Pending"] {
    background-color: #FFD700; /* Yellow */
}

option[value="Accepted"] {
    background-color: #4CAF50; /* Green */
    color: white; /* White text for better visibility */
}

option[value="Refused"] {
    background-color: #FF6347; /* Red */
    color: white; /* White text for better visibility */
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
            <a href="favorites.php">Favorites</a>
            <a href="post1.php">Post a job</a>
            <a href="notifications.php">Notifications <i class="fas fa-bell"></i> <span class="badge" id="notificationCount">0</span></a>
        </nav>
        <?php if($isLoggedIn) : ?>
            <a href="logout.php" class="btn" style="margin-top: 0%;">Logout</a>
        <?php else : ?>
            <a href="login.php" class="btn" style="margin-top: 0%;">Login</a>
        <?php endif; ?>
    </section>
</header>
    <main>
        <h1 class="heading">Applications for you </h1>
        <div class="container">
            <table>
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>Job Title</th>
                        <th>Job Seeker Username</th>
                        <th>Job Seeker Email</th>
                        <th>Job Seeker Phone</th>
                        <th>CV</th>
                        <th>Motivation Letter</th>
                        <th>Status</th>
                        <th>Interview Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr>
                            <td><?php echo $application['id']; ?></td>
                            <td><?php echo $application['job_name']; ?></td>
                            <td><?php echo $application['job_seeker_username']; ?></td>
                            <td><?php echo $application['job_seeker_email']; ?></td>
                            <td><?php echo $application['job_seeker_phone']; ?></td>
                            <td><a href="<?php echo $application['job_seeker_cv']; ?>" target="_blank" class="cv-link">View CV</a></td>
                            <td><a href="motivation_letter.php?id=<?php echo $application['id']; ?>" target="_blank" class="motivation-letter-link">View Motivation Letter</a></td>
                            <td><?php echo $application['status']; ?></td>
                            <td><?php echo $application['interview_date']; ?></td>
                            <td>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                    <input type="hidden" name="application_id" value="<?php echo $application['id']; ?>">
                                    <select name="status" onchange="showDateInput(this, <?php echo $application['id']; ?>)">
                                        <option value="Pending" <?php if ($application['status'] === 'Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Accepted" <?php if ($application['status'] === 'Accepted') echo 'selected'; ?>>Accept</option>
                                        <option value="Refused" <?php if ($application['status'] === 'Refused') echo 'selected'; ?>>Refuse</option>
                                    </select>
                                    <div id="interview_date_<?php echo $application['id']; ?>" style="display: <?php echo ($application['status'] === 'Accepted') ? 'block' : 'none'; ?>;">
                                        <input type="datetime-local" name="interview_datetime" value="<?php echo ($application['status'] === 'Accepted') ? $application['interview_date'] : ''; ?>">
                                    </div>
                                    <button type="submit">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
        function showDateInput(selectElement, id) {
            var interviewDateDiv = document.getElementById('interview_date_' + id);
            if (selectElement.value === 'Accepted') {
                interviewDateDiv.style.display = 'block';
            } else {
                interviewDateDiv.style.display = 'none';
            }
        }
    </script>

    <!-- Footer -->
   
    <!-- footer -->
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
</body>
</html>
