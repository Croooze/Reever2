<?php
session_start();
$host = 'localhost';
$dbname = 'reever';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Vérifier si un fichier a été téléchargé
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $file = $_FILES['photo'];

            // Vérifier le type de fichier
            $allowedTypes = ['image/jpeg', 'image/png'];
            if (in_array($file['type'], $allowedTypes)) {
                // Lire le contenu du fichier
                $content = file_get_contents($file['tmp_name']);

                // Mettre à jour la photo de profil de l'utilisateur
                $sql = "UPDATE user SET photo = :photo WHERE id_user = :user_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':photo', $content, PDO::PARAM_LOB);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
            } else {
                echo "Type de fichier non pris en charge";
            }
        }

        // Récupérer les autres données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $description = $_POST['description'];
        $instagram = $_POST['instagram'];

        // Mettre à jour les données de profil de l'utilisateur
        $sql = "UPDATE user SET nom = :nom, prenom = :prenom, description = :description, instagram = :instagram WHERE id_user = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':instagram', $instagram);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Redirection vers la page de profil
        header("Location: Profil.php");
        exit;
    } else {
        echo "Utilisateur non connecté";
    }

    // Fermer la connexion à la base de données
    $conn = null;
} catch (PDOException $e) {
    echo "La connexion a échoué: " . $e->getMessage();
}
?>
