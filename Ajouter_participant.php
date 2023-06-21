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

if (isset($_SESSION['user_id']) && isset($_GET['nom'])) {
    $userId = $_SESSION['user_id'];
    $nomEvenement = $_GET['nom'];

    if (isset($_POST['accepter'])) {
        // Vérifier si l'utilisateur est déjà ajouté à la liste des participants
        $sql = "SELECT * FROM liste WHERE id_user = :userId AND id_event = (SELECT id_event FROM event WHERE nom = :nomEvenement)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':nomEvenement', $nomEvenement);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo 'Vous avez déjà été ajouté à la liste des participants.';
        } else {
            // Ajouter l'utilisateur à la liste des participants
            $sql = "INSERT INTO liste (id_user, id_event) VALUES (:userId, (SELECT id_event FROM event WHERE nom = :nomEvenement))";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':nomEvenement', $nomEvenement);
            $stmt->execute();

            echo 'Vous avez été ajouté à la liste des participants avec succès.';
        }
    } elseif (isset($_POST['refuser'])) {
        echo 'Vous avez refusé de participer à l\'événement.';
    }
} else {
    echo 'Une erreur s\'est produite lors de l\'ajout du participant.';
}
?>
