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

// Memulai transaksi
$conn->begin_transaction();

try {
    // Mengambil semua kombinasi jenis_benih dan ukuran yang ada di tabel data_persediaan
    $result_persediaan = $conn->query("SELECT jenis_benih, ukuran FROM data_persediaan");

    if ($result_persediaan->num_rows > 0) {
        while ($row_persediaan = $result_persediaan->fetch_assoc()) {
            $jenis_benih = $row_persediaan['jenis_benih'];
            $ukuran = $row_persediaan['ukuran'];

            // Mengambil total jumlah_tersedia dari tabel data_benih_masuk berdasarkan jenis_benih dan ukuran
            $stmt_benih_masuk = $conn->prepare("SELECT SUM(jumlah_tersedia) AS total_tersedia FROM data_benih_masuk WHERE jenis_benih = ? AND ukuran = ?");
            $stmt_benih_masuk->bind_param("ss", $jenis_benih, $ukuran); // 'ss' untuk string
            $stmt_benih_masuk->execute();
            $result_benih_masuk = $stmt_benih_masuk->get_result();
            $row_benih_masuk = $result_benih_masuk->fetch_assoc();
            $total_tersedia = $row_benih_masuk['total_tersedia'] ?: 0;

            // Memperbarui data_persediaan
            $stmt_persediaan = $conn->prepare("UPDATE data_persediaan SET stok = ? WHERE jenis_benih = ? AND ukuran = ?");
            $stmt_persediaan->bind_param("dss", $total_tersedia, $jenis_benih, $ukuran); // 'dss' untuk double, string, string
            if (!$stmt_persediaan->execute()) {
                throw new Exception("Eksekusi statement data_persediaan gagal: " . $stmt_persediaan->error);
            }
            $stmt_persediaan->close();
        }
    }

    // Commit transaksi
    $conn->commit();

    // Redirect ke halaman admin_dashboard.php jika berhasil
    header("Location: admin_persediaan.php?success=data_berhasil_diperbaharui");
    exit();
} catch (Exception $e) {
    // Rollback transaksi jika ada kesalahan
    $conn->rollback();
    echo "Transaksi gagal: " . $e->getMessage();
}

// Tutup koneksi
$conn->close();
?>
