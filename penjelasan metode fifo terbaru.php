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

    // Ambil data transaksi yang belum selesai berdasarkan nama
    $sql_transaksi = "SELECT * FROM transaksi_penjualan WHERE nama = '$nama' AND status = 'belum selesai'";
    $result_transaksi = $conn->query($sql_transaksi);

    // Cek apakah ada transaksi yang belum selesai untuk nama yang diberikan
    if ($result_transaksi->num_rows > 0) {
        // Jika ada transaksi yang ditemukan, proses setiap transaksi
        while ($row_transaksi = $result_transaksi->fetch_assoc()) {
            $jenis_benih = $row_transaksi['jenis_benih'];
            $ukuran = $row_transaksi['ukuran'];
            $jumlah_dibeli = $row_transaksi['jumlah_dibeli'];

            // Ambil stok dari tabel data_benih_masuk berdasarkan jenis_benih dan ukuran menggunakan metode FIFO
            $stok_terpilih = [];
            $sql_benih_masuk = "SELECT no, jumlah_tersedia AS jumlah, keluar, tanggal_benih_masuk AS tanggal 
                                FROM data_benih_masuk 
                                WHERE jenis_benih = '$jenis_benih' AND ukuran = '$ukuran' 
                                ORDER BY tanggal ASC";
            $result_benih_masuk = $conn->query($sql_benih_masuk);

            // Simpan stok yang diambil ke dalam array stok_terpilih
            while ($row = $result_benih_masuk->fetch_assoc()) {
                $stok_terpilih[] = $row; 
            }

            // Hitung total stok yang tersedia
            $total_stok_tersedia = 0;
            foreach ($stok_terpilih as $stok) {
                $total_stok_tersedia += $stok['jumlah'];
            }

            // Cek apakah total stok cukup untuk memenuhi jumlah yang dibeli
            if ($total_stok_tersedia >= $jumlah_dibeli) {
                // Jika stok cukup, kurangi stok berdasarkan metode FIFO
                while ($jumlah_dibeli > 0 && !empty($stok_terpilih)) {
                    foreach ($stok_terpilih as $key => $stok) {
                        // Tentukan jumlah yang akan diambil dari stok
                        if ($stok['jumlah'] > 0) {
                            $jumlah_ambil = min($stok['jumlah'], $jumlah_dibeli);
                            
                            // Kurangi stok yang tersedia dengan jumlah yang diambil
                            $stok_terpilih[$key]['jumlah'] -= $jumlah_ambil;
                            $jumlah_dibeli -= $jumlah_ambil;
                    
                            // Update stok di database
                            $sql_update = "UPDATE data_benih_masuk SET jumlah_tersedia = {$stok_terpilih[$key]['jumlah']}, keluar = keluar + $jumlah_ambil WHERE no = {$stok['no']}";
                            $conn->query($sql_update);
                    
                            // Jika semua jumlah yang dibeli sudah terpenuhi, hentikan proses
                            if ($jumlah_dibeli <= 0) {
                                break;
                            }
                        }
                    }
                }

                // Jika stok cukup, update status transaksi menjadi 'selesai'
                if ($jumlah_dibeli <= 0) {
                    $sql_update_status = "UPDATE transaksi_penjualan 
                                          SET status = 'selesai' 
                                          WHERE no = " . $row_transaksi['no'];
                    $conn->query($sql_update_status);

                    // Kurangi stok di tabel data_persediaan
                    $sql_update_persediaan = "UPDATE data_persediaan 
                                              SET stok = stok - {$row_transaksi['jumlah_dibeli']} 
                                              WHERE jenis_benih = '$jenis_benih' AND ukuran = '$ukuran'";
                    $conn->query($sql_update_persediaan);
                }

                // Redirect ke halaman admin_transaksi.php dengan pesan sukses
                header("Location: admin_transaksi.php?success=transaksi_berhasil&nama=" . urlencode($nama));
                exit();
            } else {
                // Jika stok tidak cukup, redirect ke halaman admin_transaksi.php dengan pesan error
                header("Location: admin_transaksi.php?error=stok_tidak_cukup&nama=" . urlencode($nama));
                exit();
            }
        }
    } else {
        // Jika tidak ada transaksi yang ditemukan, redirect ke halaman admin_transaksi.php dengan pesan error
        header("Location: admin_transaksi.php?error=transaksi_tidak_ditemukan&nama=" . urlencode($nama));
        exit();
    }
}

$conn->close(); // Tutup koneksi ke database
?>
