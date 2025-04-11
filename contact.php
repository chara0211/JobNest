<?php
include 'session_helper.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    .btnn{
    display: inline-block;
    margin-top: 1rem;
    padding: 1rem 3rem;
    cursor: pointer;
    font-size: 1.8rem;
    color:white;
    border-radius: 2rem;
    background-color: rgb(0, 0, 0);
    text-align: center;
    text-transform: capitalize;
}
.btnn:hover{
    background-color: whitesmoke;
    color: black;
}
</style>
</head>
<header class="header">
    <section class="flex">
        <div id="menu-btn" class="fas fa-bars"></div>
        <a href="<?php echo $homepageURL; ?>" class="logo"><i class="fas fa-briefcase"></i> JobNest</a>
        <nav class="navbar">
            <a href="home.php">Home</a>
            <a id="jobCatButton"href="jobcategories.php">Job categories</a>
            <a id="jobButton"href="latestjobs.php">Latest jobs</a>
            <a id="aboutButton"href="about.php">About</a>
            <a id="contactButton"href="contact.php">Contact Us</a>
            <?php if(isEmployer()):?>
                    <a href="post1.php">Post a job</a>
            <?php endif; ?>
        </nav>
        <?php if(isLoggedIn()) : ?>
            <a href="logout.php" class="btnn" style="margin-top: 0%;">Logout</a>
        <?php else : ?>
            <a href="login.php" class="btnn" style="margin-top: 0%;">Login</a>
        <?php endif; ?>
    </section>
</header>

            <section id="contact" class="about">
            <div class="container">  
            <h2>Contact Us</h2>
            <p>Have questions? Reach out to us at <a href="mailto:info@jobnest.com">info@jobnest.com</a> or follow us on our social media channels.</p>
        </div></section>
        
    
<!-- footer-->
<footer class="footer">
    <section class="grid">
        <div class="boxX">
            <h3>Quick links</h3>
            <a href="home.php"><i class="fas fa-angle-right"></i>home</a>
            <a href="jobs.php"><i class="fas fa-angle-right"></i>All jobs</a>
            <a href="about.php"><i class="fas fa-angle-right"></i>About</a>
            <a href="about.php"><i class="fas fa-angle-right"></i>contact us</a>
        </div>

        <div class="boxX">
            <h3>Extra links</h3>
            <a href="login.php"><i class="fas fa-angle-right"></i>login</a>
            <a href="register.html"><i class="fas fa-angle-right"></i>register</a>
            <a href="about.php"><i class="fas fa-angle-right"></i>contact us</a>
            <a href="post1.php"><i class="fas fa-angle-right"></i>Post jobs</a>



        </div>
        <div class="boxX">
            <h3>Follow us</h3>
            <a href="#" ><i class="fab fa-facebook-f"></i> Facebook</a>
            <a href="#" ><i class="fab fa-twitter"></i> Twitter</a>
            <a href="#" ><i class="fab fa-instagram"></i>instagram</a>
            <a href="#" ><i class="fab fa-linkedin"></i> linkedin</a>
        </div>
    </section>
    <div class="credit">&copy;copyright @2024 by <span>IAGI-1</span> | all right deserved</div>
</footer>
   <!-- js  -->
   <script src="javascript.js"></script>
</body>
</html>