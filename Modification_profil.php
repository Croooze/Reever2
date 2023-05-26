<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
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
// Établir la connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reever";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Exécuter une requête pour récupérer les données
$sql = "SELECT * FROM user";
$result = $conn->query($sql);

// Vérifier s'il y a des résultats
if ($result->num_rows > 0) {
    // Initialiser une variable pour stocker les données
    $data = "";

    // Parcourir les résultats et ajouter les données à la variable
    while ($row = $result->fetch_assoc()) {
        $data .= $row["description"] . " " . $row["description"] . "\n";
    }

    // Afficher les données dans le textarea
    echo '<textarea>' . $data . '</textarea>';
} else {
    echo "Aucune donnée trouvée.";
}

// Fermer la connexion à la base de données
$conn->close();
?>
    
    </div>
   </div>
</body>
</html>