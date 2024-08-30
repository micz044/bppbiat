<?php
// Koneksi ke database
$servername = "localhost";
$username = "root"; // sesuaikan dengan username database Anda
$password = ""; // sesuaikan dengan password database Anda
$dbname = "db_persediaan";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_POST['submit_add_data_harga'])) {
    $jenis_benih = $_POST['jenis_benih'];
    $ukuran = $_POST['ukuran'];
    $harga = $_POST['harga'];

    // Menyiapkan dan menjalankan pernyataan SQL untuk menambahkan data
    $stmt = $conn->prepare("INSERT INTO data_harga (jenis_benih, ukuran, harga) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $jenis_benih, $ukuran, $harga); // Bind parameter ukuran

    if ($stmt->execute()) {
        header("Location: kepala_dataharga.php?success=berhasil_tambah");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
