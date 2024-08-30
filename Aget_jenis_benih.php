<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_persediaan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT DISTINCT jenis_benih FROM data_persediaan";
$result = $conn->query($sql);

$jenisBenih = array();
while($row = $result->fetch_assoc()) {
    $jenisBenih[] = $row;
}

echo json_encode($jenisBenih);

$conn->close();
?>
