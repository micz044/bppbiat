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

if (isset($_POST['submit_add_benih'])) {
    $jenis_benih = $_POST['jenis_benih'];
    $ukuran = $_POST['ukuran'];
    $tanggal_benih_masuk = $_POST['tanggal_benih_masuk'];
    $jumlah_tersedia = $_POST['jumlah_tersedia'];

    // Mendapatkan nilai kode_benih baru berdasarkan kode_benih terakhir
    $result = $conn->query("SELECT kode_benih FROM data_benih_masuk ORDER BY CAST(SUBSTR(kode_benih, 3) AS UNSIGNED) DESC LIMIT 1");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_kode_benih = $row['kode_benih'];
        $last_no = (int) substr($last_kode_benih, 2); // Mengambil nomor dari kode benih terakhir
        $new_no = $last_no + 1;
    } else {
        $new_no = 1; // Jika tidak ada data, mulai dari BM1
    }
    $kode_benih = 'BM' . $new_no; // Membuat kode benih baru

    // Menyiapkan dan menjalankan pernyataan SQL untuk menambahkan data ke data_benih_masuk
    $stmt_benih_masuk = $conn->prepare("INSERT INTO data_benih_masuk (kode_benih, jenis_benih, ukuran, jumlah_tersedia, stok, tanggal_benih_masuk, keluar) VALUES (?, ?, ?, ?, ?, ?, 0)"); // keluar diberi nilai 0
    if ($stmt_benih_masuk === false) {
        die('Prepare Error: ' . htmlspecialchars($conn->error));
    }
    
    $stmt_benih_masuk->bind_param("sssdds", $kode_benih, $jenis_benih, $ukuran, $jumlah_tersedia, $jumlah_tersedia, $tanggal_benih_masuk);

    if ($stmt_benih_masuk->execute()) {
        // Mengatur ulang nomor urut di data_benih_masuk
        $conn->query("SET @num := 0;");
        $conn->query("UPDATE data_benih_masuk SET no = (@num := @num + 1) ORDER BY no ASC;");

        // Mengupdate stok di tabel data_persediaan berdasarkan jenis_benih dan ukuran
        $stmt_total_tersedia = $conn->prepare("SELECT SUM(jumlah_tersedia) AS total_tersedia FROM data_benih_masuk WHERE jenis_benih = ? AND ukuran = ?");
        $stmt_total_tersedia->bind_param("ss", $jenis_benih, $ukuran);
        $stmt_total_tersedia->execute();
        $result_total_tersedia = $stmt_total_tersedia->get_result();
        $row_total_tersedia = $result_total_tersedia->fetch_assoc();
        $total_tersedia = $row_total_tersedia['total_tersedia'] ?: 0;
        $stmt_total_tersedia->close();

        // Memperbarui data_persediaan berdasarkan jenis_benih dan ukuran
        $stmt_persediaan = $conn->prepare("UPDATE data_persediaan SET stok = ? WHERE jenis_benih = ? AND ukuran = ?");
        if ($stmt_persediaan === false) {
            die('Prepare Error: ' . htmlspecialchars($conn->error));
        }
        
        $stmt_persediaan->bind_param("dss", $total_tersedia, $jenis_benih, $ukuran);
        if (!$stmt_persediaan->execute()) {
            echo "Error: " . $stmt_persediaan->error;
        }
        $stmt_persediaan->close();

        header("Location: admin_benihmasuk.php?success=data_BM_berhasil_ditambahkan");
        exit();
    } else {
        echo "Error: " . $stmt_benih_masuk->error;
    }

    $stmt_benih_masuk->close();
}

$conn->close();
?>
