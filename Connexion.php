<?php
session_start();
$host = 'localhost';
$dbname = 'reever';
$username = 'root';
$password = '';

$errors = array(); // Variable pour stocker les erreurs

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $errors[] = "La connexion a échoué : " . $e->getMessage();
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    // Récupération de l'utilisateur correspondant à l'adresse e-mail
    $sql = "SELECT * FROM user WHERE email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification du mot de passe
    if ($user && password_verify($mdp, $user['mdp'])) {
        // Mot de passe correct, l'utilisateur est connecté
        $_SESSION['user_id'] = $user['id_user'];
        header("Location: Accueil.php");
        exit;
    } else {
        // Adresse e-mail ou mot de passe invalide
        $errors[] = "Adresse e-mail ou mot de passe invalide!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel='stylesheet' href='style.css'>
</head>
<body>
    <div class="gauche">
<h1>REEVER</h1>
        <p class="slogan">Le site de rencontre et de retrouvaille</p>
    </div>
    <div class="droite">
        <div class="box">
            <form method="post" action="">
                <h1 class="formulaire">CONNEXION</h1>
                <?php if (!empty($errors)) { ?>
                    <div class="errors">
                        <?php foreach ($errors as $error) {
                            echo $error . "<br>";
                        } ?>
                    </div>
                <?php } ?>
                <input type="text" id="email" name="email" placeholder="Adresse mail" required>
                <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required>
                <input type="submit" value="Connexion" name="login">
                <a href="Inscription.php">
                    <p class="compte"> Créer un compte !</p>
                </a>
            </form>
        </div>
    </div>
</body>
</html>