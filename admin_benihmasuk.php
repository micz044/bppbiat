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
    <!-- Main Section - Data Benih Masuk -->
    <section id="benih_masuk" class="main-section active">
            <h2>Data Produksi Benih</h2>
            <div class="scrollable-table">
            <table id="transaksiTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Benih</th>
                        <th>Jenis Benih</th>
                        <th>Ukuran (Cm)</th>
                        <th>Sisa (Ekor)</th>
                        <th>Keluar (Ekor)</th>
                        <th>Jumlah Benih Masuk (Ekor)</th>
                        <th>Tanggal Pendederan</th>
                    </tr>
                </thead>
                <tbody>
                <?php
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

                    // Query untuk mengambil data benih masuk yang memiliki jumlah tersedia dan tanggal benih masuk tidak kosong
                    $sql_benih_masuk = "SELECT no, kode_benih, jenis_benih, ukuran, jumlah_tersedia, keluar, stok, tanggal_benih_masuk FROM data_benih_masuk 
                    WHERE jumlah_tersedia IS NOT NULL AND tanggal_benih_masuk IS NOT NULL";
                    $result_benih_masuk = $conn->query($sql_benih_masuk);

                    if ($result_benih_masuk->num_rows > 0) {
                    while($row_benih_masuk = $result_benih_masuk->fetch_assoc()) {
                    echo "<tr data-kode-benih='".$row_benih_masuk['kode_benih']."'>";
                    echo "<td>" . $row_benih_masuk['no'] . "</td>";
                    echo "<td>" . $row_benih_masuk['kode_benih'] . "</td>";
                    echo "<td>" . $row_benih_masuk['jenis_benih'] . "</td>";
                    echo "<td>" . $row_benih_masuk['ukuran'] . " Cm</td>";
                    echo "<td>" . $row_benih_masuk['jumlah_tersedia'] . " Ekor</td>";
                    echo "<td>" . $row_benih_masuk['keluar'] . " Ekor</td>";
                    echo "<td>" . $row_benih_masuk['stok'] . " Ekor</td>";
                    echo "<td>" . $row_benih_masuk['tanggal_benih_masuk'] . "</td>";
                    echo "</tr>";
                    }
                    } else {
                    echo "<tr><td colspan='5'>Tidak ada data benih masuk yang memiliki jumlah tersedia dan tanggal benih masuk.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
            </div>

            <form action="AFtambahdatabm_admin.php" method="post">
                <h3>Data Produksi Benih</h3>
                <label for="jenis_benih">Jenis Benih:</label>
                <input type="text" id="jenis_benih" name="jenis_benih" required>
                <br>
                <label for="ukuran">Ukuran:</label>
                <input type="text" id="ukuran" name="ukuran" required>
                <br>
                <label for="tanggal_benih_masuk">Tanggal Benih Masuk:</label>
                <input type="date" id="tanggal_benih_masuk" name="tanggal_benih_masuk" required>
                <br>
                <label for="jumlah_tersedia">Jumlah Tersedia:</label>
                <input type="number" id="jumlah_tersedia" name="jumlah_tersedia" required>
                <br>
                <button type="submit" name="submit_add_benih">Tambah</button>
            </form>

            <?php
            if (isset($_GET['success'])) {
                $success = $_GET['success'];
                if ($success == 'data_BM_berhasil_ditambahkan') {
                    echo "<div class='notification success' id='notification'>Data berhasil ditambah.</div>";
                }
            }
            ?>

            <!-- Form untuk menghapus data tertentu di data_persediaan -->
            <form method="POST" action="AFhapusdatabm_admin.php">
                <label for="no_to_delete">Nomor Data yang Akan Dihapus:</label>
                <input type="number" id="no_to_delete" name="no_to_delete" required>
                <button type="submit" name="submit_delete" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus Data</button>
            </form>

            <?php
            if (isset($_GET['success'])) {
                $success = $_GET['success'];
                if ($success == 'delete_BM_success') {
                    echo "<div class='notification success' id='notification'>Data berhasil dihapus.</div>";
                }
            }elseif (isset($_GET['error'])){
                $error = $_GET['error'];
                if($error == 'delete_BM_error'){
                    echo "<div class='notification' id='notification'>Tidak ada nomor yang ditemukan di tabel ini.</div>";
                }
            }
            ?>

            <?php
            if (isset($_GET['error'])) {
                $success = $_GET['error'];
                if ($success == 'delete_BM_keluar_error') {
                    echo "<div class='notification' id='notification'>Tidak bisa menghapus data karena sudah ada benih yang keluar.</div>";
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

<?php
    if (isset($_GET['success'])) {
        $success = $_GET['success'];
        if ($success == 'edit_tabelbm_berhasil') {
            echo "<div class='notification success' id='notification'>Berhasil Mengedit</div>";
        }
    }elseif (isset($_GET['error'])){
        $error = $_GET['error'];
        if($error == 'edit_tabelbm_error'){
            echo "<div class='notification' id='notification'>Data tidak bisa di edit karena stok sudah berkurang.</div>";
        }
    }
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tableRows = document.querySelectorAll('#transaksiTable tbody tr');
        var notification = document.getElementById('notification');

        tableRows.forEach(function(row) {
            row.addEventListener('click', function() {
                var id = this.getAttribute('data-kode-benih');
                var userConfirmed = confirm('Apakah Anda ingin mengedit kode benih ' + id + '?');

                if (userConfirmed) {
                    window.location.href = 'admin_edit_benihmasuk.php?id=' + id;
                }
            });
        });
    });
</script>