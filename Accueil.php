<?php
session_start();
$host = 'localhost';
$dbname = 'reever';
$username = 'root';
$password = '';

$dsn = "mysql:host=$host;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Récupérer le dernier événement créé par l'utilisateur connecté
        $sql = "SELECT e.nom, e.photo FROM event e INNER JOIN liste l ON e.id_event = l.id_event WHERE l.id_user = :user_id ORDER BY e.id_event DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupérer les informations de l'utilisateur connecté
        $sql = "SELECT photo FROM user WHERE id_user = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reever</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="img/png" href="img/favicon.ico"/>
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

    <section class="evenement">
        <?php if ($event) { ?>
            <?php
            $eventLink = "liste.php?nom=" . urlencode($event['nom']);
            ?>
            <a href="<?php echo $eventLink; ?>" class="event-link">
                <div class="content">
                    <?php if ($event['photo']) { ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($event['photo']); ?>" alt="Event Photo" />
                    <?php } ?>
                    <span class="event-name <?php echo ($event['photo']) ? 'with-photo' : 'without-photo'; ?>">
                        <?php echo ucfirst(strtolower($event['nom'])); ?>
                    </span>
                </div>
            </a>
        <?php } else { ?>
            <div class="content">
                <span class="event-name without-photo">
                    Scannez ou Créez un événement pour voir la liste ici
                </span>
            </div>
        <?php } ?>
    </section>

    <section class="card">
        <div class="left">
            <b>
                <a href="Scan.php">SCAN</a>
            </b>
        </div>

        <div class="right">
            <b>
                <a href="Création_event.php">CRÉER</a>
            </b>
        </div>
    </section>

</body>

</html>