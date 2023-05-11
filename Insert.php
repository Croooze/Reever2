<?php
// Configuration de la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reever";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupération des données du formulaire
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$mdp = $_POST['mdp'];
$bday = $_POST['bday'];

// Insertion des données dans la base de données
$sql = "INSERT INTO formulaire (nom, prenom, email, mdp, bday) VALUES ('$nom', '$prenom', '$email', '$mdp', '$bday')";

if ($conn->query($sql) === TRUE) {
    echo "Les données ont été enregistrées avec succès.";
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>