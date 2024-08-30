<?php
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = ""; // Kosongkan jika tidak ada password
$dbname = "db_persediaan";

$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fungsi untuk menambah data transaksi penjualan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_transaksi_penjualan'])) {
    $nama = $_POST['nama'];
    $jenis_benih = $_POST['jenis_benih'];
    $ukuran = $_POST['ukuran']; // Menambahkan ukuran
    $jumlah_dibeli = $_POST['jumlah_dibeli'];
    $harga = $_POST['harga'];
    $total = $_POST['total'];
    $tanggal_pemesanan = $_POST['tanggal_pemesanan'];
    $status = $_POST['status'];

    // Mengamankan input dari pengguna
    $nama = stripslashes($nama);
    $jenis_benih = stripslashes($jenis_benih);
    $ukuran = stripslashes($ukuran); // Mengamankan ukuran
    $jumlah_dibeli = stripslashes($jumlah_dibeli);
    $harga = stripslashes($harga);
    $total = stripslashes($total);
    $tanggal_pemesanan = stripslashes($tanggal_pemesanan);
    $status = stripslashes($status);

    $nama = mysqli_real_escape_string($conn, $nama);
    $jenis_benih = mysqli_real_escape_string($conn, $jenis_benih);
    $ukuran = mysqli_real_escape_string($conn, $ukuran); // Mengamankan ukuran
    $jumlah_dibeli = mysqli_real_escape_string($conn, $jumlah_dibeli);
    $harga = mysqli_real_escape_string($conn, $harga);
    $total = mysqli_real_escape_string($conn, $total);
    $tanggal_pemesanan = mysqli_real_escape_string($conn, $tanggal_pemesanan);
    $status = mysqli_real_escape_string($conn, $status);

    // Menghitung nomor urut berikutnya
    $sql_last_no = "SELECT MAX(no) as last_no FROM transaksi_penjualan";
    $result_last_no = $conn->query($sql_last_no);
    $last_no = 0;
    if ($result_last_no->num_rows > 0) {
        $row_last_no = $result_last_no->fetch_assoc();
        $last_no = $row_last_no['last_no'];
    }
    $next_no = $last_no + 1;

    // Query untuk menambah data transaksi penjualan
    $sql_add_transaksi = "INSERT INTO transaksi_penjualan (no, nama, jenis_benih, ukuran, jumlah_dibeli, harga, total, tanggal_pemesanan, status) 
                          VALUES ('$next_no', '$nama', '$jenis_benih', '$ukuran', '$jumlah_dibeli', '$harga', '$total', '$tanggal_pemesanan', '$status')";

    if ($conn->query($sql_add_transaksi) === TRUE) {
        header("Location: admin_transaksi.php?success=proses_transaksi_berhasil");
        exit();
    } else {
        echo "Error: " . $sql_add_transaksi . "<br>" . $conn->error;
    }
}

$conn->close();
?>
