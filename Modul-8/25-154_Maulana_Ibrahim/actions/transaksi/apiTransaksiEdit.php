<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $tanggal = $_POST['tanggal'] ?? '';
    $pelanggan_id = (int)($_POST['pelanggan_id'] ?? 0);

    try {
        $stmt = $conn->prepare("SELECT nama_pelanggan FROM pelanggan WHERE id = ?");
        $stmt->bind_param("i", $pelanggan_id);
        $stmt->execute();
        $p = $stmt->get_result()->fetch_assoc();
        $d = DateTime::createFromFormat('Y-m-d', $tanggal);
        if (!$p || !$d || $d->format('Y-m-d') !== $tanggal) {
            setFlash('error', 'Tanggal dan pelanggan wajib diisi dengan benar.');
            header('Location: ?url=/transaksi/edit&id=' . $id);
            exit;
        }
        $nama = $p['nama_pelanggan'];
        $stmt = $conn->prepare("UPDATE transaksi SET tanggal = ?, pelanggan = ?, pelanggan_id = ? WHERE id = ?");
        $stmt->bind_param("ssii", $tanggal, $nama, $pelanggan_id, $id);
        $stmt->execute();
        header('Location: ?url=/transaksi');
        exit;
    } catch (Exception $e) {
        echo "ERROR : " . $e->getMessage();
    }
}
