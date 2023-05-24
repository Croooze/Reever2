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

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Effectuez une requête pour récupérer les informations de l'utilisateur
    $sql = "SELECT nom, prenom FROM user WHERE id_user = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $nomUtilisateur = $user['nom'];
        $prenomUtilisateur = $user['prenom'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Profil</title>
</head>

<body>
    <header>
        <a href="Accueil.php" class="logo">REEVER</a>
        <nav>
            <a href="Notification.php">Notification</a>
            <a href="Profil.php">Profil</a>
            <a href="Paramètre.php">Paramètres</a>
        </nav>
    </header>
    <div class="container2">
        <div class="centered-element">
            <img src="img/3.png" alt="image profil">
        </div>
        <div class="info">
            <?php if ($user) { ?>
                <p><?php echo ($user['nom']); ?></p>
                <p><?php echo ($user['prenom']); ?></p>
            <?php } ?>
            <p>Je suis jeune et pleine d'énergie</p>
        </div>
        <div class="detail">
            <a class="test" href="">Aperçu profil</a>
            <a href="">Modifier</a>
        </div>
    </div>
</body>

</html>