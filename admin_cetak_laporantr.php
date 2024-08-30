<?php
require_once 'vendor/autoload.php'; // Memuat autoloader dari Composer
use \TCPDF as TCPDF;

$imagePath = __DIR__ . '/gambar/Logo.png';

// Pastikan POST request dari form telah diterima
if (isset($_POST['cetak_laporan'])) {
    // Ambil bulan yang dipilih dari form
    $bulan_pilihan = $_POST['bulan'];
    // Format bulan dan tahun dalam bahasa Indonesia
    $bulan_tahun = date('F Y', strtotime($bulan_pilihan));
    
    // Ubah nama bulan dari bahasa Inggris ke bahasa Indonesia
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
    
    // Mengganti nama bulan dari bahasa Inggris ke bahasa Indonesia
    $bulan_indo_pilihan = str_replace(array_keys($bulan_indo), array_values($bulan_indo), date('F', strtotime($bulan_pilihan)));

    // Format akhir bulan dan tahun dalam bahasa Indonesia
    $bulan_tahun_indo = $bulan_indo_pilihan . ' ' . date('Y', strtotime($bulan_pilihan));
    
    // Koneksi ke database (sesuaikan dengan detail koneksi Anda)
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'db_persediaan';

    $conn = new mysqli($server, $username, $password, $database);
    if ($conn->connect_error) {
        die("Koneksi ke database gagal: " . $conn->connect_error);
    }

    // Query untuk mengambil data transaksi berdasarkan bulan yang dipilih
    $sql = "SELECT jenis_benih, ukuran, jumlah_dibeli, total, tanggal FROM laporan_transaksi_bulan 
            WHERE tanggal LIKE '%$bulan_tahun_indo%'";
    $result = $conn->query($sql);

    // Array untuk menampung hasil query
    $transactions = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
    } else {
        header("Location: admin_laporantr.php?error=cetak_laporantr_error");
        exit(); // Keluar dari skrip jika tidak ada data 
    }

    // Tutup koneksi database
    $conn->close();

    // Buat objek TCPDF baru dengan orientasi landscape, ukuran A4
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Judul dokumen PDF
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('Laporan Transaksi Bulanan');

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
    $pdf->Cell(0, 10, 'Laporan Transaksi Bulanan', 0, 1, 'C');

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
    $pdf->Cell(30, 10, 'Ukuran (Cm)', 1, 0, 'C', 1);
    $pdf->Cell(35, 10, 'Jumlah Dibeli (Ekor)', 1, 0, 'C', 1);
    $pdf->Cell(30, 10, 'Total', 1, 0, 'C', 1);
    $pdf->Cell(25, 10, 'Tanggal', 1, 1, 'C', 1);

    // Data tabel
    $pdf->SetFont('');
    $no = 1;
    foreach ($transactions as $transaction) {
        $pdf->Cell(10, 10, $no++, 1, 0, 'C');
        $pdf->Cell(25, 10, $transaction['jenis_benih'], 1, 0, 'C');
        $pdf->Cell(30, 10, $transaction['ukuran'] . ' Cm', 1, 0, 'C');
        $pdf->Cell(35, 10, $transaction['jumlah_dibeli'] . ' Ekor', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Rp ' . number_format($transaction['total'], 0, ',', '.'), 1, 0, 'C');
        $pdf->Cell(25, 10, $transaction['tanggal'], 1, 1, 'C');
    }

    // Tambahkan teks "Kepala bppbiat" dan "syaiful lpd. NIP"
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(169, 1, 'Kepala UPTD BPPBIAT', 0, 1, 'R');
    $pdf->Ln(20);
    $pdf->Cell(0, 1, 'A. SAIFULLAH TAHIR, S.Sos., M.M', 0, 1, 'R');
    $pdf->Cell(173, 1, 'NIP : 197810122008011006', 0, 1, 'R');

    // Nama file untuk disimpan
    $nama_file = 'laporan_transaksi_bulanan_' . date('dmYHis') . '.pdf';

    // Output dokumen PDF ke browser atau simpan ke file
    $pdf->Output($nama_file, 'D');
} else {
    // Jika tidak ada POST request dari form, kembali ke halaman form
    header('Location: admin_laporantr.php');
    exit;
}
?>
