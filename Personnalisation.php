<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reever</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container3 {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .button {
            margin-top: 20px;
            padding: 20px;
            width: 70%;
            height: 80px;
            background-color: #ff6f69;
            color: #fff;
            font-size: 25px;
            font-weight: bold;
            text-align: center;
            line-height: 100px;
            margin-bottom: 20px;
            border-radius: 10px;
            transition: font-size 0.2s ease-in-out;
            text-decoration: none;
        }

        .button:hover {
            font-size: 40px;
        }
    </style>
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

    <div class="container3">
        <a href="#" class="button">Boîte de nuit</a>
        <a href="#" class="button">Conférence</a>
        <a href="#" class="button">Bar</a>
        <a href="#" class="button">Autres</a>
    </div>
</body>

</html>