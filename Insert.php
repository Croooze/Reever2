<?php
$host = 'localhost';
$dbname = 'reever';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "La connexion a échoué : " . $e->getMessage();
}

if (isset($_POST['envoyer'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    // Vérification de la correspondance du mot de passe
    $mdpConfirme = $_POST['mdp_confirme'];
  

    // Vérification si l'utilisateur existe déjà
    $sql = "SELECT * FROM user WHERE email=:email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
   

    // Hashage du mot de passe
    $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);

    // Insertion des données dans la base de données
    $sql = "INSERT INTO user (nom, prenom, email, mdp) VALUES (:nom, :prenom, :email, :mdp)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mdp', $hashedPassword);
    $stmt->execute();

    // Redirection après l'insertion
    header("Location: Accueil.php");
    exit;
}
?>