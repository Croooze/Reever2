<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Liste Participants</title>
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

    <div class="list">
        <?php
        // Vérifier si l'ID de l'événement est présent dans l'URL
        if (isset($_GET['id'])) {
            $eventId = $_GET['id'];

            // Vérifier si le nom de l'événement est présent dans l'URL
            if (isset($_GET['nom'])) {
                $nom = $_GET['nom'];

                // Afficher le titre avec le nom de l'événement suivi de "_liste_participants"
                echo "<h1>" . htmlspecialchars($nom) . "_liste_participants</h1>";
            }
        }
        ?>
    </div>
</body>

</html>