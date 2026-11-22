<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nama_pelanggan = trim($_POST['nama_pelanggan'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $err = '';
    if ($nama_pelanggan === '') $err = 'Nama Pelanggan wajib diisi.';
    if ($err !== '') {
        setFlash('error', $err);
        header('Location: ?url=/pelanggan/edit&id=' . $id);
        exit;
    }
    try {
        $stmt = $conn->prepare("UPDATE `pelanggan` SET nama_pelanggan = ?, alamat = ?, telepon = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nama_pelanggan, $alamat, $telepon, $id);
        $stmt->execute();
        setFlash('ok', 'Pelanggan berhasil diubah.');
        header('Location: ?url=/pelanggan');
        exit;
    } catch (mysqli_sql_exception $ex) {
        setFlash('error', 'Gagal menyimpan data.');
        header('Location: ?url=/pelanggan/edit&id=' . $id);
        exit;
    }
}
