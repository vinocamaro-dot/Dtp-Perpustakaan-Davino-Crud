<?php
require '../config/koneksi.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'anggota') {
    header("Location: ../index.php?pesan=akses_ditolak");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_siswa = trim($_POST['nama_siswa']);
    $id_buku    = $_POST['id_buku'];
    $tgl_pinjam = trim($_POST['tgl_pinjam']);

    $stmt = $pdo->prepare("INSERT INTO peminjaman (nama_siswa, id_buku, tgl_pinjam) VALUES (:nama, :id_buku, :tgl)");
    $stmt->execute([
        ':nama'    => $nama_siswa,
        ':id_buku' => $id_buku,
        ':tgl'     => $tgl_pinjam
    ]);

    $pdo->prepare("UPDATE buku SET status = 'Dipinjam' WHERE id_buku = :id_buku")->execute([':id_buku' => $id_buku]);
}

header("Location: ../index.php");
exit;