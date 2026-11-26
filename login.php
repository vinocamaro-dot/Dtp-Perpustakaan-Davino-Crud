<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Login Perpustakaan</h1>

    <?php if ($error === 'salah'): ?>
        <div class="box" style="background:#fde8e8;border-color:#a61b1b;">
            <strong>Nama atau NIP/NIS salah.</strong>
        </div>
    <?php endif; ?>

    <div class="box">
        <form action="proses_login.php" method="POST">
            <div class="field">
                <label>Masuk Sebagai</label>
                <select name="role" id="role" onchange="ubahForm()">
                    <option value="anggota">Siswa (Nama + NIS)</option>
                    <option value="admin">Guru (Nama + NIP)</option>
                </select>
            </div>
            <div class="field">
                <label id="lbl-nama">Nama Siswa</label>
                <input type="text" name="nama" required>
            </div>
            <div class="field">
                <label id="lbl-no">NIS</label>
                <input type="text" name="nomor_induk" required>
            </div>
            <button type="submit">Masuk</button>
        </form>
    </div>
</div>

<script>
function ubahForm() {
    const guru = document.getElementById('role').value === 'admin';
    document.getElementById('lbl-nama').textContent = guru ? 'Nama Guru' : 'Nama Siswa';
    document.getElementById('lbl-no').textContent = guru ? 'NIP' : 'NIS';
}
</script>
</body>
</html>