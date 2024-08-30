<?php include 'admin_header.php'; ?>

<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f2f5;
    }

    .header {
        background-color: #343a40;
        color: #fff;
        padding: 10px 20px;
        display: flex;
        align-items: center;
    }

    .header img {
        height: 50px;
        margin-right: 20px;
    }

    .header h1 {
        margin: 0;
    }

    .nav {
        background-color: #343a40;
        overflow: hidden;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .nav ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex;
    }

    .nav ul li {
        flex: 1;
    }

    .nav ul li a {
        display: block;
        padding: 14px 20px;
        color: white;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .nav ul li a:hover {
        background-color: #495057;
    }

    .main {
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 20px;
    }

    .main-section {
        display: none;
    }

    .main-section.active {
        display: block;
    }

    .scrollable-table {
        max-height: 500px; /* Ubah sesuai kebutuhan */
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #dee2e6;
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #f8f9fa;
        color: #212529;
        font-weight: bold;
        text-transform: uppercase;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    form {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    form h2, form h3 {
        margin-top: 0;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #495057;
    }

    input[type="text"], input[type="number"], input[type="date"], select {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 14px;
    }

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

    .scroll-container {
        max-height: 500px;
        overflow-y: auto;
    }

    .scroll-container::-webkit-scrollbar {
        width: 8px;
    }

    .scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .scroll-container::-webkit-scrollbar-thumb {
        background: #888;
    }

    .scroll-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #f44336; /* Red untuk error */
        color: white;
        padding: 16px;
        z-index: 1000;
        border-radius: 5px;
        display: none;
        opacity: 0;
        transition: opacity 0.5s;
    }
    .notification.success {
        background-color: #4CAF50; /* Green untuk sukses */
    }
    .notification.show {
        display: block;
        opacity: 1;
    }
    .main-section {
        padding: 10px 20px;
        text-align: left;
        animation: fadeIn 1s ease-in-out; /* Animasi fadeIn */
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .main-section h2 {
        font-size: 28px;
        margin-bottom: 20px;
    }

    .main-section p {
        font-size: 16px;
        margin-bottom: 20px;
    }

    #transaksiTable tbody tr:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        background-color: #f0f0f0; /* Warna latar belakang yang berbeda saat dihover */
    }
</style>

<div class="main scroll-container">
    <section id="transaksi" class="main-section active">
        <h2>Transaksi Penjualan Benih Ikan</h2>
        <div class="scrollable-table">
            <table id="transaksiTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jenis Benih</th>
                        <th>Ukuran (Cm)</th>
                        <th>Jumlah Pembelian (Ekor)</th>
                        <th>Harga (Rp)</th>
                        <th>Total (Rp)</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
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

                    // Query untuk mendapatkan data dari tabel transaksi_penjualan
                    $sql = "SELECT * FROM transaksi_penjualan WHERE status = 'belum selesai'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr data-id='" . $row['no'] . "' data-nama='" . $row['nama'] . "'>
                                <td>" . $row['no'] . "</td>
                                <td>" . $row['nama'] . "</td>
                                <td>" . $row['jenis_benih'] . "</td>
                                <td>" . $row['ukuran'] . " Cm</td>
                                <td>" . $row['jumlah_dibeli'] . " Ekor</td>
                                <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
                                <td>Rp " . number_format($row['total'], 0, ',', '.') . "</td>
                                <td>" . $row['tanggal_pemesanan'] . "</td>
                                <td>" . $row['status'] . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>Tidak ada data transaksi penjualan</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        
        <form action="AFprosestransaksi_admin.php" method="POST">
            <h2>Transaksi Penjualan</h2>
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required>

            <label for="jenis_benih">Jenis Benih:</label>
            <select id="jenis_benih" name="jenis_benih" required>
                <option value="">Pilih Jenis Benih</option>
                <!-- Options akan diisi oleh JavaScript -->
            </select><br><br>

            <label for="ukuran">Ukuran:</label>
            <select id="ukuran" name="ukuran" required>
                <option value="">Pilih Ukuran</option>
                <!-- Options akan diisi oleh JavaScript -->
            </select><br><br>

            <label for="jumlah_dibeli">Jumlah Dibeli (kg):</label>
            <input type="number" id="jumlah_dibeli" name="jumlah_dibeli" required>

            <label for="harga">Harga:</label>
            <input type="text" id="harga" name="harga" readonly>

            <label for="total">Total (Rp):</label>
            <input type="number" id="total" name="total" readonly>

            <label for="tanggal_pemesanan">Tanggal Pemesanan:</label>
            <input type="date" id="tanggal_pemesanan" name="tanggal_pemesanan" required>

            <input type="hidden" name="status" value="belum selesai">

            <button type="submit" name="submit_transaksi_penjualan">Tambah Transaksi</button>
        </form>

        <?php
            if (isset($_GET['success'])) {
                $success = $_GET['success'];
                if ($success == 'proses_transaksi_berhasil') {
                    echo "<div class='notification success' id='notification'>Pemesanan Berhasil</div>";
                }
            }
        ?>

        <?php
            if (isset($_GET['error'])) {
                $error = $_GET['error'];
                $nama = isset($_GET['nama']) ? htmlspecialchars($_GET['nama']) : '';
                if ($error == 'stok_tidak_cukup') {
                    echo "<div class='notification' id='notification'>Stok tidak mencukupi untuk transaksi atas nama: $nama</div>";
                } elseif ($error == 'transaksi_tidak_ditemukan') {
                    echo "<div class='notification' id='notification'>Tidak ada transaksi yang ditemukan untuk nama ini.</div>";
                }
            } elseif (isset($_GET['success'])) {
                $success = $_GET['success'];
                $nama = isset($_GET['nama']) ? htmlspecialchars($_GET['nama']) : '';
                if ($success == 'transaksi_berhasil') {
                    echo "<div class='notification success' id='notification'>Transaksi berhasil atas nama: $nama</div>";
                }
            }
        ?>

        <script>
            window.onload = function() {
                var notification = document.getElementById('notification');
                    if (notification) {
                        notification.classList.add('show');
                        setTimeout(function() {
                            notification.classList.remove('show');
                        }, 5000); // Notifikasi akan hilang setelah 5 detik
                    }
            };
         </script>
        
        <!-- Script untuk mengambil data dan interaksi dinamis -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                loadJenisBenih();
                
                document.getElementById('jenis_benih').addEventListener('change', function() {
                    loadUkuran(this.value);
                });

                document.getElementById('ukuran').addEventListener('change', function() {
                    loadHarga(document.getElementById('jenis_benih').value, this.value);
                });

                function loadJenisBenih() {
                    fetch('Aget_jenis_benih.php')
                        .then(response => response.json())
                        .then(data => {
                            let jenisBenihSelect = document.getElementById('jenis_benih');
                            jenisBenihSelect.innerHTML = '<option value="">Pilih Jenis Benih</option>';
                            data.forEach(item => {
                                let option = document.createElement('option');
                                option.value = item.jenis_benih;
                                option.textContent = item.jenis_benih;
                                jenisBenihSelect.appendChild(option);
                            });
                        });
                }

                function loadUkuran(jenisBenih) {
                    fetch('Aget_ukuran.php?jenis_benih=' + encodeURIComponent(jenisBenih))
                        .then(response => response.json())
                        .then(data => {
                            let ukuranSelect = document.getElementById('ukuran');
                            ukuranSelect.innerHTML = '<option value="">Pilih Ukuran</option>';
                            data.forEach(item => {
                                let option = document.createElement('option');
                                option.value = item.ukuran;
                                option.textContent = item.ukuran;
                                ukuranSelect.appendChild(option);
                            });
                        });
                }

                function loadHarga(jenisBenih, ukuran) {
                    fetch('Aget_harga.php?jenis_benih=' + encodeURIComponent(jenisBenih) + '&ukuran=' + encodeURIComponent(ukuran))
                        .then(response => response.json())
                        .then(data => {
                            if (data) {
                                document.getElementById('harga').value = data.harga;
                                calculateTotal();
                            }
                        });
                }

                function calculateTotal() {
                    let jumlah = document.getElementById('jumlah_dibeli').value;
                    let harga = document.getElementById('harga').value;
                    let total = jumlah * harga;
                    document.getElementById('total').value = total;
                }

                document.getElementById('jumlah_dibeli').addEventListener('input', calculateTotal);
            });
        </script>

            <!-- Form untuk mengambil data transaksi penjualan -->
            <form id="transaksiForm" action="AFtransaksi_admin.php" method="POST">
                <h3>Masukkan Nama Untuk Melakukan Proses Transaksi</h3>
                <input type="text" id="nama" name="nama" required>
                <button type="submit" name="submit_transaksi">Proses Transaksi</button>
            </form>

            <!-- Form untuk menghapus data tertentu di data_persediaan -->
            <form method="POST" action="AFhapusdatatransaksi_admin.php">
                <label for="no_to_delete">Nomor Data yang Akan Dihapus:</label>
                <input type="number" id="no_to_delete" name="no_to_delete" required>
                <button type="submit" name="submit_delete" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus Data</button>
            </form>

            <?php
            if (isset($_GET['success'])) {
                $success = $_GET['success'];
                if ($success == 'delete_transaksi_success') {
                    echo "<div class='notification success' id='notification'>Data berhasil dihapus.</div>";
                }
            }elseif (isset($_GET['error'])){
                $error = $_GET['error'];
                if($error == 'delete_transaksi_error'){
                    echo "<div class='notification' id='notification'>Tidak ada nomor yang ditemukan di tabel ini.</div>";
                }
            }
            ?>

            <?php
                if (isset($_GET['error'])) {
                    $success = $_GET['error'];
                    if ($success == 'delete_transaksi_error_status_selesai') {
                        echo "<div class='notification' id='notification'>Tidak bisa menghapus data karena status sudah selesai</div>";
                    }
                }
            ?>

    </section>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Memunculkan elemen dengan animasi fadeIn
        var mainSections = document.querySelectorAll('.main-section');
        mainSections.forEach(function(section) {
            section.style.animationPlayState = 'running';
        });
    });
</script>

<?php
    if (isset($_GET['success'])) {
        $success = $_GET['success'];
        if ($success == 'edit_tabel_berhasil') {
            echo "<div class='notification success' id='notification'>Berhasil Mengedit</div>";
        }
    }
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tableRows = document.querySelectorAll('#transaksiTable tbody tr');
        var notification = document.getElementById('notification');

        tableRows.forEach(function(row) {
            row.addEventListener('click', function() {
                var nama = this.getAttribute('data-nama');
                var id = this.getAttribute('data-id');
                var userConfirmed = confirm('Apakah Anda ingin mengedit atas nama ' + nama + '?');

                if (userConfirmed) {
                    window.location.href = 'admin_edit_transaksi.php?id=' + id;
                }
            });
        });
    });
</script>