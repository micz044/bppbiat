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

if (isset($_POST['submit_add_jenis_benih'])) {
    $jenis_benih = $_POST['jenis_benih'];
    $ukuran = $_POST['ukuran']; // Menangkap nilai ukuran dari form

    // Menyiapkan dan menjalankan pernyataan SQL untuk menambahkan data
    $stmt = $conn->prepare("INSERT INTO data_persediaan (jenis_benih, ukuran, stok) VALUES (?, ?, 0)");
    $stmt->bind_param("ss", $jenis_benih, $ukuran); // Bind parameter ukuran

    if ($stmt->execute()) {
        header("Location: admin_persediaan.php?success=data_jenis_benih_ditambah");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
