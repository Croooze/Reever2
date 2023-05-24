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

if (isset($_POST['envoyer'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    // Vérification de la correspondance du mot de passe
    $mdpConfirme = $_POST['mdp_confirme'];
    if ($mdp !== $mdpConfirme) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Vérification si l'utilisateur existe déjà
    $sql = "SELECT * FROM user WHERE email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $errors[] = "L'utilisateur avec cette adresse email existe déjà.";
    }

    if (empty($errors)) {
        // Hashage du mot de passe
        $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);

        // Insertion des données dans la base de données
        $sql = "INSERT INTO user (nom, prenom, email, mdp) VALUES (:nom, :prenom, :email, :mdp)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mdp', $hashedPassword);
        $stmt->execute();

        // Redirection après l'insertion
        header("Location: Accueil.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel='stylesheet' href='style.css'>
</head>

<body>
    <div class="gauche">
        <h1>REEVER</h1>
        <p>Le site de rencontre et de retrouvaille</p>
    </div>
    <div class="droite">
        <div class="box">
            <form method="post" action="">
                <h1 class="formulaire">INSCRIPTION</h1>
                <?php if (!empty($errors)) { ?>
                    <div class="errors">
                        <?php foreach ($errors as $error) {
                            echo $error . "<br>";
                        } ?>
                    </div>
                <?php } ?>
                <input type="text" id="nom" name="nom" placeholder="Nom" required>
                <input type="text" id="prenom" name="prenom" placeholder="Prénom" required>
                <input type="text" id="email" name="email" placeholder="Adresse mail" required>
                <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required>
                <input type="password" id="mdp_confirme" name="mdp_confirme" placeholder="Confirmer le mot de passe" required>
                <input type="submit" value="Inscription" name="envoyer">
                <a href="Connexion.php">
                    <p class="compte">Déjà inscrit ? Se connecter</p>
                </a>
            </form>
        </div>
    </div>
</body>
</html>