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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['user_type'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user_type = $_POST['user_type'];

        $stmt = $bdd->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username']; 
                $_SESSION['user_type'] = $user_type;

                if ($user_type == 'employer') {
                    header("Location: employer_home.php");
                } else {
                    header("Location: job_seeker.php");
                }
                exit();
            } else {
                $error_message = "Mot de passe incorrect";
            }
        } else {
            $error_message = "Adresse mail incorrecte";
        }
    } else {
        $error_message = "Veuillez fournir une adresse mail, un mot de passe et sélectionner un type d'utilisateur";
    }

    // Redirect back to login form with error message
    $_SESSION['error_message'] = $error_message;
    header("Location: login.php");
    exit();
} else {
    $error_message = "Invalid request method";
    $_SESSION['error_message'] = $error_message;
    header("Location: login.php");
    exit();
}
?>
