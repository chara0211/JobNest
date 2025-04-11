<?php
include 'session_helper.php';
// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'job_seeker') {
    header("Location: login.php");
    exit();
}

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

// Delete application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_application_id'])) {
    $applicationId = $_POST['delete_application_id'];
    
    // Perform deletion only if the application is pending
    $stmtCheck = $conn->prepare("SELECT status FROM applications WHERE id = :id AND user_id = :user_id");
    $stmtCheck->bindParam(':id', $applicationId);
    $stmtCheck->bindParam(':user_id', $_SESSION['user_id']);
    $stmtCheck->execute();
    $application = $stmtCheck->fetch(PDO::FETCH_ASSOC);
    
    if ($application && strtolower($application['status']) === 'pending') {
        $stmtDelete = $conn->prepare("DELETE FROM applications WHERE id = :id");
        $stmtDelete->bindParam(':id', $applicationId);
        $stmtDelete->execute();
    }
}

// Fetch applications from the database
$stmt = $conn->prepare("
    SELECT applications.*, jobs.title AS job_title, jobs.location, jobs.salary, jobs.work_type, jobs.company_name AS company_name 
    FROM applications 
    INNER JOIN jobs ON applications.job_id = jobs.id 
    WHERE applications.user_id = :user_id
");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Applications</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your CSS file here -->
    <style>
        /* Add custom styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: auto;
            background: #fff;
            padding-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
            text-transform: uppercase;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .status-accepted {
            background-color: #d4edda;
            
        }

        .status-pending {
            background-color: #fff3cd;
            
        }
        .status-refused {
            background-color: #f8d7da;
            
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
    <script>
        function confirmDelete(applicationId) {
            if (confirm("Are you sure you want to delete this application?")) {
                document.getElementById('delete_form_' + applicationId).submit();
            }
        }
    </script>
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
    <div class="container">
        <h1 class="heading">Manage Applications</h1>
        <table>
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Company Name</th>
                    <th>Motivation Letter</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Interview Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $application): ?>
                    <tr>
                        <td><?php echo $application['job_title']; ?></td>
                        <td><?php echo $application['company_name']; ?></td>
                        <td><a href="motivation_letter.php?id=<?php echo htmlspecialchars($application['id'] ?? ''); ?>" target="_blank" class="motivation-letter-link">View Letter</a></td>
                        <td class="status-<?php echo strtolower($application['status'] ?? ''); ?>">
                            <?php echo $application['status']; ?>
                        </td>
                        <td><?php echo $application['created_at']; ?></td>
                        <td><?php echo $application['interview_date']; ?></td>
                        <td>
                            <?php if (strtolower($application['status']) === 'pending'): ?>
                                <form id="delete_form_<?php echo $application['id']; ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                    <input type="hidden" name="delete_application_id" value="<?php echo $application['id']; ?>">
                                    <button type="button" class="delete-btn" onclick="confirmDelete(<?php echo $application['id']; ?>)">Delete</button>
                                </form>
                            <?php else: ?>
                                <span>Cannot delete</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
