<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_supplier = trim($_POST['nama_supplier'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $err = '';
    if ($nama_supplier === '') $err = 'Nama Supplier wajib diisi.';
    if ($err !== '') {
        setFlash('error', $err);
        header('Location: ?url=/supplier/add');
        exit;
    }
    try {
        $stmt = $conn->prepare("INSERT INTO `supplier` (nama_supplier, alamat, telepon) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama_supplier, $alamat, $telepon);
        $stmt->execute();
        setFlash('ok', 'Supplier berhasil ditambahkan.');
        header('Location: ?url=/supplier');
        exit;
    } catch (mysqli_sql_exception $ex) {
        setFlash('error', 'Gagal menyimpan data.');
        header('Location: ?url=/supplier/add');
        exit;
    }
}
