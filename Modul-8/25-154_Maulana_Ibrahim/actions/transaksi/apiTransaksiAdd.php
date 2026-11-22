<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'] ?? '';
    $pelanggan_id = (int)($_POST['pelanggan_id'] ?? 0);
    $user_id = userId();                       // kasir = user yang sedang login (dari session)

    try {
        $stmt = $conn->prepare("SELECT nama_pelanggan FROM pelanggan WHERE id = ?");
        $stmt->bind_param("i", $pelanggan_id);
        $stmt->execute();
        $p = $stmt->get_result()->fetch_assoc();
        $d = DateTime::createFromFormat('Y-m-d', $tanggal);
        if (!$p || !$d || $d->format('Y-m-d') !== $tanggal) {
            setFlash('error', 'Tanggal dan pelanggan wajib diisi dengan benar.');
            header('Location: ?url=/transaksi/add');
            exit;
        }
        $nama = $p['nama_pelanggan'];
        $stmt = $conn->prepare("INSERT INTO transaksi (tanggal, pelanggan, pelanggan_id, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $tanggal, $nama, $pelanggan_id, $user_id);
        $stmt->execute();
        $baru = $conn->insert_id;
        header('Location: ?url=/transaksiDetail/add&transaksi_id=' . $baru);   // lanjut isi barang
        exit;
    } catch (Exception $e) {
        echo "ERROR : " . $e->getMessage();
    }
}
