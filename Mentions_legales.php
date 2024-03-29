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

// Vérifier si l'utilisateur est connecté
$user = null;
if (isset($_SESSION['user_id'])) {
    // Récupérer les informations de l'utilisateur connecté depuis la base de données
    $sql = "SELECT * FROM user WHERE id_user = :userId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userId', $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="img/png" href="img/favicon.ico"/>
    <title>Mentions Légales - Reever</title>
</head>

<body class="mention_legale">
    <header>
        <a href="Accueil.php" class="logo">REEVER</a>
        <nav>
            <a href="Profil.php">Profil</a>
            <a href="Parametres.php">Paramètres</a>
        </nav>
        <?php if ($user && $user['photo']) { ?>
            <a href="Profil.php" class="profile-link">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($user['photo']); ?>" alt="Photo de profil"
                    class="profile-photo">
            </a>
        <?php } else { ?>
            <a href="Profil.php" class="profile-link">
                <img src="img/default.png" alt="Photo de profil" class="profile-photo">
            </a>
        <?php } ?>
    </header>
    <div class="mention_description">
        <h1>Mentions Légales</h1>

        <h2>Informations générales</h2>
        <p>Raison sociale : Reever</p>
        <p>Siège social : 7 rue Jean-Marie Leclair 69009 Lyon</p>
        <p>Numéro de téléphone : 07 67 41 12 36</p>
        <p>Email : contact@reever.com</p>

        <h2>Hébergement</h2>
        <p>L'hébergement du site est assuré par Reever</p>
        <p>Adresse de l'hébergeur : 7 rue Jean-Marie Leclair 69009 Lyon</p>
        <p>Numéro de téléphone de l'hébergeur : 07 67 41 12 36</p>

        <h2>Propriété intellectuelle</h2>
        <p>Tous les contenus présents sur le site Reever (textes, images, logos, vidéos, etc.) sont protégés par le
            droit de
            la propriété intellectuelle et sont la propriété exclusive de Reever ou de ses partenaires. Toute
            reproduction,
            distribution, modification ou utilisation de ces contenus sans autorisation expresse est interdite.</p>

        <h2>Protection des données personnelles</h2>
        <p>Reever s'engage à protéger la confidentialité des données personnelles collectées sur son site. Les données
            collectées sont utilisées uniquement dans le cadre des services proposés par Reever et ne sont pas
            communiquées
            à des tiers sans consentement préalable.</p>

        <h2>Cookies</h2>
        <p>Le site Reever utilise des cookies pour améliorer l'expérience de navigation des utilisateurs. Les cookies
            sont
            de petits fichiers textes stockés sur le disque dur de l'utilisateur. En utilisant le site Reever, vous
            consentez à l'utilisation de cookies.</p>


        <h2>Liens externes</h2>
        <p>Le site Reever peut contenir des liens vers des sites externes. Reever ne peut être tenu responsable du
            contenu
            ou des pratiques de confidentialité de ces sites externes.</p>

        <h2>Modification des mentions légales</h2>
        <p>Reever se réserve le droit de modifier les présentes mentions légales à tout moment. Les modifications
            prendront
            effet dès leur publication sur le site.</p>

        <p>Date de dernière mise à jour : 20 Juin 2023</p>
    </div>
</body>

</html>