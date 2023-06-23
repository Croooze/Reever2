<?php
session_start();
$host = 'localhost';
$dbname = 'reever';
$username = 'root';
$password = '';

$errors = array(); // Variable pour stocker les erreurs

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $errors[] = "La connexion a échoué : " . $e->getMessage();
}

if (isset($_POST['accepter'])) {
    // Vérifier si l'utilisateur est déjà ajouté à la liste des participants
    $sql = "SELECT * FROM liste WHERE id_user = :userId AND id_event = (SELECT id_event FROM event WHERE nom = :nomEvenement LIMIT 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $_SESSION['user_id']);
    $stmt->bindParam(':nomEvenement', $_GET['nom']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo 'Vous avez déjà été ajouté à la liste des participants.';
    } else {
        // Ajouter l'utilisateur à la liste des participants
        $sql = "INSERT INTO liste (id_user, id_event) VALUES (:userId, (SELECT id_event FROM event WHERE nom = :nomEvenement LIMIT 1))";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $_SESSION['user_id']);
        $stmt->bindParam(':nomEvenement', $_GET['nom']);
        $stmt->execute();

        echo 'Vous avez été ajouté à la liste des participants avec succès.';
        header("Location: Accueil.php");
        exit;
    }
} elseif (isset($_POST['refuser'])) {
    echo 'Vous avez refusé de participer à l\'événement.';
    header("Location: Accueil.php");
    exit;
}

// Récupérer les informations de l'utilisateur
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Effectuer une requête pour récupérer les informations de l'utilisateur
    $sql = "SELECT nom, prenom, photo FROM user WHERE id_user = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $nomUtilisateur = $user['nom'];
        $prenomUtilisateur = $user['prenom'];
        $photoUtilisateur = $user['photo'];
    }
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
</head>

<body>
    <header>
        <a href="Accueil.php" class="logo">REEVER</a>
        <nav>
            <a href="Profil.php">Profil</a>
            <a href="Paramètre.php">Paramètres</a>
        </nav>
        <?php if ($user && $photoUtilisateur) { ?>
                <a href="Profil.php" class="profile-link">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($photoUtilisateur); ?>" alt="Photo de profil" class="profile-photo">
                </a>
            <?php } else { ?>
                <a href="Profil.php" class="profile-link">
                    <img src="img/default.png" alt="Photo de profil" class="profile-photo">
                </a>
            <?php } ?>
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

    <form method="POST">
        <button type="submit" name="accepter">Accepter</button>
        <button type="submit" name="refuser">Refuser</button>
    </form>
</body>

</html>
