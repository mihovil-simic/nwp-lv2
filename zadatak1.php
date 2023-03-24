<?php
$db = "radovi";
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";

($conn = new mysqli($dbhost, $dbuser, $dbpass, $db)) or
    die("Povezivanje s bazom podataka nije uspjelo: %s\n" . $conn->error);
$tables = [];
$result = mysqli_query($conn, "SHOW TABLES");
while ($row = mysqli_fetch_row($result)) {
    $tables[] = $row[0];
}

$return = "";
foreach ($tables as $table) {
    $result = mysqli_query($conn, "SELECT * FROM " . $table);
    $num_fields = mysqli_num_fields($result);
    $return .= "DROP TABLE IF EXISTS " . $table . ";";
    $row2 = mysqli_fetch_row(
        mysqli_query($conn, "SHOW CREATE TABLE " . $table)
    );
    $return .= "\n\n" . $row2[1] . ";\n\n";
    for ($i = 0; $i < $num_fields; $i++) {
        while ($row = mysqli_fetch_row($result)) {
            $return .= "INSERT INTO " . $table . " VALUES(";
            for ($j = 0; $j < $num_fields; $j++) {
                $row[$j] = addslashes($row[$j]);
                $row[$j] = preg_replace("/\n/", "\\n", $row[$j]);
                if (isset($row[$j])) {
                    $return .= '"' . $row[$j] . '"';
                } else {
                    $return .= '""';
                }
                if ($j < $num_fields - 1) {
                    $return .= ",";
                }
            }
            $return .= ");\n";
        }
    }
    $return .= "\n\n\n";
}
mysqli_close($conn);

$file = "backup.txt";
file_put_contents($file, $return);

$zip = new ZipArchive();
$zip_file = "backup.zip";
if ($zip->open($zip_file, ZIPARCHIVE::CREATE) !== true) {
    die("Pogreska pri izradi zip datoteke '$zip_file'\n");
}
$zip->addFile($file);
$zip->close();

unlink($file);
?>
