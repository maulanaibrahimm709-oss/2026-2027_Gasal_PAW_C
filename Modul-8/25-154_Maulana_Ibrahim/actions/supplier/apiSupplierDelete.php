<?php
$id = (int)($_GET['id'] ?? 0);
try {
    $stmt = $conn->prepare("DELETE FROM `supplier` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    setFlash('ok', 'Supplier berhasil dihapus.');
} catch (mysqli_sql_exception $ex) {
    setFlash('error', 'Data tidak dapat dihapus.');
}
header('Location: ?url=/supplier');
exit;
