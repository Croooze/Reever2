<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js%22%3E</script>
    <script src="https://cdn.jsdelivr.net/npm/@zxing/library@0.18.1/zxing.umd.min.js%22%3E</script>
</head>

<body>
    <h1>Scan QR Code</h1>

    <video id="video" width="100%" height="auto" autoplay></video>
    <canvas id="canvas" style="display: none;"></canvas>

    <script>
        $(document).ready(function () {
            var video = document.getElementById('video');
            var canvas = document.getElementById('canvas');
            var context = canvas.getContext('2d');

            // Accéder à la caméra et afficher le flux vidéo
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function (stream) {
                    video.srcObject = stream;
                })
                .catch(function (error) {
                    console.log('Erreur d'accès à la caméra: ', error);
                });

            // Décoder le QR code à partir de l'image du canevas
            function decodeQRCode() {
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                var imageData = context.getImageData(0, 0, canvas.width, canvas.height);

                var codeReader = new ZXing.BrowserQRCodeReader();
                codeReader.decodeFromImageElement(canvas)
                    .then(function (result) {
                        alert("QR code scanné : " + result.text);
                    })
                    .catch(function (error) {
                        console.log('Erreur de décodage du QR code: ', error);
                    });

                requestAnimationFrame(decodeQRCode);
            }
// Démarrer le décodage du QR code
            requestAnimationFrame(decodeQRCode);
        });
    </script>
</body>

</html>