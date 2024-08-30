<?php
session_start();

function checkAuth($role) {
    if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: form_login.php?error=" . urlencode("Anda harus login terlebih dahulu."));
        exit();
    }
}
?>