<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Création événement</title>
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

    <section class="zone">
        <div class="donnée">
            <form action="" method="POST">
                <h1>ÉVÉNEMENT</h1>
                <input type="text" name="nom" placeholder="Nom de l'événement">
                <button type="submit">Générer le QR code</button>
            </form>
        </div>

        <div class="qrcode" id="qrcode"></div>
    </section>

    <script src="qrcode.min.js"></script>
    <script type="text/javascript">
        function generateur(qr_texte) {
            var qrcode = document.querySelector("#qrcode");
            qrcode.style.display = "flex";
            new QRCode(qrcode, qr_texte);
        }
    </script>

</body>
<?php
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

if (isset($_POST['nom'])) {
    $nom = $_POST['nom'];

    $sql = "INSERT INTO event(nom) VALUES (:nom)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->execute();

    $eventId = $conn->lastInsertId();

    $url = "liste.php?id=" . $eventId;
    echo "<script>generateur('$url')</script>";
}
?>
</html>