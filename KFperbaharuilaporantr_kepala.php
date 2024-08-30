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
    // Kosongkan tabel laporan_transaksi_bulan sebelum mengisi dengan data baru
    $sql_truncate = "TRUNCATE TABLE laporan_transaksi_bulan";
    if ($conn->query($sql_truncate) === FALSE) {
        echo "Error mengosongkan tabel laporan_transaksi_bulan: " . $conn->error;
        exit();
    }

    // Ambil data dari tabel transaksi_penjualan dan hitung per bulan
    $sql = "SELECT jenis_benih, ukuran, 
                   DATE_FORMAT(tanggal_pemesanan, '%Y-%m') AS bulan,
                   SUM(jumlah_dibeli) AS total_jumlah_dibeli,
                   SUM(total) AS total_total
            FROM transaksi_penjualan WHERE status='selesai'
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
            $jumlah_dibeli = $row['total_jumlah_dibeli'];
            $total = $row['total_total'];

            // Konversi bulan dari format 'YYYY-MM' ke 'Bulan Tahun'
            $tanggal = DateTime::createFromFormat('Y-m', $bulan)->format('F Y');
            // Terjemahkan nama bulan ke Bahasa Indonesia
            $tanggal = translateMonthToIndonesian($tanggal);

            // Masukkan data ke dalam tabel laporan_transaksi_bulan
            $sql_insert = "INSERT INTO laporan_transaksi_bulan (jenis_benih, ukuran, jumlah_dibeli, total, tanggal)
                           VALUES ('$jenis_benih', '$ukuran', $jumlah_dibeli, $total, '$tanggal')";
            if ($conn->query($sql_insert) === FALSE) {
                echo "Error: " . $sql_insert . "<br>" . $conn->error;
            }
        }
        // Tutup koneksi
        $conn->close();

        // Redirect to kepala_laporantr.php with success message
        header("Location: kepala_laporantr.php?success=perbaharui_laporantr");
        exit();
    } else {
        header("Location: kepala_laporantr.php?error=error_perbaharui_laporantr");
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
