
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to PostgreSQL
    $conn = pg_connect("host =database-1.c01x1jtcm1ms.ap-south-1.rds.amazonaws.com port = 5432 dbname = postgres user = postgres password = anup12345");

    if (!$conn) {
        die("Failed to connect to the database");
    }

    // Fetch owner names based on GutNumber, VillageName, and TalukaName
    $gutNumber = $_POST['gutNumber'];
    $villageName = $_POST['villageName'];
    $talukaName = $_POST['talukaName'];

    $query = "SELECT Owner.OwnerName,
                    Owner.area
            FROM Owner
            JOIN OwnerGut ON Owner.OwnerID = OwnerGut.OwnerID
            JOIN Gut ON OwnerGut.GutID = Gut.GutID
            JOIN Village ON Gut.VillageID = Village.VillageID
            JOIN Taluka ON Gut.TalukaID = Taluka.TalukaID
            WHERE Gut.GutNumber = '$gutNumber'
                AND Village.VillageName = '$villageName'
                AND Taluka.TalukaName = '$talukaName'";

    $result = pg_query($conn, $query);

    if (!$result) {
        die("Query execution failed");
    }

$data = array();

while ($row = pg_fetch_assoc($result)) {
$data[] = $row;
}

pg_close($conn);

header('Content-Type: application/json');

echo json_encode($data);
exit(); // Stop further execution of HTML/JavaScript code
}
?>