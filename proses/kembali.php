<?php
require '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pinjam   = $_POST['id_pinjam'] ?? 0;
    $tgl_kembali = trim($_POST['tgl_kembali']);

    $cek = $pdo->prepare("SELECT * FROM peminjaman WHERE id_pinjam = :id");
    $cek->execute([':id' => $id_pinjam]);
    $pinjam = $cek->fetch(PDO::FETCH_ASSOC);

    if ($pinjam && $tgl_kembali !== '') {
        $stmt = $pdo->prepare("
            INSERT INTO pengembalian (id_pinjam, nama_siswa, id_buku, tgl_pinjam, tgl_kembali)
            VALUES (:id_pinjam, :nama_siswa, :id_buku, :tgl_pinjam, :tgl_kembali)
        ");
        $stmt->execute([
            ':id_pinjam'   => $pinjam['id_pinjam'],
            ':nama_siswa'  => $pinjam['nama_siswa'],
            ':id_buku'     => $pinjam['id_buku'],
            ':tgl_pinjam'  => $pinjam['tgl_pinjam'],
            ':tgl_kembali' => $tgl_kembali
        ]);

        $pdo->prepare("UPDATE peminjaman SET status_pinjam = 'Dikembalikan' WHERE id_pinjam = :id")
            ->execute([':id' => $id_pinjam]);

        $pdo->prepare("UPDATE buku SET status = 'Tersedia' WHERE id_buku = :id_buku")
            ->execute([':id_buku' => $pinjam['id_buku']]);

        header("Location: ../index.php?pesan=kembali_ok");
        exit;
    }
}

header("Location: ../index.php?pesan=gagal");
exit;