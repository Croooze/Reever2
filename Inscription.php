<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel='stylesheet' href='style.css'>
</head>

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
                <input type="submit" value="Inscription">
            </form>
        </div>
    </div>
</body>

</html>