<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reever</title>
    <link rel='stylesheet' href='style.css'>
</head>

<?php
  $host = 'localhost';
  $dbname = 'reever';
  $username = 'root';
  $password = 'root';
    
  $dsn = "mysql:host=$host;dbname=$dbname"; 
  // récupérer tous les utilisateurs
  $sql = "SELECT * FROM event";
   
  try{
   $pdo = new PDO($dsn, $username, $password);
   $stmt = $pdo->query($sql);
   
   if($stmt === false){
    die("Erreur");
   }
   
  }catch (PDOException $e){
    echo $e->getMessage();
  }
?>
    
<tbody>
     <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
     <tr>
       <td><?php echo ($row['nom']); ?></td>
     </tr>
     <?php endwhile; ?>
   </tbody>


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
            <p><?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?></p>
            <p><?php echo ($row['nom']); ?></p>
            <p><?php endwhile; ?></p>
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