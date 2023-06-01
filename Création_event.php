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
                <button type="submit" name="submit">Générer le QR code</button>
            </form>
        </div>

        <div class="qrcode" id="qrcode">
            <?php
            if (isset($_POST['submit'])) {
                $nom = $_POST['nom'];
                $url = "http://localhost/reever/participer.php?nom=" . urlencode($nom);
                echo '<a href="participer.php"><img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($url) . '"></a>';
            }
            ?>
        </div>
    </section>

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

    if (isset($_POST['submit'])) {
        $nom = $_POST['nom'];

        // Vérifier si l'utilisateur est connecté
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            $sql = "INSERT INTO event(nom) VALUES (:nom)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nom', $nom);
            $stmt->execute();

            $eventId = $conn->lastInsertId();

            // Enregistrer l'événement dans la liste de l'utilisateur connecté
            $sql = "INSERT INTO liste(id_event, id_user) VALUES (:event_id, :user_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':event_id', $eventId);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();

            echo '<div class="center"><a href="personnalisation.php" class="custom-btn">Personnaliser Événement</a></div>';
        }
    }
    ?>

</body>

</html>