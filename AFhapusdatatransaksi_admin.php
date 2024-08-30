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

if (isset($_POST['submit_delete'])) {
    $no_to_delete = $_POST['no_to_delete'];

    // Periksa status dari transaksi_penjualan berdasarkan nomor
    $stmt_check = $conn->prepare("SELECT status FROM transaksi_penjualan WHERE no = ?");
    $stmt_check->bind_param("i", $no_to_delete);
    $stmt_check->execute();
    $stmt_check->bind_result($status);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($status == 'belum selesai') {
        // Menghapus data dari transaksi_penjualan jika status "belum selesai"
        $stmt_delete = $conn->prepare("DELETE FROM transaksi_penjualan WHERE no = ? AND status = 'belum selesai'");
        $stmt_delete->bind_param("i", $no_to_delete);
        if ($stmt_delete->execute()) {

            // Menyusun ulang nomor urut
            $result = $conn->query("SELECT no FROM transaksi_penjualan ORDER BY no ASC");
            if ($result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    $current_no = $row['no'];
                    $stmt_update = $conn->prepare("UPDATE transaksi_penjualan SET no = ? WHERE no = ?");
                    $stmt_update->bind_param("ii", $no, $current_no);
                    $stmt_update->execute();
                    $no++;
                }
            }
            
            // Reset auto increment secara manual jika dibutuhkan
            $conn->query("ALTER TABLE transaksi_penjualan AUTO_INCREMENT = 1");

            header("Location: admin_transaksi.php?success=delete_transaksi_success");
            exit();
            
        } else {
            echo "<div class='notification error'>Error: " . $stmt_delete->error . "</div>";
        }
        $stmt_delete->close();

    } elseif ($status == 'selesai') {
        // Jika status "selesai", tampilkan notifikasi dan jangan hapus data
        header("Location: admin_transaksi.php?error=delete_transaksi_error_status_selesai");
        exit();

    } else {
        // Jika status tidak ditemukan, misalnya nomor tidak ada
        header("Location: admin_transaksi.php?error=delete_transaksi_error");
        exit();
    }
}

$conn->close();
?>
