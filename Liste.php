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

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: Connexion.php");
    exit;
}

// Récupérer les informations de l'utilisateur connecté
$sql = "SELECT * FROM user WHERE id_user = :userId";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':userId', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reever - Liste des participants</title>
    <link rel="stylesheet" href="style.css">
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

    


    <?php 
    // Vérifier si le nom de l'événement est présent dans les paramètres de l'URL
    if (isset($_GET['nom'])) {
        $nomEvenement = $_GET['nom'];
        echo '<h1 style="color: #fff;">Liste des participants à l\'événement : ' . $nomEvenement . '</h1>';

    } else {
        echo '<p>Événement : [Nom de l\'événement non spécifié]</p>';
    }
    ?>
    <div class="lemon">
    <ul>
    <?php
    // Récupérer la liste des participants distincts
    $sql = "SELECT DISTINCT u.id_user, u.nom, u.prenom, u.photo FROM user u INNER JOIN liste l ON u.id_user = l.id_user INNER JOIN event e ON l.id_event = e.id_event WHERE e.nom = :nomEvenement";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nomEvenement', $_GET['nom']);
    $stmt->execute();
    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($participants as $participant) {
        $photoParticipant = $participant['photo'];
        $nomParticipant = $participant['nom'];
        $prenomParticipant = $participant['prenom'];
    ?>
        <li>
            <div class="participant">
                <?php if ($photoParticipant) { ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($photoParticipant); ?>" alt="image profil" width="70px" style="border-radius: 50%;">
                <?php } else { ?>
                    <img src="img/default.png" alt="image profil" width="70px" style="border-radius: 50%;">
                <?php } ?>
                <a href="Profil_template.php?nom=<?php echo urlencode($participant['nom']); ?>"><?php echo $participant['nom'] . ' ' . $participant['prenom']; ?></a>
            </div>
        </li>
    <?php } ?>
</ul>
    </div>
    <a href="Accueil.php" class="btn-retour"><-Retour</a>
</body>

</html>