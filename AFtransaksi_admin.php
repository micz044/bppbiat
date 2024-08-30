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

// Cek apakah form transaksi telah disubmit
if (isset($_POST['submit_transaksi'])) {
    $nama = $_POST['nama']; // Ambil nama dari form

    // Ambil data dari tabel transaksi_penjualan berdasarkan nama dan status belum selesai
    $sql_transaksi = "SELECT * FROM transaksi_penjualan WHERE nama = '$nama' AND status = 'belum selesai'";
    $result_transaksi = $conn->query($sql_transaksi);

    if ($result_transaksi->num_rows > 0) {
        // Jika ditemukan transaksi dengan status belum selesai
        while ($row_transaksi = $result_transaksi->fetch_assoc()) {
            $jenis_benih = $row_transaksi['jenis_benih'];
            $ukuran = $row_transaksi['ukuran'];
            $jumlah_dibeli = $row_transaksi['jumlah_dibeli'];

            // Mengambil stok dari tabel data_benih_masuk berdasarkan jenis_benih dan ukuran
            // Menggunakan metode FIFO: stok diambil berdasarkan urutan masuk (tanggal paling awal)
            $stok_terpilih = [];
            $sql_benih_masuk = "SELECT no, jumlah_tersedia AS jumlah, keluar, tanggal_benih_masuk AS tanggal FROM data_benih_masuk WHERE jenis_benih = '$jenis_benih' AND ukuran = '$ukuran' ORDER BY tanggal ASC";
            $result_benih_masuk = $conn->query($sql_benih_masuk);

            while ($row = $result_benih_masuk->fetch_assoc()) {
                $stok_terpilih[] = $row;
            }

            // Menghitung total stok tersedia dari tabel data_benih_masuk
            $total_stok_tersedia = 0;
            foreach ($stok_terpilih as $stok) {
                $total_stok_tersedia += $stok['jumlah'];
            }

            // Periksa apakah total stok mencukupi untuk transaksi
            if ($total_stok_tersedia >= $jumlah_dibeli) {
                // Jika stok mencukupi, kurangi stok berdasarkan jumlah yang tersedia dari tabel data_benih_masuk
                // FIFO: Pemrosesan berdasarkan urutan masuknya stok (tanggal yang paling awal)
                while ($jumlah_dibeli > 0 && !empty($stok_terpilih)) {
                    foreach ($stok_terpilih as $key => $stok) {
                        if ($stok['jumlah'] > 0) {
                            // Kurangi stok berdasarkan jumlah yang tersedia
                            $jumlah_ambil = min($stok['jumlah'], $jumlah_dibeli);
                            $stok_terpilih[$key]['jumlah'] -= $jumlah_ambil;
                            $jumlah_dibeli -= $jumlah_ambil;

                            // Update stok di database
                            $sql_update = "UPDATE data_benih_masuk SET jumlah_tersedia = {$stok_terpilih[$key]['jumlah']}, keluar = keluar + $jumlah_ambil WHERE no = {$stok['no']}";
                            
                            // Eksekusi query update stok
                            $conn->query($sql_update);

                            // Keluar dari loop jika stok sudah cukup
                            if ($jumlah_dibeli <= 0) {
                                break;
                            }
                        }
                    }
                }

                // Perbarui status transaksi jika stok cukup
                if ($jumlah_dibeli <= 0) {
                    $sql_update_status = "UPDATE transaksi_penjualan SET status = 'selesai' WHERE no = " . $row_transaksi['no'];
                    $conn->query($sql_update_status);

                    // Update stok di tabel data_persediaan
                    $sql_update_persediaan = "UPDATE data_persediaan SET stok = stok - {$row_transaksi['jumlah_dibeli']} WHERE jenis_benih = '$jenis_benih' AND ukuran = '$ukuran'";
                    $conn->query($sql_update_persediaan);
                }

                // Redirect ke halaman admin_transaksi.php dengan parameter sukses
                header("Location: admin_transaksi.php?success=transaksi_berhasil&nama=" . urlencode($nama));
                exit();
            } else {
                // Jika total stok tidak mencukupi
                header("Location: admin_transaksi.php?error=stok_tidak_cukup&nama=" . urlencode($nama));
                exit();
            }
        }
    } else {
        // Jika tidak ada transaksi yang ditemukan untuk nama ini
        header("Location: admin_transaksi.php?error=transaksi_tidak_ditemukan&nama=" . urlencode($nama));
        exit();
    }
}

$conn->close();
?>
