<?php

if(isset($_FILES['file'])) {
    $path = 'uploads/' . $_FILES['file']['name'] . '.enc';
    $content = file_get_contents($_FILES['file']['tmp_name']);
    $key = openssl_random_pseudo_bytes(32);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($content, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    file_put_contents($path, $encrypted);
    file_put_contents($path . '.key', $key);
    file_put_contents($path . '.iv', $iv);
    echo 'Datoteka je uspješno kriptirana i spremljena!';
}

if(isset($_GET['decrypt'])) {
    $path = 'uploads/' . $_GET['decrypt'];
    $encrypted = file_get_contents($path);
    $key = file_get_contents($path . '.key');
    $iv = file_get_contents($path . '.iv');
    $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($path, '.enc') . '"');
    echo $decrypted;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kriptiranje i dekriptiranje datoteka</title>
</head>
<body>
    <h1>Kriptiranje datoteka</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="file">Odaberite datoteku za ucitavanje:</label><br>
        <input type="file" name="file" id="file"><br><br>
        <input type="submit" value="Kriptiraj">
    </form>
    
    <h1>Dekriptiranje datoteka</h1>
    <?php
    $files = glob('uploads/*.enc');
    foreach($files as $file) {
        echo '<a href="?decrypt=' . basename($file) . '">' . basename($file, '.enc') . '</a><br>';
    }
    ?>
</body>
</html>