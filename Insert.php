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

if (!isset($_SESSION['user_id'])) {
    header("Location: Connexion.php");
    exit;
}

$userID = $_SESSION['user_id'];

// Récupération des données de l'utilisateur connecté
$sql = "SELECT * FROM user WHERE id=:id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $userID);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($user)) {
    $errors[] = "Utilisateur introuvable!";
} else {
    // Afficher les données de l'utilisateur
    echo "Nom: " . $user['nom'] . "<br>";
    echo "Prénom: " . $user['prenom'] . "<br>";
    echo "Email: " . $user['email'] . "<br>";
}
?>