<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel='stylesheet' href='style.css'>
</head>

<?php
$host = 'localhost';
$dbname = 'reever';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "la connexion a échoué: " . $e->getMessage();
}

if (isset($_POST['envoyer'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    $bday = $_POST['bday'];

    $sql = ("INSERT INTO `user`(`nom`, `prenom`, `mdp`, `email`) VALUES (`:nom`, `:prenom`, `:mdp`, `:email`)");
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mdp', $mdp);
    $stmt->execute();
}
?>

<body>
    <div class="gauche">
        <h1>REEVER</h1>
        <p>Le site de rencontre et de retrouvaille</p>
    </div>
    <div class="droite">
        <div class="box">
            <form method="post" action="Accueil.php">
                <h1 class="formulaire">INSCRIPTION</h1>
                <input type="text" id="nom" placeholder="Nom">
                <input type="text" id="prenom" placeholder="Prénom">
                <input type="text" id="email" placeholder="Adresse mail">
                <input type="password" id="mdp" placeholder="Mot de passe">
                <input type="password" id="mdp" placeholder="Confirmer le mot de passe">
                <input class="date" type="date" id="bday" name="bday">
                <input type="submit" value="Inscription" name="envoyer">
            </form>
        </div>
    </div>
</body>

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

$conn->close();?>

</html>