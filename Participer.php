<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reever</title>
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
        echo '<p>Participer à l\'événement : "' . $nomEvenement . '"</p>';
    } else {
        echo '<p>Participer à l\'événement : [Nom de l\'événement non spécifié]</p>';
    }
    ?>

    <p>Vous serez affiché dans la liste de cet événement pour une durée de 24 heures.</p>
    <button>Accepter</button>
    <button>Refuser</button>
</body>

</html>