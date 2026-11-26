<?php
require '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_buku    = $_POST['id_buku'] ?? 0;
    $judul_buku = trim($_POST['judul_buku']);
    $penulis    = trim($_POST['penulis']);
    $kategori   = trim($_POST['kategori']);
    $status     = $_POST['status'];

    if (!$id_buku || $judul_buku === '' || $penulis === '' || $kategori === '') {
        header("Location: ../index.php?pesan=gagal");
        exit;
    }

    $stmt = $pdo->prepare("UPDATE buku SET judul_buku = :judul, penulis = :penulis, kategori = :kategori, status = :status WHERE id_buku = :id");
    $stmt->execute([
        ':judul'    => $judul_buku,
        ':penulis'  => $penulis,
        ':kategori' => $kategori,
        ':status'   => $status,
        ':id'       => $id_buku
    ]);

    header("Location: ../index.php?pesan=ubah_ok");
    exit;
}

header("Location: ../index.php");
exit;