<?php
// Hibakeresés - ha valami baj van, írja ki
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- Adatbázis kapcsolat ---
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "flame_sensor";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Hiba a kapcsolódáskor: " . $conn->connect_error);
}

// Adatok lekérése (utolsó 20)
$sql = "SELECT id, flame_detected, created_at FROM flame_measurements ORDER BY id DESC LIMIT 20";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lángérzékelő Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-fire { background-color: #f8d7da !important; color: #842029 !important; font-weight: bold; }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow">
        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <h4 class="mb-0">🔥 Lángérzékelő Előzmények</h4>
            <button onclick="location.reload()" class="btn btn-sm btn-outline-light">Frissítés</button>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-secondary">
                    <tr>
                        <th>ID</th>
                        <th>Állapot</th>
                        <th>Időpont</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $is_fire = ($row["flame_detected"] == 1);
                            $class = $is_fire ? 'table-fire' : '';
                            $status = $is_fire ? '⚠️ TŰZ ÉSZLELVE!' : '✅ Rendben';

                            echo "<tr class='$class'>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $status . "</td>";
                            echo "<td>" . $row["created_at"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>Nincs rögzített adat.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
<?php $conn->close(); ?>
