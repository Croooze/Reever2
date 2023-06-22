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

// Récupérer le nom de l'événement depuis l'URL
if (isset($_GET['nom'])) {
    $nomEvenement = $_GET['nom'];

    // Récupérer le QR code de l'événement dans la base de données
    $sql = "SELECT qr_code FROM event WHERE nom = :nomEvenement";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nomEvenement', $nomEvenement);
    $stmt->execute();
    $qrCodeRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($qrCodeRow && $qrCodeRow['qr_code']) {
        $qrCodeData = $qrCodeRow['qr_code'];

        // Afficher le QR code
        echo '<img src="data:image/png;base64,' . base64_encode($qrCodeData) . '" alt="QR Code">';
    } else {
        echo 'QR Code non disponible';
    }
} else {
    echo 'Nom de l\'événement non spécifié';
}

?>