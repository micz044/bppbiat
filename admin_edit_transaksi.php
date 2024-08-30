<?php include 'admin_header.php'; ?>
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
<?php
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

// Ambil data jenis benih dan ukuran dari data_harga
$harga_query = "SELECT DISTINCT jenis_benih, ukuran, harga FROM data_harga";
$harga_result = $conn->query($harga_query);

// Simpan data harga dalam array PHP
$harga_data = [];
while ($row = $harga_result->fetch_assoc()) {
    $harga_data[] = $row;
}

// Ambil data jenis benih untuk dropdown
$jenis_benih_query = "SELECT DISTINCT jenis_benih FROM data_harga";
$jenis_benih_result = $conn->query($jenis_benih_query);

if (isset($_POST['update_transaksi'])) {
    $no = $_POST['no'];
    $nama = $_POST['nama'];
    $jenis_benih = $_POST['jenis_benih'];
    $ukuran = $_POST['ukuran'];
    $jumlah_dibeli = $_POST['jumlah_dibeli'];
    $harga = $_POST['harga'];
    $total = $_POST['total'];
    $tanggal_pemesanan = $_POST['tanggal_pemesanan'];
    $status = $_POST['status'];

    // Query untuk update data transaksi
    $sql = "UPDATE transaksi_penjualan SET 
            nama='$nama', 
            jenis_benih='$jenis_benih', 
            ukuran='$ukuran', 
            jumlah_dibeli='$jumlah_dibeli', 
            harga='$harga', 
            total='$total', 
            tanggal_pemesanan='$tanggal_pemesanan', 
            status='$status' 
            WHERE no='$no'";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_transaksi.php?success=edit_tabel_berhasil");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mendapatkan data transaksi berdasarkan id
    $sql = "SELECT * FROM transaksi_penjualan WHERE no = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
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

<div class="main scroll-container">
    <section id="edit_transaksi" class="main-section active">
        <button class="back-button" onclick="window.location.href='admin_transaksi.php'">Kembali</button>
        <br><br>
        <h2>Edit Transaksi Penjualan</h2>
        <form action="admin_edit_transaksi.php" method="POST">
            <input type="hidden" name="no" value="<?php echo $row['no']; ?>">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?php echo $row['nama']; ?>" required>

            <label for="jenis_benih">Jenis Benih:</label>
            <select id="jenis_benih" name="jenis_benih" required>
                <?php while ($jenis_row = $jenis_benih_result->fetch_assoc()) { ?>
                    <option value="<?php echo $jenis_row['jenis_benih']; ?>" <?php echo ($row['jenis_benih'] == $jenis_row['jenis_benih']) ? 'selected' : ''; ?>>
                        <?php echo $jenis_row['jenis_benih']; ?>
                    </option>
                <?php } ?>
            </select>

            <label for="ukuran">Ukuran (cm):</label>
            <select id="ukuran" name="ukuran" required>
                <!-- Options will be populated by JavaScript -->
            </select>

            <label for="harga">Harga:</label>
            <input type="number" id="harga" name="harga" value="<?php echo number_format($row['harga'], 0, ',', '.'); ?>" readonly>

            <label for="jumlah_dibeli">Jumlah Dibeli (kg):</label>
            <input type="number" id="jumlah_dibeli" name="jumlah_dibeli" value="<?php echo $row['jumlah_dibeli']; ?>" required>

            <label for="total">Total (Rp):</label>
            <input type="number" id="total" name="total" value="<?php echo number_format($row['total'], 0, ',', '.'); ?>" readonly>

            <label for="tanggal_pemesanan">Tanggal Pemesanan:</label>
            <input type="date" id="tanggal_pemesanan" name="tanggal_pemesanan" value="<?php echo $row['tanggal_pemesanan']; ?>" required>

            <input type="hidden" name="status" value="belum selesai">

            <button type="submit" name="update_transaksi">Simpan Perubahan</button>
        </form>
    </section>
</div>

<script>
    document.getElementById('jenis_benih').addEventListener('change', updateUkuranOptions);
    document.getElementById('ukuran').addEventListener('change', updateHarga);

    const hargaData = <?php echo json_encode($harga_data); ?>;

    function updateUkuranOptions() {
        const jenisBenih = document.getElementById('jenis_benih').value;
        const ukuranSelect = document.getElementById('ukuran');
        ukuranSelect.innerHTML = '<option value="">Pilih ukuran</option>'; // Reset ukuran options

        const filteredUkuran = hargaData.filter(item => item.jenis_benih === jenisBenih);

        filteredUkuran.forEach(item => {
            const option = document.createElement('option');
            option.value = item.ukuran;
            option.text = item.ukuran;
            ukuranSelect.add(option);
        });

        // Reset harga when jenis benih changes
        document.getElementById('harga').value = '';
    }

    function updateHarga() {
        const jenisBenih = document.getElementById('jenis_benih').value;
        const ukuran = document.getElementById('ukuran').value;
        const hargaInput = document.getElementById('harga');

        if (jenisBenih && ukuran) {
            const hargaItem = hargaData.find(item => item.jenis_benih === jenisBenih && item.ukuran === ukuran);
            if (hargaItem) {
                hargaInput.value = hargaItem.harga;
            }
        }
    }

    // Initialize options on page load
    window.onload = function() {
        updateUkuranOptions();
        updateHarga();
    };
</script>


<script>
    document.getElementById('jumlah_dibeli').addEventListener('input', calculateTotal);
    document.getElementById('harga').addEventListener('input', calculateTotal);

    function calculateTotal() {
        var jumlah = document.getElementById('jumlah_dibeli').value;
        var harga = document.getElementById('harga').value;
        var total = jumlah * harga;
        document.getElementById('total').value = total;
    }
</script>
