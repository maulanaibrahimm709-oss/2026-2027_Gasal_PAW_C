<?php
require_once __DIR__ . '/../../helpers/laporanHelper.php';

$mulai   = $_GET['tanggal_mulai']   ?? '';
$selesai = $_GET['tanggal_selesai'] ?? '';

// Buang layout HTML (nav, dsb.) yang sudah ditampung ob_start() di index.php,
// supaya file .xls hanya berisi tabel rekapitulasi.
while (ob_get_level() > 0) { ob_end_clean(); }

if (!tanggalValid($mulai) || !tanggalValid($selesai) || $mulai > $selesai) {
    http_response_code(400);
    echo 'Rentang tanggal tidak valid.';
    exit;
}

$data = ambilLaporan($conn, $mulai, $selesai);

// HTTP Response Header: browser diminta mengunduh respons sebagai file Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Rekap_Penjualan_' . $mulai . '_sd_' . $selesai . '.xls"');
header('Pragma: no-cache');
header('Expires: 0');
?>
<html>
<head><meta charset="UTF-8"></head>
<body>
<table border="1">
    <tr><th colspan="3">Rekap Laporan Penjualan <?= $mulai; ?> sampai <?= $selesai; ?></th></tr>
    <tr><th>No</th><th>Total Pendapatan</th><th>Tanggal Transaksi</th></tr>
    <?php $no = 1; foreach ($data['rekap'] as $row): ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= (int)$row['total_harian']; ?></td>
        <td><?= tglIndo($row['tgl']); ?></td>
    </tr>
    <?php endforeach; ?>
    <tr><td colspan="2"><b>Jumlah Pelanggan Unik</b></td><td><?= $data['pelanggan']; ?> Orang</td></tr>
    <tr><td colspan="2"><b>Total Pendapatan Keseluruhan</b></td><td><?= (int)$data['pendapatan']; ?></td></tr>
</table>
</body>
</html>
<?php exit; ?>
