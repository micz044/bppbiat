<?php include 'kepala_header.php'; ?>

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
    <!-- Main Section - Laporan Persediaan Benih Ikan -->
    <section id="laporan_persediaan" class="main-section active">
            <h2>Data Persediaan Benih Ikan</h2>
            <div class="scrollable-table">
            <table>
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Jenis Benih</th>
                        <th>Ukuran (Cm)</th>
                        <th>Stok (Ekor)</th>
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
                    // Query untuk mengambil data persediaan
                    $sql_persediaan = "SELECT no, jenis_benih, ukuran, stok FROM data_persediaan";
                    $result_persediaan = $conn->query($sql_persediaan);

                    if ($result_persediaan->num_rows > 0) {
                        while($row_persediaan = $result_persediaan->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row_persediaan['no'] . "</td>";
                            echo "<td>" . $row_persediaan['jenis_benih'] . "</td>";
                            echo "<td>" . $row_persediaan['ukuran'] . " Cm</td>";
                            echo "<td>" . $row_persediaan['stok'] . " Ekor</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Tidak ada data persediaan.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            </div>
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