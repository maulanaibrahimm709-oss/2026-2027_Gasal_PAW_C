<?php
require_once __DIR__ . '/../../helpers/laporanHelper.php';
$id = (int)($_GET['id'] ?? 0);
$trx = null; $items = [];
try {
    $stmt = $conn->prepare("SELECT t.id, t.tanggal, t.pelanggan, u.nama AS kasir FROM transaksi t LEFT JOIN `user` u ON u.id = t.user_id WHERE t.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $trx = $stmt->get_result()->fetch_assoc();
    if ($trx) {
        $stmt = $conn->prepare("SELECT b.nama_barang, b.harga, td.qty, (b.harga * td.qty) AS subtotal FROM transaksi_detail td JOIN barang b ON b.id = td.barang_id WHERE td.transaksi_id = ? ORDER BY td.id");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    echo "ERROR : " . $e->getMessage();
}
?>
<?php if (!$trx): ?>
    <h2>Struk</h2>
    <p class="pesan pesan-error">Transaksi tidak ditemukan.</p>
<?php else:
    $total = 0; $jumlah = 0;
    foreach ($items as $it) { $total += (int)$it['subtotal']; $jumlah += (int)$it['qty']; }
?>
<div class="struk">
    <div class="struk-judul">SISTEM PENJUALAN</div>
    <div class="struk-sub">Toko Alat Tulis &bull; Struk Pembelian</div>
    <hr>
    <div class="struk-baris"><span>No. Struk</span><span>#<?= str_pad((string)$trx['id'], 6, '0', STR_PAD_LEFT); ?></span></div>
    <div class="struk-baris"><span>Tanggal</span><span><?= htmlspecialchars(tglIndo($trx['tanggal'])); ?></span></div>
    <div class="struk-baris"><span>Pelanggan</span><span><?= htmlspecialchars($trx['pelanggan']); ?></span></div>
    <div class="struk-baris"><span>Kasir</span><span><?= htmlspecialchars($trx['kasir'] ?? '-'); ?></span></div>
    <hr>
    <?php if (empty($items)): ?>
        <p class="struk-sub">Belum ada barang pada transaksi ini.</p>
    <?php endif; ?>
    <?php foreach ($items as $it): ?>
        <div class="struk-item"><?= htmlspecialchars($it['nama_barang']); ?></div>
        <div class="struk-baris"><span><?= (int)$it['qty']; ?> x <?= number_format((float)$it['harga'], 0, ',', '.'); ?></span><span><?= number_format((float)$it['subtotal'], 0, ',', '.'); ?></span></div>
    <?php endforeach; ?>
    <hr>
    <div class="struk-baris"><span>Jumlah item</span><span><?= $jumlah; ?></span></div>
    <div class="struk-baris struk-total"><span>TOTAL</span><span><?= rupiah($total); ?></span></div>
    <hr>
    <div class="struk-terima">Terima kasih telah berbelanja!</div>
    <div class="struk-barcode"></div>
    <div class="struk-sub">Dicetak <?= date('d-m-Y H:i'); ?></div>
</div>
<div class="aksi-struk no-print">
    <a href="?url=/transaksi">Kembali</a>
    <a href="?url=/transaksiDetail&id=<?= (int)$trx['id']; ?>">Detail</a>
    <button type="button" class="btn-cetak" onclick="window.print()">Cetak Struk</button>
</div>
<?php if (isset($_GET['cetak'])): ?><script>window.addEventListener('load', function () { window.print(); });</script><?php endif; ?>
<?php endif; ?>
