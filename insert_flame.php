<?php
// 1. Hibák kijelzése, hogy ne csak 500-as hibát lássunk
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Adatbázis adatok (Ugyanaz, mint a display_data.php-ban)
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "flame_sensor";

// 3. Kapcsolódás
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

// 4. Adat fogadása
if (isset($_GET['status'])) {
    // Biztosítjuk, hogy szám legyen (0 vagy 1)
    $status = intval($_GET['status']);

    // 5. Beszúrás (Ellenőrizd: a tábla neve flame_measurements, az oszlop flame_detected)
    $stmt = $conn->prepare("INSERT INTO flame_measurements (flame_detected, created_at) VALUES (?, NOW())");
    
    if ($stmt) {
        $stmt->bind_param("i", $status);
        if ($stmt->execute()) {
            echo "Sikeres mentés! Állapot: " . $status;
        } else {
            echo "SQL hiba a végrehajtásnál: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "SQL hiba az előkészítésnél: " . $conn->error;
    }
} else {
    echo "Hiba: Nincs status paraméter!";
}

$conn->close();
?>
