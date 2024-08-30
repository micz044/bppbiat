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
        padding: 5px 20px;
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
</style>

<div class="main scroll-container">
    <!-- Main Section - Laporan Transaksi Penjualan -->
    <section id="laporan_transaksi" class="main-section active">
        <h2>Laporan Transaksi Penjualan Benih Ikan Belum Selesai</h2>
        <div class="scrollable-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jenis Benih</th>
                        <th>Ukuran</th>
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
                            echo "<tr>
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

            <h2>Laporan Transaksi Penjualan Benih Ikan Sudah Selesai</h2>
            <div class="scrollable-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jenis Benih</th>
                        <th>Ukuran</th>
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
                    $sql = "SELECT * FROM transaksi_penjualan WHERE status = 'selesai'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
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

            <h2>Pilih Bulan dan Tahun untuk Cetak Laporan</h2>

            <form action="admin_cetak_laporantr.php" method="post">
                <label for="bulan">Bulan:</label>
                <input type="month" id="bulan" name="bulan" required>
                <button type="submit" name="cetak_laporan">Cetak Laporan PDF</button>
            </form>
            
            <?php
            if (isset($_GET['error'])){
                $error = $_GET['error'];
                if($error == 'cetak_laporantr_error'){
                    echo "<div class='notification' id='notification'>Tidak ada laporan di bulan ini.</div>";
                }
            }
            ?>

            <h2>Laporan Transaksi Bulanan</h2>
            <div class="scrollable-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Benih</th>
                        <th>Ukuran (Cm)</th>
                        <th>Jumlah Pembelian (Ekor)</th>
                        <th>Total (Rp)</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Informasi koneksi ke database
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "db_persediaan";

                    // Buat koneksi ke database
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Periksa koneksi
                    if ($conn->connect_error) {
                        die("Koneksi gagal: " . $conn->connect_error);
                    }

                    // Query untuk mengambil data dari tabel laporan_transaksi_bulan
                    $sql = "SELECT * FROM laporan_transaksi_bulan";
                    $result = $conn->query($sql);

                    // Cek apakah ada data
                    if ($result->num_rows > 0) {
                        // Loop untuk menampilkan data
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['no'] . "</td>";
                            echo "<td>" . $row['jenis_benih'] . "</td>";
                            echo "<td>" . $row['ukuran'] . " Cm</td>";
                            echo "<td>" . $row['jumlah_dibeli'] . " Ekor</td>";
                            echo "<td>Rp " . number_format($row['total'], 0, ',', '.') . "</td>";
                            echo "<td>" . $row['tanggal'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Tidak ada data yang ditemukan</td></tr>";
                    }

                    // Tutup koneksi
                    $conn->close();
                    ?>
                </tbody>
            </table>
            </div>

            <form method="post" action="AFperbaharuilaporantr_admin.php">
                <button type="submit" name="perbaharui_laporan">Perbaharui Laporan</button>
            </form>

            <?php
            if (isset($_GET['success'])) {
                $success = $_GET['success'];
                if ($success == 'perbaharui_laporantr') {
                    echo "<div class='notification success' id='notification'>Laporan Diperbaharui</div>";
                }
            }
            ?>

            <?php
            if (isset($_GET['error'])){
                $error = $_GET['error'];
                if($error == 'error_perbaharui_laporantr'){
                    echo "<div class='notification' id='notification'>Tidak ada data yang ditemukan di tabel laporan transaksi penjualan sudah selesai.</div>";
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