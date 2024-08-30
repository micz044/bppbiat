<?php
require_once 'vendor/autoload.php'; // Memuat autoloader dari Composer
use \TCPDF as TCPDF;

$imagePath = __DIR__ . '/gambar/Logo.png';

// Pastikan POST request dari form telah diterima
if (isset($_POST['cetak_laporan'])) {
    // Ambil bulan yang dipilih dari form
    $bulan_pilihan = $_POST['bulan'];
    // Mengubah format input bulan dari 'YYYY-MM' ke 'Bulan Tahun'
    $bulan_tahun = date('F Y', strtotime($bulan_pilihan));
    $bulan_indo = [
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
    $bulan_tahun_indo = str_replace(array_keys($bulan_indo), array_values($bulan_indo), $bulan_tahun);

    // Koneksi ke database (sesuaikan dengan detail koneksi Anda)
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'db_persediaan';

    $conn = new mysqli($server, $username, $password, $database);
    if ($conn->connect_error) {
        die("Koneksi ke database gagal: " . $conn->connect_error);
    }

    // Query untuk mengambil data transaksi berdasarkan bulan yang dipilih dari tabel laporan_transaksi_bulan
    $sql = "SELECT no, jenis_benih, ukuran, sisa, keluar, stok, tanggal FROM laporan_bulan_bm 
            WHERE tanggal LIKE '%$bulan_tahun_indo%'";
    $result = $conn->query($sql);

    // Array untuk menampung hasil query
    $transactions = [];
    if ($result->num_rows > 0) {
        $no = 1; // Nomor awal
        while ($row = $result->fetch_assoc()) {
            $row['no'] = $no++; // Tambahkan nomor dan increment
            $transactions[] = $row;
        }
    } else {
        header("Location: admin_laporanbm.php?error=cetak_laporanbm_error");
        exit(); // Keluar dari skrip jika tidak ada data
    }

    // Tutup koneksi database
    $conn->close();

    // Buat objek TCPDF baru dengan orientasi landscape dan kertas A4
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Judul dokumen PDF
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('Laporan Pembelian Benih bulanan');

    // Atur margin
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);

    // Menambahkan halaman baru
    $pdf->AddPage();

    // Tambahkan logo di samping kiri kop surat
    if (file_exists($imagePath)) {
        $pdf->Image($imagePath, 15, 15, 18, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    } else {
        die('Logo tidak ditemukan atau path tidak valid: ' . $imagePath);
    }

    // Kop surat di tengah halaman
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetY(15); // Atur posisi Y ke 15 mm
    $pdf->Cell(0, 5, 'PEMERINTAHAN KABUPATEN SOPPENG ', 0, 1, 'C');
    $pdf->Cell(0, 5, 'DINAS PETERNAKAN, KESEHATAN HEWAN DAN PERIKANAN', 0, 1, 'C');
    $pdf->Cell(0, 5, 'UNIT PELAKSANA TEKNIS DAERAH BALAI PEMBENIHAN DAN', 0, 1, 'C');
    $pdf->Cell(0, 5, 'PENGEMBANGAN BUDIDAYA IKAN AIR TAWAR', 0, 1, 'C');

    // Tambahkan garis horizontal di bagian atas kop surat
    $pdf->SetLineWidth(0.8); // Set ketebalan garis
    $pdf->Line(15, 40, 210 - 15, 40); // Koordinat (15, 40) hingga (297 - 15, 40) untuk A4 landscape
    $pdf->Ln(9); // Spasi setelah garis

    // Judul halaman
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Laporan Benih Masuk bulanan', 0, 1, 'C');

    // Tanggal cetak
    $pdf->Ln(1); // Spasi sebelum tanggal
    $pdf->SetFont('helvetica', 'I', 10);
    $pdf->Cell(0, 10, 'Tanggal Cetak: ' . date('d F Y'), 0, 1, 'L');

    // Tambahkan tabel untuk menampilkan data transaksi
    $pdf->SetFont('helvetica', '', 9);

    // Header tabel
    $pdf->SetFillColor(200, 220, 255);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0);
    $pdf->SetLineWidth(0.1);
    $pdf->SetFont('', 'B');
    $pdf->Cell(10, 10, 'No', 1, 0, 'C', 1);
    $pdf->Cell(25, 10, 'Jenis Benih', 1, 0, 'C', 1);
    $pdf->Cell(25, 10, 'Ukuran (Cm)', 1, 0, 'C', 1);
    $pdf->Cell(25, 10, 'Sisa (Ekor)', 1, 0, 'C', 1);
    $pdf->Cell(25, 10, 'Keluar (Ekor)', 1, 0, 'C', 1);
    $pdf->Cell(45, 10, 'Jumlah Benih Masuk (Ekor)', 1, 0, 'C', 1);
    $pdf->Cell(30, 10, 'Tanggal', 1, 1, 'C', 1);

    // Data tabel
    $pdf->SetFont('');
    foreach ($transactions as $transaction) {
        $pdf->Cell(10, 10, $transaction['no'], 1, 0, 'C');
        $pdf->Cell(25, 10, $transaction['jenis_benih'], 1, 0, 'C');
        $pdf->Cell(25, 10, $transaction['ukuran'] . ' Cm', 1, 0, 'C');
        $pdf->Cell(25, 10, $transaction['sisa'] . ' Ekor', 1, 0, 'C');
        $pdf->Cell(25, 10, $transaction['keluar'] . ' Ekor', 1, 0, 'C');
        $pdf->Cell(45, 10, $transaction['stok'] . ' Ekor', 1, 0, 'C');
        $pdf->Cell(30, 10, $transaction['tanggal'], 1, 1, 'C');
    }

    // Tambahkan teks "Kepala bppbiat" dan "syaiful lpd. NIP"
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(169, 1, 'Kepala UPTD BPPBIAT', 0, 1, 'R');
    $pdf->Ln(20);
    $pdf->Cell(0, 1, 'A. SAIFULLAH TAHIR, S.Sos., M.M', 0, 1, 'R');
    $pdf->Cell(173, 1, 'NIP : 197810122008011006', 0, 1, 'R');

    // Nama file untuk disimpan
    $nama_file = 'laporan_pembelian_bulanan_' . date('dmYHis') . '.pdf';

    // Output PDF ke browser
    $pdf->Output($nama_file, 'I');
}
?>
