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
    echo "La connexion a échoué : " . $e->getMessage();
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    $sql = "SELECT * FROM user WHERE email=:email AND mdp=:mdp";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mdp', $mdp);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: Accueil.php");
        exit;
    } else {
        $message = "Adresse email ou mot de passe invalide!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel='stylesheet' href='style.css'>
</head>
<body>
    <div class="gauche">
        <h1>REEVER</h1>
        <p>Le site de rencontre et de retrouvaille</p>
    </div>
    <div class="droite">
        <div class="connexion">
            <div class="box">
                <form method="post" action="">
                    <h1 class="formulaire">CONNEXION</h1>
                    <?php if (isset($message)) { ?>
                        <div class="message">
                            <?php echo $message; ?>
                        </div>
                    <?php } ?>
                    <input type="text" id="email" name="email" placeholder="Adresse mail" required>
                    <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required>
                    <input type="submit" value="Connexion" name="login">
                    <a href="Inscription.php">
                        <p class="compte"> Créer un compte !</p>
                    </a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>