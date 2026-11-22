<?php
$id = (int)($_GET['id'] ?? 0);
if ($id === userId()) {
    setFlash('error', 'Akun yang sedang login tidak dapat dihapus.');
    header('Location: ?url=/user');
    exit;
}
try {
    $stmt = $conn->prepare("DELETE FROM `user` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    setFlash('ok', 'User berhasil dihapus.');
} catch (mysqli_sql_exception $ex) {
    setFlash('error', 'Data tidak dapat dihapus.');
}
header('Location: ?url=/user');
exit;
