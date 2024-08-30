<?php
header('Content-Type: application/json');

$jenis_benih = $_GET['jenis_benih'];
$ukuran = $_GET['ukuran'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_persediaan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT harga FROM data_harga WHERE jenis_benih = ? AND ukuran = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $jenis_benih, $ukuran);
$stmt->execute();
$result = $stmt->get_result();

$harga = $result->fetch_assoc();
echo json_encode($harga);

$stmt->close();
$conn->close();
?>
