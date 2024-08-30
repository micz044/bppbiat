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

    // Periksa apakah nomor ada di tabel data_benih_masuk
    $stmt_check = $conn->prepare("SELECT COUNT(*) FROM data_harga WHERE id = ?");
    $stmt_check->bind_param("i", $no_to_delete);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
        // Menghapus data dari data_persediaan
        $stmt_delete = $conn->prepare("DELETE FROM data_harga WHERE id = ?");
        $stmt_delete->bind_param("i", $no_to_delete);
        if ($stmt_delete->execute()) {

            // Menyusun ulang nomor urut
            $result = $conn->query("SELECT id FROM data_harga ORDER BY id ASC");
            if ($result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    $current_no = $row['id'];
                    $stmt_update = $conn->prepare("UPDATE data_harga SET id = ? WHERE id = ?");
                    $stmt_update->bind_param("ii", $no, $current_no);
                    $stmt_update->execute();
                    $no++;
                }
            }
            
            // Reset auto increment secara manual jika dibutuhkan
            $conn->query("ALTER TABLE data_harga AUTO_INCREMENT = 1");

            header("Location: kepala_dataharga.php?success=delete_dataharga_success");
            exit();
            
        } else {
            echo "<div class='notification error'>Error: " . $stmt_delete->error . "</div>";
        }
        $stmt_delete->close();
    } else {
        header("Location: kepala_dataharga.php?error=delete_dataharga_error");
        exit();
    }
}

$conn->close();
?>
