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
    $mdp_confirm = $_POST['mdp_confirm'];
    $bday = $_POST['bday'];

    $sql = ("INSERT INTO user(nom, prenom, mdp, email) VALUES (:nom, :prenom, :mdp, :email)");
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
            <form method="post" action="">
                <h1 class="formulaire">INSCRIPTION</h1>
                <input type="text" id="nom" name="nom" placeholder="Nom">
                <input type="text" id="prenom" name="prenom" placeholder="Prénom">
                <input type="text" id="email" name="email" placeholder="Adresse mail">
                <input type="password" id="mdp" name="mdp" placeholder="Mot de passe">
                <input type="password" id="mdp" placeholder="Confirmer le mot de passe">
                <input class="date" type="date" id="bday" name="bday">
                <input type="submit" value="Inscription" name="envoyer">
            </form>
        </div>
    </div>
</body>

</html>

