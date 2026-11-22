<?php
$id = (int)($_GET['id'] ?? 0);
try {
    $stmt = $conn->prepare("DELETE FROM `pelanggan` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    setFlash('ok', 'Pelanggan berhasil dihapus.');
} catch (mysqli_sql_exception $ex) {
    setFlash('error', 'Data tidak dapat dihapus.');
}
header('Location: ?url=/pelanggan');
exit;
