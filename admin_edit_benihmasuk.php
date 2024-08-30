<?php include 'admin_header.php'; ?>

<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_persediaan";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_benih = $_POST['kode_benih'];
    $jenis_benih = $_POST['jenis_benih'];
    $ukuran = $_POST['ukuran'];
    $stok = $_POST['stok'];
    $tanggal_benih_masuk = $_POST['tanggal_benih_masuk'];

    // Mengecek nilai keluar
    $sql_check = "SELECT keluar FROM data_benih_masuk WHERE kode_benih = '$kode_benih'";
    $result_check = $conn->query($sql_check);
    $row_check = $result_check->fetch_assoc();

    if ($row_check['keluar'] == 0) {
        // Memulai transaksi
        $conn->begin_transaction();

        try {
            // Mengupdate data benih masuk
            $sql_update = "UPDATE data_benih_masuk SET jenis_benih = ?, ukuran = ?, stok = ?, tanggal_benih_masuk = ?, jumlah_tersedia = ? WHERE kode_benih = ?";
            $stmt = $conn->prepare($sql_update);
            $jumlah_tersedia = $stok; // Menyesuaikan jumlah_tersedia dengan stok
            $stmt->bind_param("ssssss", $jenis_benih, $ukuran, $stok, $tanggal_benih_masuk, $jumlah_tersedia, $kode_benih);

            if (!$stmt->execute()) {
                throw new Exception("Eksekusi statement data_benih_masuk gagal: " . $stmt->error);
            }
            $stmt->close();

            // Mengambil total jumlah_tersedia dari tabel data_benih_masuk untuk setiap jenis_benih dan ukuran
            $sql_all_benih = "SELECT jenis_benih, ukuran, SUM(jumlah_tersedia) AS total_tersedia FROM data_benih_masuk GROUP BY jenis_benih, ukuran";
            $result_all_benih = $conn->query($sql_all_benih);

            if ($result_all_benih->num_rows > 0) {
                while ($row_all_benih = $result_all_benih->fetch_assoc()) {
                    $jenis_benih_all = $row_all_benih['jenis_benih'];
                    $ukuran_all = $row_all_benih['ukuran'];
                    $total_tersedia_all = $row_all_benih['total_tersedia'];

                    // Memperbarui data_persediaan
                    $stmt_persediaan = $conn->prepare("UPDATE data_persediaan SET stok = ? WHERE jenis_benih = ? AND ukuran = ?");
                    $stmt_persediaan->bind_param("dss", $total_tersedia_all, $jenis_benih_all, $ukuran_all);
                    if (!$stmt_persediaan->execute()) {
                        throw new Exception("Eksekusi statement data_persediaan gagal: " . $stmt_persediaan->error);
                    }
                    $stmt_persediaan->close();
                }
            }

            // Commit transaksi
            $conn->commit();
            header("Location: admin_benihmasuk.php?success=edit_tabelbm_berhasil");
            exit();
        } catch (Exception $e) {
            // Rollback transaksi jika ada kesalahan
            $conn->rollback();
            echo "Transaksi gagal: " . $e->getMessage();
        }
    } else {
        header("Location: admin_benihmasuk.php?error=edit_tabelbm_error");
        exit();
    }
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mendapatkan data berdasarkan kode_benih
    $sql = "SELECT * FROM data_benih_masuk WHERE kode_benih = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Data Benih Masuk</title>
            <style>
                button {
                    background-color: #000000;
                    color: white;
                    padding: 12px 20px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    transition: background-color 0.3s;
                    font-size: 14px;
                }

                button:hover {
                    background-color: #494949;
                }
            </style>
        </head>
        <body>
            <div class="main">
                <button class="back-button" onclick="window.location.href='admin_benihmasuk.php'">Kembali</button>
                <br><br>
                <h2>Edit Data Benih Masuk</h2>
                <form action="admin_edit_benihmasuk.php" method="post">
                    <input type="hidden" name="kode_benih" value="<?php echo htmlspecialchars($row['kode_benih']); ?>">
                    <label for="jenis_benih">Jenis Benih:</label>
                    <input type="text" id="jenis_benih" name="jenis_benih" value="<?php echo htmlspecialchars($row['jenis_benih']); ?>" required>
                    <br>
                    <label for="ukuran">Ukuran:</label>
                    <input type="text" id="ukuran" name="ukuran" value="<?php echo htmlspecialchars($row['ukuran']); ?>" required>
                    <br>
                    <label for="stok">Stok (kg):</label>
                    <input type="number" id="stok" name="stok" step="0.01" value="<?php echo htmlspecialchars($row['stok']); ?>" required>
                    <br>
                    <label for="tanggal_benih_masuk">Tanggal Benih Masuk:</label>
                    <input type="date" id="tanggal_benih_masuk" name="tanggal_benih_masuk" value="<?php echo htmlspecialchars($row['tanggal_benih_masuk']); ?>" required>
                    <br>
                    <button type="submit">Perbarui Data</button>
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Data tidak ditemukan.";
    }
} else {
    echo "Kode Benih tidak diberikan.";
}

$conn->close();
?>
