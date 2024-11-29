<?php
$data = json_decode(file_get_contents('php://input'), true);

if(isset($data['image'])) {
    $image = $data['image'];
    $image = str_replace('data:image/png;base64,', '', $image);
    $image = str_replace(' ', '+', $image);
    $image = base64_decode($image);
    file_put_contents('snap.png', $image);
    echo json_encode(['status' => 'success']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="text/javascript" src="https://unpkg.com/webcam-easy@1.1.1/dist/webcam-easy.min.js"></script>
</head>
<body>
    <video id="camfeed" autoplay width="300"></video>
    <a download>Snap</a>
    <script>
        if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
                var video = document.querySelector('#camfeed');
                video.srcObject = stream;
                video.play();
            });
        }else{
            alert('Camera not found');
        }

        document.querySelector('a').addEventListener('click', function(){
            var canvas = document.createElement('canvas');
            canvas.width = 300;
            canvas.height = 300;
            var context = canvas.getContext('2d');
            context.drawImage(document.querySelector('#camfeed'), 0, 0, 300, 300);
            var data = canvas.toDataURL('image/png');
            // this.href = data;
            // this.download = 'snap.png';

            fetch('#', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                    body: JSON.stringify({ image: data })
                }).then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    alert('Image uploaded');
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('Image not uploaded');
                });  
            });
    </script>
</body>
</html>