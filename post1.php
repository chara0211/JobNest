<?php
include 'session_helper.php';

// Establish database connection
try {
    $conn = new PDO('mysql:host=localhost;dbname=login;charset=utf8', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Display error message if connection fails
    echo "Connection failed: " . $e->getMessage();
    exit();
}

if (isset($_POST['PostJob'])) { // Check for the PostJob submit button
    // Retrieve form data
    $title = $_POST['title'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $work_type = $_POST['workType'];
    $working_hours = $_POST['workingHours'];
    $category = $_POST['category'];
    $contract_type = $_POST['contract_type']; // New field for contract type
    $company_name = $_POST['company_name']; // New field for company name

    // Ensure that the company image file is uploaded securely
    if (isset($_FILES['companyImage'])) {
        $company_image = $_FILES['companyImage']['name'];
        $company_image_tmp_name = $_FILES['companyImage']['tmp_name'];
        $company_image_folder = 'Photos/' . $company_image;
        
        // Move uploaded company image file to the specified folder
        move_uploaded_file($company_image_tmp_name, $company_image_folder);
    } else {
        // Set a default image if no image is uploaded
        $company_image = 'default_image.png';
    }

    // Get the employer_id (which is the same as user_id) from the session
    $employer_id = $_SESSION['user_id'];

    try {
        // Prepare and execute the SQL statement
        $sql = "INSERT INTO jobs (title, location, salary, work_type, working_hours, company_image, category, contract_type, company_name, employer_id) 
                VALUES (:title, :location, :salary, :work_type, :working_hours, :company_image, :category, :contract_type, :company_name, :employer_id)";
        $stmt = $conn->prepare($sql);
        // Bind parameters
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':salary', $salary);
        $stmt->bindParam(':work_type', $work_type);
        $stmt->bindParam(':working_hours', $working_hours);
        $stmt->bindParam(':company_image', $company_image);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':contract_type', $contract_type); // Bind contract type parameter
        $stmt->bindParam(':company_name', $company_name); // Bind company name parameter
        $stmt->bindParam(':employer_id', $employer_id); // Bind employer_id parameter
        
        $stmt->execute();

        // Insert notifications for all users except the employer
        $message = "New job posted: $title at $location";
        $created_at = date('Y-m-d H:i:s');
        
        // Fetch all user IDs except the current employer
        $user_stmt = $conn->prepare("SELECT id FROM user WHERE id != :employer_id");
        $user_stmt->bindParam(':employer_id', $employer_id);
        $user_stmt->execute();
        $user_result = $user_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Insert a notification for each user
        $notification_stmt = $conn->prepare("INSERT INTO notifications (user_id, message, created_at, is_read) VALUES (:user_id, :message, :created_at, 0)");
        foreach ($user_result as $user_row) {
            $user_id = $user_row['id'];
            $notification_stmt->bindParam(':user_id', $user_id);
            $notification_stmt->bindParam(':message', $message);
            $notification_stmt->bindParam(':created_at', $created_at);
            $notification_stmt->execute();
        }

        echo "Data inserted successfully!";
        header("refresh:3;url=employer_home.php"); // Corrected the URL redirection
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="post.css"> <!-- Link to your CSS file -->
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
            <?php if(isLoggedIn()):?>
            <a href="favorites.php">Favorites</a>
            <?php endif; ?>
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
<h1 class="heading">Post a job</h1>
<form action="post1.php" method="post" id="postJobForm" enctype="multipart/form-data">
    <label for="title">Job Title:</label>
    <input type="text" id="title" name="title" required><br>

    <label for="companyName">Company Name:</label>
    <input type="text" id="companyName" name="company_name" required><br>

    <label for="location">Location:</label>
    <input type="text" id="location" name="location" required><br>

    <label for="salary">Salary:</label>
    <input type="text" id="salary" name="salary" required><br>

    <label for="workType">Type of Work:</label>
    <select id="workType" name="workType">
        <option value="full-time">Full-time</option>
        <option value="part-time">Part-time</option>
        <option value="contract">Contract</option>
    </select><br>

    <label for="workingHours">Working Hours:</label>
    <select id="workingHours" name="workingHours">
        <option value="day-shift">Day Shift</option>
        <option value="night-shift">Night Shift</option>
    </select><br>

    <label for="category">Category:</label>
    <select id="category" name="category">
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
    </select><br>

    <label for="contractType">Contract Type:</label>
    <select id="contractType" name="contract_type">
        <option value="stage">Stage (Internship) Contract</option>
        <option value="cdd">CDD (Fixed-Term Contract)</option>
        <option value="cdi">CDI (Permanent Contract)</option>
        <option value="freelance">Freelance Contract</option>
        <option value="temporary">Temporary Agency Contract</option>
    </select><br>

    <label for="companyImage">Company Image:</label>
    <input type="file" id="companyImage" name="companyImage" accept="image/*"><br>

    <input type="submit" value="Post Job" name="PostJob">
</form>

<!-- footer-->
<footer class="footer">
    <section class="grid">
        <div class="boxX">
            <h3>Quick links</h3>
            <a href="home.php"><i class="fas fa-angle-right"></i>home</a>
            <a href="about.php"><i class="fas fa-angle-right"></i>about</a>
            <a href="jobs.php"><i class="fas fa-angle-right"></i>all jobs</a>
            <a href="jobs.php"><i class="fas fa-angle-right"></i>contact us</a>
            <a href="#"><i class="fas fa-angle-right"></i>Filter search</a>
        </div>

        <div class="boxX">
            <h3>Extra links</h3>
            <a href="#"><i class="fas fa-angle-right"></i>account</a>
            <a href="login.php"><i class="fas fa-angle-right"></i>login</a>
            <a href="register.php"><i class="fas fa-angle-right"></i>register</a>
            <a href="jobs.php"><i class="fas fa-angle-right"></i>contact us</a>
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
    <div class="credit">&copy;copyright @2024 by<span>IAGI-1</span> | all right reserved</div>
</footer>

<!-- js  -->
<script src="javascript.js"></script>

</body>
</html>
