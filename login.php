<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .error-message {
            color: #D8000C;
            background-color: #FFBABA;
            padding: 10px;
            border: 1px solid #D8000C;
            border-radius: 5px;
            margin-bottom: 10px;
            text-align: center;
        }
        .background {
            background: url('2895265.jpg'),rgba(0, 0, 0, 0.4) no-repeat;
            height: 100%;
            background-position: center;
            background-size: cover;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .heading {
            text-align: center;
        }
    </style>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <!--header-->
    <header class="header">
        <section class="flex">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="home.php" class="logo"><i class="fas fa-briefcase"></i> JobNest</a>
            <nav class="navbar">
                <a href="home.php">Home</a>
                <a href="about.php">About</a>
                <a href="jobs.php">All jobs</a>
                <a href="">                     </a>
                <a href="">                     </a>
                <a href="">                     </a>         
            </nav>
            <a href="post1.html"  style="margin-top: 0%;"></a>           </section>
    </header>
    <!-- fin header-->

    <!-- main body -->
    
    <section class="login">
        <div class="container">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="error-message"><?php echo $_SESSION['error_message']; ?></div>
            <?php endif; ?>
            <form action="traitementlogin.php" method="post" enctype="multipart/form-data">
                <h1 class="heading">Login</h1>
                <p>I am a:</p>
                <select name="user_type" id="user_type" class="input">
                    <option value="employer">Employer</option>
                    <option value="job_seeker">Job Seeker</option>
                </select>

                <p>Email</p>
                <input type="text" name="email" placeholder="Enter your email" required maxlength="50" class="input">
                
                <p>Password</p>
                <input type="password" name="password" placeholder="Enter your password" required maxlength="50" class="input">
            
                <input type="submit" value="Login" class="btn">
                <p class="register-link">Don't have an account? <a href="register.html">Register here</a></p>
            </form>
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
                <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
                <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
                <a href="#"><i class="fab fa-instagram"></i>instagram</a>
                <a href="#"><i class="fab fa-linkedin"></i> linkedin</a>
                <a href="#"><i class="fab fa-youtube"></i> youtube</a>
            </div>
        </section>
        <div class="credit">&copy;copyright @2024 by <span>IAGI-1</span> | all rights reserved</div>
    </footer>

</body>
</html>
