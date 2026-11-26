<?php
require '../config/koneksi.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM buku WHERE id_buku = :id");
    $stmt->execute([':id' => $id]);
}

header("Location: ../index.php");
exit;