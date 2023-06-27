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
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="img/png" href="img/favicon.ico"/>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <title>Scan QR Code</title>
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

    <h1>Scan QR Code</h1>

    <video id="videoElement" autoplay="autoplay" class="active" style="margin-left: 25%;transform: scaleX(-1);"></video>
    <button onclick="startCamera()">Activer la caméra</button>

    <script>
        // Obtenez la référence à l'élément vidéo
        var videoElement = document.getElementById('videoElement');

        // Créez une instance du scanner Instascan
        var scanner = new Instascan.Scanner({ video: videoElement });

        // Ajoutez un écouteur d'événement lorsqu'un QR code est détecté
        scanner.addListener('scan', function (content) {
            // Envoyer le contenu du QR code au serveur pour traitement
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'traitement_qr_code.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Traitement réussi, faire quelque chose ici si nécessaire
                            console.log(response.message);
                        } else {
                            // Traitement échoué, afficher un message d'erreur si nécessaire
                            console.error(response.message);
                        }
                    } else {
                        // Erreur de la requête HTTP, afficher un message d'erreur si nécessaire
                        console.error('Erreur lors de la requête :', xhr.status);
                    }
                }
            };
            var data = { content: content };
            xhr.send(JSON.stringify(data));
        });

        // Démarrez le scanner de QR code
        Instascan.Camera.getCameras()
            .then(function (cameras) {
                if (cameras.length > 0) {
                    // Utilisez la caméra arrière par défaut
                    scanner.start(cameras[0]);
                } else {
                    console.error('Aucune caméra disponible.');
                }
            })
            .catch(function (error) {
                console.error('Erreur lors de la récupération des caméras :', error);
            });

        // Fonction pour activer la caméra
        function startCamera() {
            scanner.start();
        }
    </script>

</body>

</html>
