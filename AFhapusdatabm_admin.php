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

    // Mengecek apakah no yang ingin dihapus ada di tabel dan memeriksa apakah field 'keluar' sudah ada nilainya
    $stmt_check = $conn->prepare("SELECT keluar FROM data_benih_masuk WHERE no = ?");
    if ($stmt_check === false) {
        die("Error dalam prepare statement: " . $conn->error);
    }

    $stmt_check->bind_param("i", $no_to_delete);
    $stmt_check->execute();
    $stmt_check->bind_result($keluar);
    
    if ($stmt_check->fetch()) {
        $stmt_check->close(); // Pastikan untuk menutup statement setelah fetch
        
        if ($keluar > 0) {
            header("Location: admin_benihmasuk.php?error=delete_BM_keluar_error");
            exit();
        }

        // Menghapus data berdasarkan no
        $stmt_delete = $conn->prepare("DELETE FROM data_benih_masuk WHERE no = ?");
        if ($stmt_delete === false) {
            die("Error dalam prepare statement untuk delete: " . $conn->error);
        }
        $stmt_delete->bind_param("i", $no_to_delete);
        
        if ($stmt_delete->execute()) {
            $stmt_delete->close(); // Tutup statement setelah eksekusi

            // Menyusun ulang nomor urut
            $result = $conn->query("SELECT no FROM data_benih_masuk ORDER BY no ASC");
            if ($result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    $current_no = $row['no'];
                    $stmt_update = $conn->prepare("UPDATE data_benih_masuk SET no = ? WHERE no = ?");
                    $stmt_update->bind_param("ii", $no, $current_no);
                    $stmt_update->execute();
                    $stmt_update->close(); // Tutup statement setiap kali selesai eksekusi
                    $no++;
                }
            }

            // Reset auto increment secara manual jika dibutuhkan
            $conn->query("ALTER TABLE data_benih_masuk AUTO_INCREMENT = 1");

            header("Location: admin_benihmasuk.php?success=delete_BM_success");
            exit();
            
        } else {
            echo "<div class='notification error'>Error: " . $stmt_delete->error . "</div>";
        }
    } else {
        $stmt_check->close(); // Tutup statement jika no tidak ditemukan
        header("Location: admin_benihmasuk.php?error=delete_BM_error");
        exit();
    }

    $stmt_check->close(); // Tutup statement jika no ditemukan
}

$conn->close();

?>
