<?php
header('Content-Type: application/json');

$jenis_benih = $_GET['jenis_benih'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_persediaan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT DISTINCT ukuran FROM data_persediaan WHERE jenis_benih = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $jenis_benih);
$stmt->execute();
$result = $stmt->get_result();

$ukuran = array();
while($row = $result->fetch_assoc()) {
    $ukuran[] = $row;
}

echo json_encode($ukuran);

$stmt->close();
$conn->close();
?>
