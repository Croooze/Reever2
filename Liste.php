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
    // Vérifier si l'ID de l'événement est présent dans les paramètres de l'URL
    if (isset($_GET['id_event'])) {
        $idEvent = $_GET['id_event'];

        // Connectez-vous à la base de données
        $host = 'localhost';
        $dbname = 'reever';
        $username = 'root';
        $password = '';

        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Récupérer le nom de l'événement à partir de la table "Event"
            $sql = "SELECT nom FROM Event WHERE id_event = :id_event";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_event', $idEvent);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $nomEvenement = $result['nom'];
                echo '<p>Participer à l\'événement : "' . $nomEvenement . '"</p>';

                // Vérifier si l'utilisateur a cliqué sur "Accepter"
                if (isset($_POST['accepter'])) {
                    // Vérifier si l'utilisateur est connecté
                    if (isset($_SESSION['user_id'])) {
                        $userId = $_SESSION['user_id'];

                        // Ajouter l'utilisateur à la liste des participants de l'événement
                        $sql = "INSERT INTO liste(id_event, id_user) VALUES (:event_id, :user_id)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':event_id', $idEvent);
                        $stmt->bindParam(':user_id', $userId);
                        $stmt->execute();

                        // Rediriger l'utilisateur vers la page de liste des participants
                        header("Location: liste.php?nom=" . urlencode($nomEvenement));
                        exit();
                    }
                }

                // Gérer le cas où l'utilisateur clique sur "Refuser"
                if (isset($_POST['refuser'])) {
                    // Rediriger l'utilisateur vers la page d'accueil
                    header("Location: Accueil.php");
                    exit();
                }

                // Afficher les boutons "Accepter" et "Refuser"
                echo '
                <form method="POST">
                    <button type="submit" name="accepter">Accepter</button>
                    <button type="submit" name="refuser">Refuser</button>
                </form>';
            } else {
                echo '<p>Participer à l\'événement : [Nom de l\'événement non trouvé]</p>';
            }
        } catch (PDOException $e) {
            echo "La connexion à la base de données a échoué: " . $e->getMessage();
        }
    } else {
        echo '<p>Participer à l\'événement : [ID de l\'événement non spécifié]</p>';
    }
    ?>

    <p>Vous serez affiché dans la liste de cet événement pour une durée de 24h</p>
</body>

</html>