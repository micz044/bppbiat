<?php
include 'admin_header.php';

// Query untuk mengambil data persediaan berdasarkan nomor yang dipilih
$servername = "localhost";
$username = "root";
$password = ""; // Kosongkan jika tidak ada password
$dbname = "db_persediaan";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['update_transaksi'])) {
    $no = $_POST['no'];
    $jenis_benih = $_POST['jenis_benih'];
    $ukuran = $_POST['ukuran'];
    $stok = $_POST['stok'];

    // Query untuk update data persediaan
    $sql_persediaan = "UPDATE data_persediaan SET 
            jenis_benih='$jenis_benih',  
            ukuran='$ukuran',
            stok='$stok' 
            WHERE no='$no'";

    if ($conn->query($sql_persediaan) === TRUE) {
        header("Location: admin_persediaan.php?success=edit_tabelpr_berhasil");
        exit();
    } else {
        echo "Error: " . $sql_persediaan . "<br>" . $conn->error;
    }
} else if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data persediaan berdasarkan nomor yang dipilih
    $sql_persediaan = "SELECT * FROM data_persediaan WHERE no = '$id'";
    $result_persediaan = $conn->query($sql_persediaan);

    if ($result_persediaan->num_rows > 0) {
        $row_persediaan = $result_persediaan->fetch_assoc();
    } else {
        echo "Data tidak ditemukan.";
        exit;
    }
} else {
    echo "ID tidak ditemukan.";
    exit;
}

$conn->close();
?>

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

<div class="main">
    <button class="back-button" onclick="window.location.href='admin_persediaan.php'">Kembali</button>
    <br><br>
    <h2>Edit Data Persediaan Benih Ikan</h2>
    <form action="admin_edit_persediaan.php" method="post">
        <input type="hidden" name="no" value="<?php echo $row_persediaan['no']; ?>">
        <label for="jenis_benih">Jenis Benih:</label>
        <input type="text" id="jenis_benih" name="jenis_benih" value="<?php echo $row_persediaan['jenis_benih']; ?>" required><br><br>
        <label for="ukuran">Ukuran:</label>
        <input type="text" id="ukuran" name="ukuran" value="<?php echo $row_persediaan['ukuran']; ?>" required><br><br>
        <label for="stok">Stok:</label>
        <input type="number" id="stok" name="stok" value="<?php echo $row_persediaan['stok']; ?>" readonly><br><br>
        <button type="submit" name="update_transaksi">Simpan Perubahan</button>
    </form>
</div>
