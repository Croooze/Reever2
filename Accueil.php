<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reever</title>
    <link rel="stylesheet" href="style.css">
</head>

<?php
$host = 'localhost';
$dbname = 'reever';
$username = 'root';
$password = '';

$dsn = "mysql:host=$host;dbname=$dbname";
// récupérer le dernier événement
$sql = "SELECT * FROM event ORDER BY id_event DESC LIMIT 1";

try {
    $pdo = new PDO($dsn, $username, $password);
    $stmt = $pdo->query($sql);

    if ($stmt === false) {
        die("Erreur");
    }

} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<body>
    <header>
        <a href="Accueil.php" class="logo">REEVER</a>
        <nav>
            <a href="Notification.php">Notification</a>
            <a href="Profil.php">Profil</a>
            <a href="Paramètre.php">Paramètres</a>
        </nav>
    </header>

    <section class="evenement">
        <div class="content">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <?php
                $eventLink = "liste.php?nom=" . urlencode($row['nom']);
                ?>
                <a href="<?php echo $eventLink; ?>" class="button"><?php echo $row['nom']; ?></a>
            <?php endwhile; ?>
        </div>
    </section>

    <section class="card">
        <div class="left">
            <b>
                <a href="Scan.php">SCAN</a>
            </b>
        </div>

        <div class="right">
            <b>
                <a href="Création_event.php">CRÉER</a>
            </b>
        </div>
    </section>

</body>

</html>