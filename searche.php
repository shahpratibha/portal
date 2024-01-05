<?php




// Connect to PostgreSQL
$conn = pg_connect("host =localhost port = 5432 dbname = postgres user = postgres password = 123");

if (!$conn) {
    die("Failed to connect to the database");
}

$userInput = isset($_POST['search2']) ? $_POST['search2'] : '';
// echo "User Input: " . $userInput;

if (!empty($userInput)) {
    $query1 = "SELECT 
                    Taluka.TalukaName,
                    Village.VillageName,
                    Owner.OwnerName,
                    Gut.GutNumber,
                    Owner.area
                FROM 
                    Gut
                    JOIN 
                    Village ON Gut.VillageID = Village.VillageID
                    JOIN 
                    Taluka ON Gut.TalukaID = Taluka.TalukaID
                    JOIN 
                    OwnerGut ON Gut.GutID = OwnerGut.GutID
                    JOIN 
                    Owner ON OwnerGut.OwnerID = Owner.OwnerID
                WHERE 
                    Owner.OwnerName ILIKE '%$userInput%'";





$result12 = pg_query($conn, $query1);

if (!$result12) {
    die("Query execution failed");
}

$dataa = array();

if ($result12) {


    while ($row = pg_fetch_assoc($result12)) {
        $dataa[] = $row;
    }
}








pg_close($conn);
header('Content-Type: application/json');

echo json_encode($dataa);
exit(); 
}
?>