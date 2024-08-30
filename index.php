<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fisheries Management System - Home</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .scrollable-table {
            max-height: 500px; /* Ubah sesuai kebutuhan */
            overflow-y: auto;
            border: 0px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-windows2 {
            display: flex;
            justify-content: center; /* Memusatkan konten secara horizontal */
            align-items: center; /* Memusatkan konten secara vertikal */
            flex-wrap: wrap;
            gap: 20px;
            min-height: 100vh; /* Pastikan area konten mencakup seluruh tinggi viewport */
        }

        .info-window2 {
            background-color: rgba(255, 255, 255, 0.815);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 700px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin: 0 auto; /* Memastikan info window berada di tengah */
        }

        .info-window2:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .info-window2 h3 {
            margin-top: 0;
            font-size: 24px;
        }

        .info-window2 p {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <img src="./gambar/Logo.png" alt="Logo">
            </div>
            <div class="company-info">
                <h4>BALAI PEMBENIHAN DAN PENGEMBANGAN BUDIDAYA IKAN AIR TAWAR</h4>
            </div>
            <nav class="nav-menu">
                <ul>
                    <li><a href="#tentang-perikanan">Profil Perikanan</a></li>
                    <li><a href="#data-benih">Data Persediaan Benih Ikan</a></li>
                    <li><a href="#kontak">Kontak</a></li>
                    <li><form action="form_login.php" method="post"><button type="submit" class="login-button">Login</button></form></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Sections -->

    <section id="tentang-perikanan" class="main-section">
        <div class="container">
            <h2>Profil Perikanan</h2>

            <div class="info-windows">
                <div class="info-window">
                    <h3>IKAN NILA</h3>
                    <img src="gambar/ikan_nila.png" alt="Ikan Nila">
                    <p>Ikan nila (Oreochromis niloticus) adalah sejenis ikan konsumsi air tawar yang diintroduksi dari Afrika, tepatnya Afrika bagian timur, pada tahun 1969, dan kini menjadi ikan peliharaan yang populer di kolam-kolam air tawar di Indonesia.</p>
                </div>
                <div class="info-window">
                    <h3>IKAN MAS</h3>
                    <img src="gambar/ikan_emas.png" alt="Ikan Mas">
                    <p>Ikan mas / Ikan karper (Cyprinus carpio) adalah ikan air tawar yang mulai dipelihara di Indonesia sekitar tahun 1920-an. Ikan mas yang terdapat di Indonesia merupakan ikan mas yang dibawa dari Cina, Eropa, Taiwan dan Jepang.</p>
                </div>
                <div class="info-window">
                    <h3>IKAN LELE</h3>
                    <img src="gambar/ikan_lele.png" alt="Ikan Lele">
                    <p>Lele atau Ikan Keli (Clarias melanoderma) adalah sejenis ikan yang hidup di air tawar.</p>
                </div>
                <div class="info-window">
                    <h3>IKAN KOI</h3>
                    <img src="gambar/ikan_koi.png" alt="Ikan Koi">
                    <p>Ikan Koi adalah jenis ikan hias ini termasuk dalam ikan mas (Cyprinus carpio) yang memiliki corak  yang sangat indah pada tubuhnya. </p>
                </div>
                <div class="info-window">
                    <h3>IKAN GURAME</h3>
                    <img src="gambar/ikan_gurame.png" alt="Ikan Gurame">
                    <p>Gurami atau gurami (Osphronemus gouramy) adalah sejenis ikan air tawar yang populer sebagai ikan konsumsi di Asia Tenggara dan Asia Selatan.</p>
                </div>
                <div class="info-window">
                    <h3>IKAN GABUS</h3>
                    <img src="gambar/ikan_gabus1.png" alt="Ikan Gabus">
                    <p>Ikan Gabus dengan nama ilmiah Channa striata (Bloch, 1793). Ikan ini dijuluki gabus karena dagingnya putih, lembut dan tebal seperti gabus. Selain dari pada itu, ikan ini tidak memiliki tulang-tulang halus.</p>
                </div>
                <div class="info-window">
                    <h3>IKAN BETOK</h3>
                    <img src="gambar/ikan_batok.png" alt="Ikan betok">
                    <p>Betok (Anabas testudineus) adalah nama sejenis ikan air tawar dan payau, seluruh tubuhnya berwarna hitam sampai hijau pucat, panjang mencapai 25 cm, hidup di dasar perairan tropis.</p>
                </div>
                <div class="info-window">
                    <h3>LOBSTER AIR TAWAR</h3>
                    <img src="gambar/lobster_air_tawar.png" alt="Lonster Air Tawar">
                    <p>Lobster air tawar adalah crustacea berada dalam superfamili Astacoidea dan Parastacoidea yang bernapas dengan insang yang menyerupai bulu unggas dan memakan zooplankton, tumbuhan air.</p>
                </div>
                

                
                <!-- Tambahkan jendela informasi lainnya di sini -->
            </div>
        </div>
    </section>

    <section id="data-benih" class="main-section">
            <h2>Data Persediaan Benih Ikan</h2>
            <div class="scrollable-table">
            <table>
                <thead>
                    <tr>
                        <th>Jenis Benih</th>
                        <th>Ukuran (Cm)</th>
                        <th>Stok (Ekor)</th>
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

                    // Query untuk mengambil data dari stok_persediaan
                    $sql = "SELECT no, jenis_benih,ukuran, stok FROM data_persediaan";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Output data setiap baris
                        while($row = $result->fetch_assoc()) {
                            echo "<tr><td>" . $row["jenis_benih"] . "</td><td>" . $row["ukuran"] . " Cm</td><td>" . $row["stok"] . " Ekor</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Tidak ada data</td></tr>";
                    }

                    // Tutup koneksi
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="kontak" class="main-section">
        <div class="container">
            <div class="info-window2">
                <h2>Kontak</h2>
                <p>Hubungi kami untuk informasi lebih lanjut.</p>
                <p>0853-9847-7472</p>
            </div>
        </div>
    </section>

    <!-- Background Image -->
    <div class="background-image"></div>

    <script src="script.js"></script>
</body>
</html>