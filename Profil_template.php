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

// Récupérer les informations de l'utilisateur connecté
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Effectuer une requête pour récupérer les informations de l'utilisateur connecté
    $sql = "SELECT nom, prenom, description, photo, instagram FROM user WHERE id_user = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    $userConnecte = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userConnecte) {
        $photoUtilisateurConnecte = $userConnecte['photo'];
    }
}

// Récupérer les informations de la personne sur laquelle vous avez cliqué
if (isset($_GET['nom'])) {
    $nomParticipant = $_GET['nom'];

    // Effectuer une requête pour récupérer les informations de la personne sur laquelle vous avez cliqué
    $sql = "SELECT nom, prenom, description, photo, instagram FROM user WHERE nom = :nomParticipant";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nomParticipant', $nomParticipant);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $nomUtilisateur = $user['nom'];
        $prenomUtilisateur = $user['prenom'];
        $descriptionUtilisateur = $user['description'];
        $photoUtilisateur = $user['photo'];
        $instaUtilisateur = $user['instagram'];
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
            <a href="Profil.php">Profil</a>
            <a href="Paramètre.php">Paramètres</a>
        </nav>
        <?php if ($_SESSION['user_id'] && $photoUtilisateurConnecte) { ?>
            <a href="Profil.php" class="profile-link">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($photoUtilisateurConnecte); ?>" alt="Photo de profil" class="profile-photo">
            </a>
        <?php } else { ?>
            <a href="Profil.php" class="profile-link">
                <img src="img/default.png" alt="Photo de profil" class="profile-photo">
            </a>
        <?php } ?>
    </header>

    <div class="container2">
        <div class="centered-element">
            <?php if ($user && $photoUtilisateur) { ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($photoUtilisateur); ?>" alt="image profil">
            <?php } else { ?>
                <img src="img\default.png" alt="image profil">
            <?php } ?>
            <div class="name">
                <p><?php echo ($user['prenom'] . ' ' . $user['nom']); ?></p>
            </div>
        </div>
        <div class="info">
            <p class="description"><?php echo ($user['description']); ?></p>
            <p class="instagram"><a href="https://www.instagram.com/<?php echo ($user['instagram']); ?>" target="_blank">@<?php echo ($user['instagram']); ?></a></p>
            <div class="detail">
                <a href="javascript:history.back()">Retour</a>
            </div>
        </div>
    </div>
</body>

</html>
