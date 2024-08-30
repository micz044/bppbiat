-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Agu 2024 pada 23.18
-- Versi server: 10.4.22-MariaDB
-- Versi PHP: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_persediaan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_benih_masuk`
--

CREATE TABLE `data_benih_masuk` (
  `no` int(11) NOT NULL,
  `kode_benih` varchar(20) NOT NULL,
  `jenis_benih` varchar(100) NOT NULL,
  `ukuran` varchar(50) NOT NULL,
  `jumlah_tersedia` int(11) NOT NULL,
  `keluar` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `tanggal_benih_masuk` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `data_benih_masuk`
--

INSERT INTO `data_benih_masuk` (`no`, `kode_benih`, `jenis_benih`, `ukuran`, `jumlah_tersedia`, `keluar`, `stok`, `tanggal_benih_masuk`) VALUES
(1, 'BM1', 'ikan nila', '1-2', 100000, 0, 100000, '2024-07-17'),
(2, 'BM2', 'ikan nila', '2-3', 92405, 7595, 100000, '2024-07-18'),
(3, 'BM3', 'ikan nila', '4-5', 100000, 0, 100000, '2024-07-18'),
(4, 'BM4', 'ikan nila', '6-8', 100000, 0, 100000, '2024-07-19'),
(5, 'BM5', 'ikan nila', '9-11', 100000, 0, 100000, '2024-07-19'),
(6, 'BM6', 'ikan nila', '11-15', 92500, 7500, 100000, '2024-07-21'),
(7, 'BM7', 'Ikan Lele ', '1-2', 100000, 0, 100000, '2024-07-24'),
(8, 'BM8', 'Ikan Lele', '2-3', 100000, 0, 100000, '2024-07-25'),
(9, 'BM9', 'Ikan Lele', '4-5', 100000, 0, 100000, '2024-07-26'),
(10, 'BM10', 'Ikan Lele', '6-8', 94000, 6000, 100000, '2024-07-26'),
(11, 'BM10', 'Ikan Lele', '9-11', 100000, 0, 100000, '2024-07-24'),
(12, 'BM10', 'Ikan Lele', '9-11', 100000, 0, 100000, '2024-07-26'),
(13, 'BM10', 'Ikan Lele', '11-15', 100000, 0, 100000, '2024-07-21'),
(14, 'BM10', 'Ikan Mas', '1-2', 5000, 0, 5000, '2024-07-27'),
(15, 'BM10', 'Ikan Mas', '1-2', 1000, 0, 1000, '2024-07-25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_persediaan`
--

CREATE TABLE `data_persediaan` (
  `no` int(11) NOT NULL,
  `jenis_benih` varchar(100) NOT NULL,
  `ukuran` varchar(50) NOT NULL,
  `stok` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `data_persediaan`
--

INSERT INTO `data_persediaan` (`no`, `jenis_benih`, `ukuran`, `stok`) VALUES
(1, 'ikan nila', '1-2', 100000),
(2, 'ikan nila', '2-3', 92405),
(3, 'ikan nila', '4-5', 100000),
(4, 'ikan nila', '6-8', 100000),
(5, 'ikan nila', '9-11', 100000),
(6, 'ikan nila', '11-15', 92500),
(7, 'Ikan Lele ', '1-2', 100000),
(8, 'Ikan Lele ', '2-3', 100000),
(9, 'Ikan Lele ', '4-5', 100000),
(10, 'Ikan Lele ', '6-8', 94000),
(11, 'Ikan Lele ', '9-11', 200000),
(12, 'Ikan Lele', '11-15', 100000),
(13, 'Ikan Mas', '1-2', 6000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_bulan_bm`
--

CREATE TABLE `laporan_bulan_bm` (
  `no` int(11) NOT NULL,
  `jenis_benih` varchar(255) NOT NULL,
  `ukuran` varchar(50) NOT NULL,
  `sisa` int(11) NOT NULL,
  `keluar` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `tanggal` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `laporan_bulan_bm`
--

INSERT INTO `laporan_bulan_bm` (`no`, `jenis_benih`, `ukuran`, `sisa`, `keluar`, `stok`, `tanggal`) VALUES
(1, 'Ikan Lele ', '1-2', 100000, 0, 100000, 'Juli 2024'),
(2, 'Ikan Lele', '11-15', 100000, 0, 100000, 'Juli 2024'),
(3, 'Ikan Lele', '2-3', 100000, 0, 100000, 'Juli 2024'),
(4, 'Ikan Lele', '4-5', 100000, 0, 100000, 'Juli 2024'),
(5, 'Ikan Lele', '6-8', 94000, 6000, 100000, 'Juli 2024'),
(6, 'Ikan Lele', '9-11', 200000, 0, 200000, 'Juli 2024'),
(7, 'Ikan Mas', '1-2', 6000, 0, 6000, 'Juli 2024'),
(8, 'ikan nila', '1-2', 100000, 0, 100000, 'Juli 2024'),
(9, 'ikan nila', '11-15', 92500, 7500, 100000, 'Juli 2024'),
(10, 'ikan nila', '2-3', 92405, 7595, 100000, 'Juli 2024'),
(11, 'ikan nila', '4-5', 100000, 0, 100000, 'Juli 2024'),
(12, 'ikan nila', '6-8', 100000, 0, 100000, 'Juli 2024'),
(13, 'ikan nila', '9-11', 100000, 0, 100000, 'Juli 2024');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_transaksi_bulan`
--

CREATE TABLE `laporan_transaksi_bulan` (
  `no` int(11) NOT NULL,
  `jenis_benih` varchar(255) NOT NULL,
  `ukuran` varchar(50) NOT NULL,
  `jumlah_dibeli` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `tanggal` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `laporan_transaksi_bulan`
--

INSERT INTO `laporan_transaksi_bulan` (`no`, `jenis_benih`, `ukuran`, `jumlah_dibeli`, `total`, `tanggal`) VALUES
(1, 'Ikan Nila', '1-2', 50, '1000000.00', 'Juli 2024'),
(2, 'Ikan Nila', '2-3', 95, '1900000.00', 'Juli 2024');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `sandi` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `sandi`, `status`) VALUES
(1, 'admin', 'adminpass', 'admin'),
(2, 'kepala', 'kepalapass', 'kepala');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_penjualan`
--

CREATE TABLE `transaksi_penjualan` (
  `no` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_benih` varchar(100) NOT NULL,
  `ukuran` varchar(50) NOT NULL,
  `jumlah_dibeli` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `tanggal_pemesanan` date NOT NULL,
  `status` enum('selesai','belum selesai') NOT NULL DEFAULT 'belum selesai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `transaksi_penjualan`
--

INSERT INTO `transaksi_penjualan` (`no`, `nama`, `jenis_benih`, `ukuran`, `jumlah_dibeli`, `harga`, `total`, `tanggal_pemesanan`, `status`) VALUES
(1, 'DIPA', 'ikan nila', '11-15', 7500, '2000.00', '15000000.00', '2024-07-27', 'selesai'),
(2, 'ABC', 'Ikan Lele ', '6-8', 6000, '1000.00', '6000000.00', '2024-07-28', 'selesai'),
(3, 'AAA', 'Ikan Mas', '1-2', 1000, '100.00', '100000.00', '2024-07-28', 'belum selesai');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `data_benih_masuk`
--
ALTER TABLE `data_benih_masuk`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `data_persediaan`
--
ALTER TABLE `data_persediaan`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `laporan_bulan_bm`
--
ALTER TABLE `laporan_bulan_bm`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `laporan_transaksi_bulan`
--
ALTER TABLE `laporan_transaksi_bulan`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi_penjualan`
--
ALTER TABLE `transaksi_penjualan`
  ADD PRIMARY KEY (`no`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `data_benih_masuk`
--
ALTER TABLE `data_benih_masuk`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `data_persediaan`
--
ALTER TABLE `data_persediaan`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `laporan_bulan_bm`
--
ALTER TABLE `laporan_bulan_bm`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `laporan_transaksi_bulan`
--
ALTER TABLE `laporan_transaksi_bulan`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `transaksi_penjualan`
--
ALTER TABLE `transaksi_penjualan`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
