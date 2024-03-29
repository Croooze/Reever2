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

// Récupérer le nom de l'événement
$nomEvenement = isset($_GET['nom']) ? $_GET['nom'] : '';

// Fonction pour télécharger le QR code
function downloadQRCode($data, $filename)
{
    header('Content-Type: image/png');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    echo $data;
    exit;
}

// Vérifier si le QR code est disponible pour le téléchargement
if (isset($_GET['nom']) && isset($_GET['download'])) {
    $nomEvenement = $_GET['nom'];

    // Récupérer le QR code de l'événement dans la base de données
    $sql = "SELECT qr_code FROM event WHERE nom = :nomEvenement";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nomEvenement', $nomEvenement);
    $stmt->execute();
    $qrCodeRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($qrCodeRow && $qrCodeRow['qr_code']) {
        $qrCodeData = $qrCodeRow['qr_code'];
        $filename = urlencode($nomEvenement) . '.png';
        downloadQRCode($qrCodeData, $filename);
    }
}

// Compter le nombre de participants à l'événement
if (!empty($nomEvenement)) {
    $sqlCount = "SELECT COUNT(*) AS participantCount FROM user u INNER JOIN liste l ON u.id_user = l.id_user INNER JOIN event e ON l.id_event = e.id_event WHERE e.nom = :nomEvenement";
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->bindParam(':nomEvenement', $nomEvenement);
    $stmtCount->execute();
    $participantCount = $stmtCount->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reever - Liste des participants</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="img/png" href="img/favicon.ico" />
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
                <img src="data:image/jpeg;base64,<?php echo base64_encode($user['photo']); ?>" alt="Photo de profil"
                    class="profile-photo">
            </a>
        <?php } else { ?>
            <a href="Profil.php" class="profile-link">
                <img src="img/default.png" alt="Photo de profil" class="profile-photo">
            </a>
        <?php } ?>
    </header>

    <?php if (!empty($nomEvenement)) { ?>
        <h1 style="color: #fff;">Liste des participants à l'événement :
            <?php echo $nomEvenement; ?> (
            <?php echo $participantCount['participantCount']; ?>
            <?php echo ($participantCount['participantCount'] > 1) ? 'participants' : 'participant'; ?>)
        </h1>

    <?php } else { ?>
        <p>Événement : [Nom de l'événement non spécifié]</p>
    <?php } ?>

    <div class="lemon">
        <ul>
            <?php
            $sql = "SELECT DISTINCT u.id_user, u.nom, u.prenom, u.photo FROM user u INNER JOIN liste l ON u.id_user = l.id_user INNER JOIN event e ON l.id_event = e.id_event WHERE e.nom = :nomEvenement";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nomEvenement', $nomEvenement);
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
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($photoParticipant); ?>" alt="image profil"
                                width="70px" height="70px" style="border-radius: 50%;">
                        <?php } else { ?>
                            <img src="img/default.png" alt="image profil" width="70px" height="70px"
                                style="border-radius: 50%;">
                        <?php } ?>
                        <a href="Profil_template.php?nom=<?php echo urlencode($participant['nom']); ?>"><?php echo $participant['prenom'] . ' ' . $participant['nom']; ?></a>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
    <a href="Accueil.php" class="btn-retour"><- Retour</a>

            <?php if (!empty($nomEvenement)) { ?>
                <div class="qrcodediv">
                    <a href="?nom=<?php echo urlencode($nomEvenement); ?>&download=<?php echo urlencode($nomEvenement); ?>"
                        class="btn-download">Voir QR Code de l'événement</a>
                </div>
            <?php } ?>
</body>

</html>