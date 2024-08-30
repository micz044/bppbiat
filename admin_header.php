<?php 
    include 'cek_login.php';
    checkAuth('admin');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style_admin.css">
</head>
<body>
    
    <!-- Header -->
    <header class="header">
        <img src="./gambar/Logo.png" alt="Logo">
        <h1>MENU STAF BPPBIAT</h1>
    </header>

    <!-- Navigation -->
    <nav class="nav">
        <ul>
            
            <li><a href="admin_transaksi.php">Transaksi Penjualan</a></li>
            <li><a href="admin_persediaan.php">Data Persediaan Benih Ikan</a></li>
            <li><a href="admin_benihmasuk.php">Data Produksi Benih</a></li>
            <li><a href="admin_laporantr.php">Laporan Transaksi Penjualan</a></li>
            <li><a href="admin_laporanbm.php">Laporan Data Produksi Benih</a></li>

            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>