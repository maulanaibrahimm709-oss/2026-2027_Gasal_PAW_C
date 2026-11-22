<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pelanggan = trim($_POST['nama_pelanggan'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $err = '';
    if ($nama_pelanggan === '') $err = 'Nama Pelanggan wajib diisi.';
    if ($err !== '') {
        setFlash('error', $err);
        header('Location: ?url=/pelanggan/add');
        exit;
    }
    try {
        $stmt = $conn->prepare("INSERT INTO `pelanggan` (nama_pelanggan, alamat, telepon) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama_pelanggan, $alamat, $telepon);
        $stmt->execute();
        setFlash('ok', 'Pelanggan berhasil ditambahkan.');
        header('Location: ?url=/pelanggan');
        exit;
    } catch (mysqli_sql_exception $ex) {
        setFlash('error', 'Gagal menyimpan data.');
        header('Location: ?url=/pelanggan/add');
        exit;
    }
}
