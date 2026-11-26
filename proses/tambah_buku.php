<?php
require '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul_buku = trim($_POST['judul_buku']);
    $penulis    = trim($_POST['penulis']);
    $kategori   = trim($_POST['kategori']);
    $status     = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO buku (judul_buku, penulis, kategori, status) VALUES (:judul, :penulis, :kategori, :status)");
    $stmt->execute([
        ':judul'    => $judul_buku,
        ':penulis'  => $penulis,
        ':kategori' => $kategori,
        ':status'   => $status
    ]);
}

header("Location: ../index.php");
exit;