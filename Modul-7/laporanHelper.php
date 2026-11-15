<?php
// Helper laporan: dipakai oleh views/laporan/laporan.php dan actions/laporan/apiLaporanExcel.php

/** Validasi format tanggal YYYY-MM-DD */
function tanggalValid($tgl) {
    $d = DateTime::createFromFormat('Y-m-d', (string)$tgl);
    return $d && $d->format('Y-m-d') === $tgl;
}

/** Format rupiah: Rp. 39.000 */
function rupiah($angka) {
    return 'Rp. ' . number_format((float)$angka, 0, ',', '.');
}

/** Format tanggal: 01 Sep 2026 */
function tglIndo($tgl) {
    $bln = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $t = strtotime($tgl);
    return date('d', $t) . ' ' . $bln[(int)date('n', $t) - 1] . ' ' . date('Y', $t);
}

/**
 * Ambil data laporan penjualan pada rentang tanggal.
 * return: ['rekap' => [[tgl,total_harian],...], 'pelanggan' => int, 'pendapatan' => int]
 */
function ambilLaporan($conn, $mulai, $selesai) {
    $hasil = ['rekap' => [], 'pelanggan' => 0, 'pendapatan' => 0];

    // 1) Agregasi pendapatan harian: SUM() + GROUP BY DATE()
    $sql = "SELECT DATE(tanggal) AS tgl, SUM(total) AS total_harian
            FROM transaksi
            WHERE tanggal BETWEEN ? AND ?
            GROUP BY DATE(tanggal)
            ORDER BY tgl ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $mulai, $selesai);
    $stmt->execute();
    $hasil['rekap'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // 2) Ringkasan kumulatif: pelanggan unik + total pendapatan keseluruhan
    $sql = "SELECT COUNT(DISTINCT pelanggan) AS jml_pelanggan,
                   COALESCE(SUM(total), 0)   AS total_pendapatan
            FROM transaksi
            WHERE tanggal BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $mulai, $selesai);
    $stmt->execute();
    $ringkas = $stmt->get_result()->fetch_assoc();
    $hasil['pelanggan']  = (int)$ringkas['jml_pelanggan'];
    $hasil['pendapatan'] = (int)$ringkas['total_pendapatan'];

    return $hasil;
}
