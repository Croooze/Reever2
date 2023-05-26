<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Modification du profil</title>
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
    <div class="trucgris">
        <div class="Mbiographie">Modifie ta biographie</div>
        <div class="text">
            <?php
            session_start();
            // Vérifier si l'utilisateur est connecté
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                // Établir la connexion à la base de données
                $host = 'localhost';
                $dbname = 'reever';
                $username = 'root';
                $password = '';

                try {
                    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Récupérer les données de l'utilisateur connecté
                    $sql = "SELECT * FROM user WHERE id_user = :user_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Vérifier si l'utilisateur existe
                    if ($user) {
                        $nom = $user['nom'];
                        $prenom = $user['prenom'];
                        $description = $user['description'];
                        $instagram = $user['instagram'];
                    } else {
                        echo "Utilisateur non trouvé";
                    }
                } catch (PDOException $e) {
                    echo "La connexion a échoué: " . $e->getMessage();
                }

                // Traiter le formulaire de modification
                if (isset($_POST['envoyer'])) {
                    $description = $_POST['description'];
                    $instagram = $_POST['instagram'];

                    // Mettre à jour les données de l'utilisateur
                    $sql = "UPDATE user SET description = :description, instagram = :instagram WHERE id_user = :user_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':instagram', $instagram);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->execute();

                    // Redirection vers la page de profil
                    header("Location: Profil.php");
                    exit;
                }

                // Fermer la connexion à la base de données
                $conn = null;
            } else {
                echo "Utilisateur non connecté";
            }
            ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <label for="nom">Nom:</label>
                <input type="text" name="nom" value="<?php echo $nom; ?>"><br>

                <label for="prenom">Prénom:</label>
                <input type="text" name="prenom" value="<?php echo $prenom; ?>"><br>

                <label for="description">Description:</label>
                <textarea name="description"><?php echo $description; ?></textarea><br>

                <label for="instagram">Instagram:</label>
                <input type="text" name="instagram" value="<?php echo $instagram; ?>"><br>

                <input type="file" name="photo"><br>

                <input type="submit" name="envoyer" value="Modifier">
            </form>
        </div>
    </div>
</body>
</html>