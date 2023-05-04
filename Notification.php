<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <section class="zone">
        <div class="donnée">
            <form>
                <h1>ÉVÉNEMENT</h1>
                <input type="text" class="qr_texte" placeholder="Nom de l'événement" onchange="generateur()">
                <button type="submit" onclick="generateur()">Générer le QR code</button>
            </form>
        </div>

        <div class="qrcode">
            <p>QR CODE : </p>
            <script src="qrcode.min.js"></script>
            <script type="text/javascript">
                var qr_texte = document.querySelector(".qr_texte");

                function generateur() {
                    var qrcode = document.querySelector(".qr_code");
                    qrcode.style.display = "flex";
                    new QRCode(qrcode, qr_texte.value);
                }
            </script>
        </div>
    </section>

</body>

</html>