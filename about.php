<?php
include 'session_helper.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
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
            
        <?php if(isLoggedIn()) : ?>
            <a href="logout.php" class="btn" style="margin-top: 0%;">Logout</a>
        <?php else : ?>
            <a href="login.php" class="btn" style="margin-top: 0%;">Login</a>
        <?php endif; ?>
    </section>
</header>

    <!-- fin header -->
    <!-- main body-->
    <section class="about">
        <div class="container">
            <h1 class="heading">About Us</h1>
            <p>Welcome to JobNest, your trusted platform for finding your next career opportunity. Our mission is to connect job seekers with their dream jobs while helping employers find the best talent.</p>
            
            <h2 class="heading">Our Mission and Vision</h2>
            <p>Our mission is to bridge the gap between job seekers and employers, ensuring a smooth and efficient hiring process. We envision a world where everyone can find their ideal job effortlessly.</p>
            
            <h2>Meet the Team</h2>
            <div class="team">
                <div class="team-member">
                    <img src="Photos/yassmine.jpg" alt="Yassmine Attar">
                    <h3>Yassmine Attar</h3>
                    <p>CEO & Founder</p>
                </div>
                <div class="team-member">
                    <img src="Photos/waffaa.jpg" alt="wafaa hadchi">
                    <h3>Wafaa Hadchi</h3>
                    <p>CEO & Founder</p>
                </div>
                <div class="team-member">
                    <img src="C:\Users\AMEZIANE\Documents\doc perso\photo jihane.jpeg" alt="Jihane Amez">
                    <h3>Jihane Ameziane</h3>
                    <p>CEO & Founder</p>
                </div>
                
            </div>
            
            <h2 class="heading">What We Offer</h2>
            <p>For Job Seekers: We provide a comprehensive job search engine, career advice, and tools to create standout resumes. For Employers: We offer job posting services, access to a large database of CVs, and recruitment services.</p>
                        
            <h2>Contact Us</h2>
            <p>Have questions? Reach out to us at <a href="mailto:info@jobnest.com">info@jobnest.com</a> or follow us on our social media channels.</p>
        </div>
    </section>
    <!-- fin main body -->

    <!-- footer-->
    <footer class="footer">
        <section class="grid">
            <div class="boxX">
                <h3>Quick links</h3>
                <a href="home.html"><i class="fas fa-angle-right"></i>home</a>
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
        <div class="credit">&copy;copyright @2024 by<span>IAGI-1</span> | all rights reserved</div>
    </footer>

    <!-- js  -->
    <script src="javascript.js"></script>
</body>
</html>
