<?php
require 'config/koneksi.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM buku WHERE id_buku = :id");
$stmt->execute([':id' => $id]);
$b = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$b) {
    header("Location: index.php");
    exit;
}

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?pesan=akses_ditolak"); // Jika di edit.php, ganti ../index.php menjadi index.php
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Edit Buku</h1>
    <div class="box">
        <form action="proses/ubah_buku.php" method="POST">
            <input type="hidden" name="id_buku" value="<?= $b['id_buku'] ?>">
            <div class="field">
                <label>Judul</label>
                <input type="text" name="judul_buku" value="<?= htmlspecialchars($b['judul_buku']) ?>" required>
            </div>
            <div class="field">
                <label>Penulis</label>
                <input type="text" name="penulis" value="<?= htmlspecialchars($b['penulis']) ?>" required>
            </div>
            <div class="field">
                <label>Kategori</label>
                <input type="text" name="kategori" value="<?= htmlspecialchars($b['kategori']) ?>" required>
            </div>
            <div class="field">
                <label>Status</label>
                <select name="status">
                    <option value="Tersedia" <?= $b['status'] === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="Dipinjam" <?= $b['status'] === 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                </select>
            </div>
            <button type="submit">Update</button>
            <a href="index.php" class="btn btn-edit">Batal</a>
        </form>
    </div>
</div>
</body>
</html>