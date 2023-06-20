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

// Vérifier si le paramètre "nom" est présent dans l'URL
if (isset($_GET['nom'])) {
    $nom = $_GET['nom'];

    // Récupérer l'événement correspondant au nom spécifié
    $sql = "SELECT * FROM event WHERE nom = :nom";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($event) {
        $eventId = $event['id_event'];

        // Récupérer la liste des utilisateurs liés à l'événement
        $sql = "SELECT user.* FROM user INNER JOIN liste ON user.id_user = liste.id_user WHERE liste.id_event = :event_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':event_id', $eventId);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Événement non trouvé";
    }
} else {
    echo "Paramètre 'nom' manquant dans l'URL";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste de l'événement</title>
    <link rel="stylesheet" href="style.css">
</head>
<header>
        <a href="Accueil.php" class="logo">REEVER</a>
        <nav>
            <a href="Notification.php">Notification</a>
            <a href="Profil.php">Profil</a>
            <a href="Paramètre.php">Paramètres</a>
        </nav>
    </header>

<body>
    <h1>Liste des participants de l'événement : <?php echo $nom; ?></h1>

    <?php if (isset($users) && count($users) > 0) : ?>
        <ul>
            <?php foreach ($users as $user) : ?>
                <li><?php echo $user['nom'] . ' ' . $user['prenom']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>Aucun utilisateur inscrit à cet événement.</p>
    <?php endif; ?>
</body>

</html>