<?php
session_start();
require 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit;
}

$role        = $_POST['role'] ?? 'anggota';
$nama        = trim($_POST['nama'] ?? '');
$nomor_induk = trim($_POST['nomor_induk'] ?? '');

if ($nama === '' || $nomor_induk === '' || !in_array($role, ['admin', 'anggota'])) {
    header("Location: register.php?error=kosong");
    exit;
}

// Cek apakah nomor_induk sudah pernah didaftarkan
$cek = $pdo->prepare("SELECT id_regis FROM regis WHERE nomor_induk = :no LIMIT 1");
$cek->execute([':no' => $nomor_induk]);

if ($cek->fetch()) {
    header("Location: register.php?error=terdaftar");
    exit;
}

// Hash nomor_induk sesuai standar kode 
$hash = password_hash($nomor_induk, PASSWORD_DEFAULT);

try {
    $pdo->beginTransaction();

    // 1. Simpan ke tabel baru `regis`
    $stmtRegis = $pdo->prepare("INSERT INTO regis (nama, nomor_induk, kode_hash, role) VALUES (:nama, :no, :hash, :role)");
    $stmtRegis->execute([
        ':nama' => $nama,
        ':no'   => $nomor_induk,
        ':hash' => $hash,
        ':role' => $role
    ]);

    // 2. Simpan juga ke tabel `users` agar bisa langsung dipakai login
    $stmtUser = $pdo->prepare("INSERT INTO users (nama, nomor_induk, kode_hash, role) VALUES (:nama, :no, :hash, :role)");
    $stmtUser->execute([
        ':nama' => $nama,
        ':no'   => $nomor_induk,
        ':hash' => $hash,
        ':role' => $role
    ]);

    $pdo->commit();

    header("Location: login.php?pesan=regis_ok");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    header("Location: register.php?error=kosong");
    exit;
}