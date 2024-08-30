<?php
include 'kepala_header.php';

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
    $id = $_POST['id'];
    $jenis_benih = $_POST['jenis_benih'];
    $ukuran = $_POST['ukuran'];
    $harga = $_POST['harga'];

    // Query untuk update data harga
    $sql_dataharga = "UPDATE data_harga SET 
            jenis_benih='$jenis_benih',  
            ukuran='$ukuran',
            harga='$harga' 
            WHERE id='$id'";

    if ($conn->query($sql_dataharga) === TRUE) {
        header("Location: kepala_dataharga.php?success=edit_tabeldh_berhasil");
        exit();
    } else {
        echo "Error: " . $sql_dataharga . "<br>" . $conn->error;
    }
} else if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data harga berdasarkan nomor yang dipilih
    $sql_dataharga = "SELECT * FROM data_harga WHERE id = '$id'";
    $result_dataharga = $conn->query($sql_dataharga);

    if ($result_dataharga->num_rows > 0) {
        $row_dataharga = $result_dataharga->fetch_assoc();
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
    <button class="back-button" onclick="window.location.href='kepala_dataharga.php'">Kembali</button>
    <br><br>
    <h2>Edit Data Persediaan Benih Ikan</h2>
    <form action="kepala_edit_dataharga.php" method="post">
        <input type="hidden" name="id" value="<?php echo $row_dataharga['id']; ?>">
        <label for="jenis_benih">Jenis Benih:</label>
        <input type="text" id="jenis_benih" name="jenis_benih" value="<?php echo $row_dataharga['jenis_benih']; ?>" required><br><br>
        <label for="ukuran">Ukuran:</label>
        <input type="text" id="ukuran" name="ukuran" value="<?php echo $row_dataharga['ukuran']; ?>" required><br><br>
        <label for="harga">Harga:</label>
        <input type="number" id="harga" name="harga" value="<?php echo $row_dataharga['harga']; ?>" required><br><br>
        <button type="submit" name="update_transaksi">Simpan Perubahan</button>
    </form>
</div>
