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
    die("Koneksi gagal: " . $conn->connect_error); // Jika koneksi gagal, tampilkan pesan kesalahan dan hentikan eksekusi
}

// If digunakan untuk mengecek apakah form transaksi telah disubmit
if (isset($_POST['submit_transaksi'])) {
    $nama = $_POST['nama']; // Ambil nama dari form

    // Ambil data dari tabel transaksi_penjualan berdasarkan nama dan status belum selesai
    $sql_transaksi = "SELECT * FROM transaksi_penjualan WHERE nama = '$nama' AND status = 'belum selesai'";
    $result_transaksi = $conn->query($sql_transaksi);

    // If digunakan untuk mengecek apakah ada baris hasil yang ditemukan
    if ($result_transaksi->num_rows > 0) {
        // Jika ada transaksi yang ditemukan
        while ($row_transaksi = $result_transaksi->fetch_assoc()) {
            // While digunakan untuk memproses setiap transaksi yang ditemukan
            // fetch_assoc() digunakan untuk mengambil baris hasil sebagai array asosiatif
            $jenis_benih = $row_transaksi['jenis_benih'];
            $ukuran = $row_transaksi['ukuran'];
            $jumlah_dibeli = $row_transaksi['jumlah_dibeli'];

            // Mengambil stok dari tabel data_benih_masuk berdasarkan jenis_benih dan ukuran
            // Menggunakan metode FIFO: stok diambil berdasarkan urutan masuk yaitu order by tanggal asc (tanggal paling awal)
            $stok_terpilih = [];
            $sql_benih_masuk = "SELECT no, jumlah_tersedia AS jumlah, keluar, tanggal_benih_masuk AS tanggal FROM data_benih_masuk WHERE jenis_benih = '$jenis_benih' AND ukuran = '$ukuran' ORDER BY tanggal ASC";
            $result_benih_masuk = $conn->query($sql_benih_masuk);

            while ($row = $result_benih_masuk->fetch_assoc()) {
                // Ambil setiap baris hasil sebagai array asosiatif dan simpan ke dalam array stok_terpilih
                $stok_terpilih[] = $row; 
                // Jadi setiap data yang ada di tabel data benih masuk akan di simpan
                // ke dalam array stok terpilih berdasarkan jenis benih dan ukuran 
                // dan akan mengurutkan berdasarkan tanggal paling awal
            }

            $total_stok_tersedia = 0;
            // Menggunakan foreach untuk melihat setiap item dalam array $stok_terpilih satu per satu
            foreach ($stok_terpilih as $stok) {
                // foreach digunakan untuk mengambil setiap data dari array $stok_terpilih satu per satu
                // Setiap data dalam array disebut $stok
                
                // Tambahkan jumlah stok dari setiap data ke total_stok_tersedia
                // $stok['jumlah'] adalah jumlah stok dari data saat ini
                // += digunakan untuk menambahkan nilai ke total_stok_tersedia
                $total_stok_tersedia += $stok['jumlah'];
            }

            // If diguanakan untuk memeriksa apakah total stok yang tersedia cukup untuk memenuhi jumlah yang dibeli
            if ($total_stok_tersedia >= $jumlah_dibeli) {
                // Jika stok cukup, proses pengurangan stok mengguanakan metode FIFO berdasarkan tanggal paling awal
                while ($jumlah_dibeli > 0 && !empty($stok_terpilih)) {
                    // While digunakan untuk memastikan bahwa proses akan terus berlangsung selama masih ada sisa yang harus dibeli
                    // dan memastikan bahwa masih ada data stok yang tersedia untuk diproses di array $stok_terpilih

                    foreach ($stok_terpilih as $key => $stok) {
                        // Foreach digunakan untuk mengulangi setiap elemen dalam array $stok_terpilih dan memproses stok satu per satu.
                        // $key menyimpan posisi atau indeks dari data saat ini dalam array
                        // $stok adalah data stok saat ini yang sedang diproses dari array
                    
                        // Cek jika masih ada stok yang tersedia
                        if ($stok['jumlah'] > 0) {
                            // Tentukan jumlah yang akan diambil: memilih yang lebih kecil antara stok yang ada dan jumlah yang dibeli
                            // Fungsi min() digunakan untuk memastikan kita tidak mengambil lebih dari yang tersedia atau lebih dari yang dibutuhkan
                            $jumlah_ambil = min($stok['jumlah'], $jumlah_dibeli);
                            
                            // Kurangi jumlah stok yang tersedia dengan jumlah yang diambil
                            // $stok_terpilih[$key]['jumlah'] diperbarui dengan mengurangi jumlah yang diambil
                            $stok_terpilih[$key]['jumlah'] -= $jumlah_ambil;
                            
                            // Kurangi jumlah yang harus dibeli dengan jumlah yang diambil
                            // $jumlah_dibeli dikurangi dengan jumlah yang diambil untuk menghitung sisa yang masih perlu dibeli
                            $jumlah_dibeli -= $jumlah_ambil;
                    
                            // Siapkan perintah SQL untuk memperbarui stok di database
                            // Perintah ini akan memperbarui jumlah stok yang tersedia dan jumlah yang keluar di tabel data_benih_masuk
                            $sql_update = "UPDATE data_benih_masuk SET jumlah_tersedia = {$stok_terpilih[$key]['jumlah']}, keluar = keluar + $jumlah_ambil WHERE no = {$stok['no']}";
                    
                            // Jalankan perintah SQL untuk memperbarui stok di database
                            // query() digunakan untuk menjalankan perintah SQL yang telah disiapkan
                            $conn->query($sql_update);
                    
                            // Jika semua jumlah yang dibeli sudah terpenuhi, berhenti memproses stok
                            // Jika jumlah_dibeli menjadi kurang dari atau sama dengan 0, berarti semua stok yang dibeli sudah terproses
                            if ($jumlah_dibeli <= 0) {
                                // break; menghentikan loop foreach untuk tidak memproses sisa stok
                                break;
                            }
                        }
                    }
                }

                // Perbarui status transaksi jika stok cukup
                if ($jumlah_dibeli <= 0) {
                    // Set status transaksi menjadi 'selesai'
                    $sql_update_status = "UPDATE transaksi_penjualan SET status = 'selesai' WHERE no = " . $row_transaksi['no'];
                    $conn->query($sql_update_status);

                    // Update stok di tabel data_persediaan
                    // Kurangi stok sesuai dengan jumlah yang dibeli
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

$conn->close(); // Tutup koneksi ke database
?>
