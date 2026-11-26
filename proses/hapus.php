<?php
require '../config/koneksi.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?pesan=akses_ditolak"); // Jika di edit.php, ganti ../index.php menjadi index.php
    exit;
}

$id = $_GET['id'] ?? 0;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM buku WHERE id_buku = :id");
    $stmt->execute([':id' => $id]);
}

header("Location: ../index.php");
exit;