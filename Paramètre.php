<?php
session_start();
$host = 'localhost';
$dbname = 'reever';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "La connexion a échoué: " . $e->getMessage();
}

$errors = array(); // Variable pour stocker les erreurs

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Récupérer les informations de l'utilisateur connecté
    $sql = "SELECT photo FROM user WHERE id_user = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Paramètre</title>
</head>

<body>
    <header>
        <a href="Accueil.php" class="logo">REEVER</a>
        <nav>
            <a href="Profil.php">Profil</a>
            <a href="Paramètre.php">Paramètres</a>
            <?php if ($user && $user['photo']) { ?>
                <a href="Profil.php" class="profile-link">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($user['photo']); ?>" alt="Photo de profil" class="profile-photo">
                </a>
            <?php } else { ?>
                <a href="Profil.php" class="profile-link">
                    <img src="img/default-profile-photo.jpg" alt="Photo de profil" class="profile-photo">
                </a>
            <?php } ?>
        </nav>
    </header>

    <div class="contenu">
        <a href="supprimer_compte.php">Supprimer le compte</a>
        <a href="Connexion.php">Déconnexion</a>
    </div>

    <div class="mention">
        <a href="Mentions_legales.php">Mentions légales | copyright 2023 reever</a>
    </div>
</body>

</html>
