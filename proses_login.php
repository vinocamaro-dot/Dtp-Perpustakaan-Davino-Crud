<?php
session_start();
require 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$role        = $_POST['role'] ?? 'anggota';
$nama        = trim($_POST['nama'] ?? '');
$nomor_induk = trim($_POST['nomor_induk'] ?? '');

if ($nama === '' || $nomor_induk === '') {
    header("Location: login.php?error=salah");
    exit;
}

if ($role === 'admin') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE nama = :nama AND nomor_induk = :no AND role = 'admin' LIMIT 1");
    $stmt->execute([':nama' => $nama, ':no' => $nomor_induk]);
    $guru = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$guru || !password_verify($nomor_induk, $guru['kode_hash'])) {
        header("Location: login.php?error=salah");
        exit;
    }

    $_SESSION['user_id']     = $guru['id'];
    $_SESSION['nama']        = $guru['nama'];
    $_SESSION['nomor_induk'] = $guru['nomor_induk'];
    $_SESSION['role']        = 'admin';

    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE nomor_induk = :no AND role = 'anggota' LIMIT 1");
$stmt->execute([':no' => $nomor_induk]);
$siswa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$siswa) {
    $hash = password_hash($nomor_induk, PASSWORD_DEFAULT);
    $ins = $pdo->prepare("INSERT INTO users (nama, nomor_induk, kode_hash, role) VALUES (:nama, :no, :hash, 'anggota')");
    $ins->execute([':nama' => $nama, ':no' => $nomor_induk, ':hash' => $hash]);

    $_SESSION['user_id']     = $pdo->lastInsertId();
    $_SESSION['nama']        = $nama;
    $_SESSION['nomor_induk'] = $nomor_induk;
    $_SESSION['role']        = 'anggota';
} else {
    if (!password_verify($nomor_induk, $siswa['kode_hash'])) {
        header("Location: login.php?error=salah");
        exit;
    }
    $_SESSION['user_id']     = $siswa['id'];
    $_SESSION['nama']        = $siswa['nama'];
    $_SESSION['nomor_induk'] = $siswa['nomor_induk'];
    $_SESSION['role']        = 'anggota';
}

header("Location: index.php");
exit;