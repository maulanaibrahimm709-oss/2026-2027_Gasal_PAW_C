<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nama_supplier = trim($_POST['nama_supplier'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $err = '';
    if ($nama_supplier === '') $err = 'Nama Supplier wajib diisi.';
    if ($err !== '') {
        setFlash('error', $err);
        header('Location: ?url=/supplier/edit&id=' . $id);
        exit;
    }
    try {
        $stmt = $conn->prepare("UPDATE `supplier` SET nama_supplier = ?, alamat = ?, telepon = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nama_supplier, $alamat, $telepon, $id);
        $stmt->execute();
        setFlash('ok', 'Supplier berhasil diubah.');
        header('Location: ?url=/supplier');
        exit;
    } catch (mysqli_sql_exception $ex) {
        setFlash('error', 'Gagal menyimpan data.');
        header('Location: ?url=/supplier/edit&id=' . $id);
        exit;
    }
}
