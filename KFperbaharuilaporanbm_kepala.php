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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['perbaharui_laporan'])) {
    // Kosongkan tabel laporan_bulan_bm sebelum mengisi dengan data baru
    $sql_truncate = "TRUNCATE TABLE laporan_bulan_bm";
    if ($conn->query($sql_truncate) === FALSE) {
        echo "Error mengosongkan tabel laporan_bulan_bm: " . $conn->error;
        exit();
    }

    // Ambil data dari tabel data_benih_masuk dan hitung per bulan
    $sql = "SELECT jenis_benih, ukuran,
                   DATE_FORMAT(tanggal_benih_masuk, '%Y-%m') AS bulan,
                   SUM(jumlah_tersedia) AS total_jumlah_tersedia,
                   SUM(keluar) AS total_keluar,
                   SUM(stok) AS total_stok
            FROM data_benih_masuk
            GROUP BY jenis_benih, ukuran, bulan";
    $result = $conn->query($sql);

    if ($result === FALSE) {
        echo "Error: " . $conn->error;
        exit();
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $jenis_benih = $row['jenis_benih'];
            $ukuran = $row['ukuran'];
            $bulan = $row['bulan'];
            $stok = $row['total_stok'];
            $keluar = $row['total_keluar'];
            $sisa = $row['total_jumlah_tersedia'];

            // Konversi bulan dari format 'YYYY-MM' ke 'Bulan Tahun'
            $tanggal = DateTime::createFromFormat('Y-m', $bulan)->format('F Y');
            // Terjemahkan nama bulan ke Bahasa Indonesia
            $tanggal = translateMonthToIndonesian($tanggal);

            // Masukkan data ke dalam tabel laporan_bulan_bm
            $sql_insert = "INSERT INTO laporan_bulan_bm (jenis_benih, ukuran, sisa, keluar, stok, tanggal)
                           VALUES ('$jenis_benih', '$ukuran', $sisa, $keluar, $stok, '$tanggal')";
            if ($conn->query($sql_insert) === FALSE) {
                echo "Error: " . $sql_insert . "<br>" . $conn->error;
            }
        }
        // Tutup koneksi
        $conn->close();

        // Redirect to kepala_dashboard.php with success message
        header("Location: kepala_laporanbm.php?success=perbaharui_laporanbm");
        exit();
    } else {
        header("Location: kepala_laporanbm.php?error=error_perbaharui_laporanbm");
        exit();
    }
}

// Terjemahkan nama bulan ke Bahasa Indonesia
function translateMonthToIndonesian($date) {
    $months = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];
    $parts = explode(' ', $date);
    $month = $parts[0];
    $year = $parts[1];
    return $months[$month] . ' ' . $year;
}

// Tutup koneksi
$conn->close();
?>
