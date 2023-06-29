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

if (isset($_POST['submit'])) {
    $nom = $_POST['nom'];

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Vérifier si l'événement avec le même nom existe déjà
        $sql = "SELECT id_event FROM event WHERE nom = :nom";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Ajouter un message d'erreur à la liste des erreurs
            $errors[] = 'Ce nom d\'événement est indisponible, veuillez en saisir un autre.';
        } else {
            // Insérer l'événement dans la base de données
            $sql = "INSERT INTO event(nom, qr_code) VALUES (:nom, :qrCode)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nom', $nom);

            // Générer le QR code
            $url = "http://" . $_SERVER['HTTP_HOST'] . "/reever/participer.php?nom=" . urlencode($nom);
            $qrCodeData = file_get_contents("https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($url));
            $stmt->bindParam(':qrCode', $qrCodeData, PDO::PARAM_LOB);

            $stmt->execute();

            $eventId = $conn->lastInsertId();

            // Vérifier si un fichier image a été sélectionné
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $photo = $_FILES['photo']['tmp_name'];

                // Lire le contenu du fichier photo
                $photoContent = file_get_contents($photo);

                // Mettre à jour le champ 'photo' dans la table 'event'
                $sql = "UPDATE event SET photo = :photo WHERE id_event = :event_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':photo', $photoContent, PDO::PARAM_LOB);
                $stmt->bindParam(':event_id', $eventId);
                $stmt->execute();
            }
        }
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
    <link rel="shortcut icon" type="img/png" href="img/favicon.ico" />
    <style>
        .zone button[type="file"] {
            display: block;
            margin-top: 10px;
        }
    </style>
    <title>Création événement</title>
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
    <section class="zone">
        <div class="donnée">
            <form action="" method="POST" enctype="multipart/form-data">
                <h1>ÉVÉNEMENT</h1>
                <input type="text" name="nom" placeholder="Nom de l'événement">
                <button type="submit" name="submit">Générer le QR code</button>
                <input type="file" name="photo" accept="image/*">
            </form>
        </div>

        <div class="qrcode" id="qrcode">
            <?php
            if (isset($_POST['submit']) && empty($errors)) {
                $nom = $_POST['nom'];
                $url = "http://" . $_SERVER['HTTP_HOST']. "/reever/participer.php?nom=" . urlencode($nom);
                $qrCodeData = file_get_contents("https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($url));
                echo '<a href="/reever/participer.php?nom=' . urlencode($nom) . '"><img src="data:image/png;base64,' . base64_encode($qrCodeData) . '"></a>';

                // Mettre à jour le champ 'qr_code' dans la table 'event'
                $sql = "UPDATE event SET qr_code = :qrCode WHERE nom = :nom";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':qrCode', $qrCodeData, PDO::PARAM_LOB);
                $stmt->bindParam(':nom', $nom);
                $stmt->execute();
            }
            ?>
        </div>
    </section>

    <?php
    // Afficher les erreurs
    if (!empty($errors)) {
        echo '<div class="error">';
        foreach ($errors as $error) {
            echo '<p>' . $error . '</p>';
        }
        echo '</div>';
    }
    ?>

</body>

</html>