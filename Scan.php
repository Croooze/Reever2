<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <title>Scan QR Code</title>
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

    <h1>Scan QR Code</h1>

    <video id="videoElement" autoplay></video>
    <button onclick="startCamera()">Activer la caméra</button>
    <script>
        // Obtenez la référence à l'élément vidéo
var videoElement = document.getElementById('videoElement');

// Obtenez la référence au conteneur des résultats
var qrResult = document.getElementById('qrResult');

// Créez une instance du scanner Instascan
var scanner = new Instascan.Scanner({ video: videoElement });

// Ajoutez un écouteur d'événement lorsqu'un QR code est détecté
scanner.addListener('scan', function (content) {
    // Affichez le contenu du QR code dans le conteneur des résultats
    qrResult.textContent = content;
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

    </script>


</body>

</html>