<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Profili osoba</title>
    <style>
        .profile {
            display: inline-block;
            width: 200px;
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
        }
        .profile img {
            width: 100%;
            height: auto;
        }
        .profile h2 {
            margin: 10px 0 5px;
        }
        .profile p {
            margin: 5px 0;s
        }
    </style>
</head>
<body>
<?php
$xml = simplexml_load_file("LV2.xml");
if ($xml === false) {
    die("Greska: nije moguce ucitati XML datoteku.");
}

foreach ($xml->record as $osoba) {
    $id = (string) $osoba["id"];
    $ime = (string) $osoba->ime;
    $prezime = (string) $osoba->prezime;
    $email = (string) $osoba->email;
    $spol = (string) $osoba->spol;
    $slika = (string) $osoba->slika;
    $zivotopis = (string) $osoba->zivotopis;

    echo '<div class="profile">';
    echo '<img src="' . $slika . '" alt="' . $ime . " " . $prezime . '">';
    echo "<h2>" . $ime . " " . $prezime . "</h2>";
    echo "<p><strong>Email:</strong> " . $email . "</p>";
    echo "<p>" . $zivotopis . "</p>";
    echo "</div>";
}
?>
</body>
</html>