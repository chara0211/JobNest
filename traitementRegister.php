<?php 
$hostname = "localhost";
$username = "root";
$password = "";

try {
    $bdd = new PDO("mysql:host=$hostname;dbname=login", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage(); 
}

if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $gender = $_POST['gender'];
    $birth_date = $_POST['birth_date'];
    $experience_level = $_POST['experience_level'];
    $current_position = $_POST['current_position'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $region = $_POST['region'];
    $postal_code = $_POST['postal_code'];
    $linkedin_url = $_POST['linkedin_url'];

    // Gestion de l'upload du fichier CV
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $cv = $_FILES['cv']['name'];
        $cv_temp = $_FILES['cv']['tmp_name'];
        $cv_folder = "uploads/" . basename($cv);

        if (move_uploaded_file($cv_temp, $cv_folder)) {
            $cv_path = $cv_folder;
        } else {
            echo "Erreur lors du téléchargement du CV.";
            exit;
        }
    } else {
        echo "Veuillez télécharger votre CV.";
        exit;
    }

    try {
        $sql = "INSERT INTO user (username, password, email, phone_number, gender, birth_date, experience_level, current_position, country, city, region, postal_code, cv, linkedin_url)
                VALUES (:username, :password, :email, :phone_number, :gender, :birth_date, :experience_level, :current_position, :country, :city, :region, :postal_code, :cv, :linkedin_url)";

        $stmt = $bdd->prepare($sql);
        $stmt->execute(array(
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'phone_number' => $phone_number,
            'gender' => $gender,
            'birth_date' => $birth_date,
            'experience_level' => $experience_level,
            'current_position' => $current_position,
            'country' => $country,
            'city' => $city,
            'region' => $region,
            'postal_code' => $postal_code,
            'cv' => $cv_path,
            'linkedin_url' => $linkedin_url
        ));
        echo "Votre inscription a été effectuée avec succès. Vous pouvez maintenant vous connecter!";
        // Redirect the user to the login page after a short delay
        header("refresh:3;url=login.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de la création de l'utilisateur: " . $e->getMessage();
    }

} else {
    echo "Aucune donnée postée.";
}
?>
