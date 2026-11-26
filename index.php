<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'config/koneksi.php';

$role = $_SESSION['role']; // 'admin' (Guru) atau 'anggota' (Siswa)
$nama_user = $_SESSION['nama'];

$buku = $pdo->query("SELECT * FROM buku ORDER BY id_buku DESC")->fetchAll(PDO::FETCH_ASSOC);
$buku_tersedia = $pdo->query("SELECT * FROM buku WHERE status = 'Tersedia' ORDER BY judul_buku ASC")->fetchAll(PDO::FETCH_ASSOC);

$sedang_dipinjam = $pdo->query("
    SELECT p.*, b.judul_buku
    FROM peminjaman p
    JOIN buku b ON p.id_buku = b.id_buku
    WHERE p.status_pinjam = 'Dipinjam'
    ORDER BY p.id_pinjam DESC
")->fetchAll(PDO::FETCH_ASSOC);

$riwayat_kembali = $pdo->query("
    SELECT k.*, b.judul_buku
    FROM pengembalian k
    JOIN buku b ON k.id_buku = b.id_buku
    ORDER BY k.id_kembali DESC
")->fetchAll(PDO::FETCH_ASSOC);

$pesan = $_GET['pesan'] ?? '';
$daftar_pesan = [
    'tambah_ok'     => 'Buku berhasil ditambahkan.',
    'ubah_ok'       => 'Data buku berhasil diperbarui.',
    'hapus_ok'      => 'Buku berhasil dihapus.',
    'pinjam_ok'     => 'Peminjaman buku berhasil dicatat.',
    'kembali_ok'    => 'Buku berhasil dikembalikan.',
    'akses_ditolak' => 'Akses ditolak! Anda tidak memiliki izin untuk tindakan tersebut.',
    'gagal'         => 'Operasi gagal. Pastikan semua data terisi dengan benar.'
];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h1 style="margin: 0;">Perpustakaan Skomda</h1>
        <div>
            <span style="font-size: 14px; margin-right: 10px;">
                Halo, <strong><?= htmlspecialchars($nama_user) ?></strong> (<?= $role === 'admin' ? 'Guru' : 'Siswa' ?>)
            </span>
            <a href="logout.php" class="btn btn-hapus">Logout</a>
        </div>
    </div>

    <?php if (isset($daftar_pesan[$pesan])): ?>
        <div class="box" style="background: <?= ($pesan === 'gagal' || $pesan === 'akses_ditolak') ? '#fde8e8' : '#e8f5e9' ?>; border-color: <?= ($pesan === 'gagal' || $pesan === 'akses_ditolak') ? '#a61b1b' : '#2e7d32' ?>;">
            <strong><?= htmlspecialchars($daftar_pesan[$pesan]) ?></strong>
        </div>
    <?php endif; ?>

    <!-- HAK AKSES GURU (ADMIN): TAMBAH, EDIT, HAPUS BUKU -->
    <?php if ($role === 'admin'): ?>
    <div class="box">
        <h2>1. Tambah Buku</h2>
        <form action="proses/tambah_buku.php" method="POST">
            <div class="field">
                <label>Judul Buku</label>
                <input type="text" name="judul_buku" required>
            </div>
            <div class="field">
                <label>Penulis</label>
                <input type="text" name="penulis" required>
            </div>
            <div class="field">
                <label>Kategori</label>
                <input type="text" name="kategori" required>
            </div>
            <div class="field">
                <label>Status</label>
                <select name="status">
                    <option value="Tersedia">Tersedia</option>
                    <option value="Dipinjam">Dipinjam</option>
                </select>
            </div>
            <button type="submit">Simpan Buku</button>
        </form>

        <h3 style="margin-top: 18px;">Daftar Buku</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php $no = 1; foreach ($buku as $b): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($b['judul_buku']) ?></td>
                <td><?= htmlspecialchars($b['penulis']) ?></td>
                <td><?= htmlspecialchars($b['kategori']) ?></td>
                <td><?= htmlspecialchars($b['status']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $b['id_buku'] ?>" class="btn btn-edit">Edit</a>
                    <a href="proses/hapus_buku.php?id=<?= $b['id_buku'] ?>" class="btn btn-hapus" onclick="return confirm('Hapus buku ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>

    <!-- HAK AKSES SISWA (ANGGOTA): PINJAM BUKU -->
    <?php if ($role === 'anggota'): ?>
    <div class="box">
        <h2>Pinjam Buku</h2>
        <form action="proses/pinjam.php" method="POST">
            <div class="field">
                <label>Nama Siswa</label>
                <input type="text" name="nama_siswa" value="<?= htmlspecialchars($nama_user) ?>" readonly required>
            </div>
            <div class="field">
                <label>Buku</label>
                <select name="id_buku" required>
                    <?php foreach ($buku_tersedia as $bt): ?>
                        <option value="<?= $bt['id_buku'] ?>"><?= htmlspecialchars($bt['judul_buku']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label>Tanggal Pinjam</label>
                <input type="text" name="tgl_pinjam" value="<?= date('Y-m-d') ?>" required>
            </div>
            <button type="submit">Pinjam</button>
        </form>

        <h3 style="margin-top: 18px;">Sedang Dipinjam</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
            </tr>
            <?php $no = 1; foreach ($sedang_dipinjam as $p): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($p['nama_siswa']) ?></td>
                <td><?= htmlspecialchars($p['judul_buku']) ?></td>
                <td><?= htmlspecialchars($p['tgl_pinjam']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>

    <!-- HAK AKSES GURU (ADMIN): KEMBALIKAN BUKU -->
    <?php if ($role === 'admin'): ?>
    <div class="box">
        <h2>2. Kembalikan Buku</h2>
        <form action="proses/kembali.php" method="POST">
            <div class="field">
                <label>Pilih Peminjaman</label>
                <select name="id_pinjam" required>
                    <?php foreach ($sedang_dipinjam as $p): ?>
                        <option value="<?= $p['id_pinjam'] ?>">
                            <?= htmlspecialchars($p['nama_siswa']) ?> - <?= htmlspecialchars($p['judul_buku']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label>Tanggal Kembali</label>
                <input type="text" name="tgl_kembali" value="<?= date('Y-m-d') ?>" required>
            </div>
            <button type="submit">Kembalikan</button>
        </form>

        <h3 style="margin-top: 18px;">Sudah Dikembalikan</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
            </tr>
            <?php $no = 1; foreach ($riwayat_kembali as $rk): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($rk['nama_siswa']) ?></td>
                <td><?= htmlspecialchars($rk['judul_buku']) ?></td>
                <td><?= htmlspecialchars($rk['tgl_pinjam']) ?></td>
                <td><?= htmlspecialchars($rk['tgl_kembali']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
</div>
</body>
</html>