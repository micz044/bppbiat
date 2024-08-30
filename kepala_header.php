<?php
include 'cek_login.php';
checkAuth('kepala');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kepala Dashboard</title>
    <link rel="stylesheet" href="style_kepala.css">
</head>
<body>

    <!-- Header -->
    <header class="header">
        <img src="./gambar/Logo.png" alt="Logo">
        <h1>MENU KEPALA BPPBIAT</h1>
    </header>

    <!-- Navigation -->
    <nav class="nav">
        <ul>

            <li><a href="kepala_laporantr.php">Laporan Transaksi Penjualan</a></li>
            <li><a href="kepala_persediaan.php">Data Persediaan Benih Ikan</a></li>
            <li><a href="kepala_dataharga.php">Data Harga Benih Ikan</a></li>
            <li><a href="kepala_laporanbm.php">Laporan Data Produksi Benih</a></li>
         
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>    