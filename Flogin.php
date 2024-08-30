<?php
session_start();
$servername = "localhost";
$username = "root";
$password = ""; // Kosongkan jika tidak ada password
$dbname = "db_persediaan";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['username'];
    $sandi = $_POST['password'];
    $role = $_POST['role'];

    // Mencegah SQL injection
    $nama = stripslashes($nama);
    $sandi = stripslashes($sandi);
    $role = stripslashes($role);
    $nama = mysqli_real_escape_string($conn, $nama);
    $sandi = mysqli_real_escape_string($conn, $sandi);
    $role = mysqli_real_escape_string($conn, $role);

    // Memeriksa apakah nama dan sandi sesuai
    $sql = "SELECT * FROM pengguna WHERE nama='$nama' AND sandi='$sandi'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Memeriksa apakah status sesuai
        if ($row['status'] == $role) {
            $_SESSION['username'] = $row['nama'];
            $_SESSION['role'] = $row['status'];
            if ($row['status'] == 'admin') {
                header("Location: admin_transaksi.php");
            } elseif ($row['status'] == 'kepala') {
                header("Location: kepala_laporantr.php");
            }
        } else {
            $error = "Status yang anda masukkan salah";
            header("Location: form_login.php?error=" . urlencode($error));
        }
    } else {
        // Memeriksa apakah nama saja yang salah atau sandi saja yang salah
        $sql_check_name = "SELECT * FROM pengguna WHERE nama='$nama'";
        $result_check_name = $conn->query($sql_check_name);
        if ($result_check_name->num_rows > 0) {
            $error = "Sandi yang dimasukkan salah.";
        } else {
            $error = "Nama yang dimasukkan salah.";
        }
        header("Location: form_login.php?error=" . urlencode($error));
    }
}
$conn->close();
?>