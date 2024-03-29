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

    if (isset($_POST['supprimer'])) {
        // Effectuer une requête pour supprimer l'utilisateur de la base de données
        $sql = "DELETE FROM user WHERE id_user = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        // Déconnecter l'utilisateur
        session_unset();
        session_destroy();

        // Rediriger vers la page de connexion
        header("Location: Connexion.php");
        exit();
    }

    // Effectuez une requête pour récupérer les informations de l'utilisateur, y compris la photo
    $sql = "SELECT nom, prenom, photo FROM user WHERE id_user = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $nomUtilisateur = $user['nom'];
        $prenomUtilisateur = $user['prenom'];
        $photoUtilisateur = $user['photo'];
    }
} else {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header("Location: Connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="img/png" href="img/favicon.ico"/>
    <title>Supprimer le compte</title>
</head>

<body>
    <header>
        <a href="Accueil.php" class="logo">REEVER</a>
        <nav>
            <a href="Profil.php">Profil</a>
            <a href="Paramètre.php">Paramètres</a>
        </nav>
        <?php if ($user && $user['photo']) { ?>
                <a href="Profil.php" class="profile-link">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($user['photo']); ?>" alt="Photo de profil" class="profile-photo">
                </a>
            <?php } else { ?>
                <a href="Profil.php" class="profile-link">
                    <img src="img/default.png" alt="Photo de profil" class="profile-photo">
                </a>
            <?php } ?>
    </header>

    <div class="container">
        <h2 class="page-title">Supprimer le compte</h2>
        <p>Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.</p>
        <p class="user-info">Nom : <?php echo $nomUtilisateur; ?></p>
        <p class="user-info">Prénom : <?php echo $prenomUtilisateur; ?></p>
        <?php if ($photoUtilisateur) { ?>
        <?php } ?>
        <form action="" method="POST">
            <button type="submit" name="supprimer" class="delete-btn">Supprimer le compte</button>
        </form>
    </div>

    <div class="mention">
        <a href="Mentions_legales.php">Mentions légales | Copyright 2023
            reever</a>
    </div>
</body>

</html>
