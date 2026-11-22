<?php
	// koneksi TANPA memilih database (KampusDB dibuat oleh skrip ini)
	$conn = mysqli_connect("localhost", "root", "");
	$hash = password_hash("123456", PASSWORD_DEFAULT); // password awal owner & kasir: 123456
	$sql = "
			CREATE DATABASE IF NOT EXISTS KampusDB;
			USE KampusDB;
			DROP TABLE IF EXISTS transaksi_detail;
			DROP TABLE IF EXISTS transaksi;
			DROP TABLE IF EXISTS barang;
			DROP TABLE IF EXISTS pelanggan;
			DROP TABLE IF EXISTS supplier;
			DROP TABLE IF EXISTS `user`;
			CREATE TABLE `user` (id INT AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(100) NOT NULL, username VARCHAR(30) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, level TINYINT NOT NULL COMMENT '1=owner, 2=kasir') ENGINE=InnoDB;
			CREATE TABLE supplier (id INT AUTO_INCREMENT PRIMARY KEY, nama_supplier VARCHAR(100) NOT NULL, alamat VARCHAR(200) NOT NULL DEFAULT '', telepon VARCHAR(20) NOT NULL DEFAULT '') ENGINE=InnoDB;
			CREATE TABLE pelanggan (id INT AUTO_INCREMENT PRIMARY KEY, nama_pelanggan VARCHAR(100) NOT NULL, alamat VARCHAR(200) NOT NULL DEFAULT '', telepon VARCHAR(20) NOT NULL DEFAULT '') ENGINE=InnoDB;
			CREATE TABLE barang (id INT AUTO_INCREMENT PRIMARY KEY, nama_barang VARCHAR(100) NOT NULL, harga INT NOT NULL) ENGINE=InnoDB;
			CREATE TABLE transaksi (id INT AUTO_INCREMENT PRIMARY KEY, tanggal DATE NOT NULL, pelanggan VARCHAR(100) NOT NULL, total INT NOT NULL DEFAULT 0, pelanggan_id INT NULL, user_id INT NULL, FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id) ON DELETE SET NULL, FOREIGN KEY (user_id) REFERENCES `user`(id) ON DELETE SET NULL) ENGINE=InnoDB;
			CREATE TABLE transaksi_detail (id INT AUTO_INCREMENT PRIMARY KEY, transaksi_id INT NOT NULL, barang_id INT NOT NULL, qty INT NOT NULL, UNIQUE (transaksi_id, barang_id), FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE RESTRICT, FOREIGN KEY (barang_id) REFERENCES barang(id) ON DELETE RESTRICT) ENGINE=InnoDB;
			INSERT INTO `user` (nama, username, password, level) VALUES
			('Budi', 'owner', '$hash', 1),
			('Wati', 'kasir', '$hash', 2);
			INSERT INTO supplier (nama_supplier, alamat, telepon) VALUES
			('PT Sumber Alat Tulis', 'Surabaya', '031-5551001'),
			('CV Maju Kertas', 'Malang', '0341-555102'),
			('UD Sinar Pena', 'Bangkalan', '031-5551003'),
			('PT Nusantara Stationery', 'Sidoarjo', '031-5551004'),
			('CV Prima Office', 'Gresik', '031-5551005');
			INSERT INTO pelanggan (nama_pelanggan, alamat, telepon) VALUES
			('Andi Pratama', 'Bangkalan', '081234567801'),
			('Budi Santoso', 'Sampang', '081234567802'),
			('Citra Dewi', 'Bangkalan', '081234567803'),
			('Deni Setiawan', 'Surabaya', '081234567804'),
			('Eka Rahmawati', 'Pamekasan', '081234567805'),
			('Fajar Nugraha', 'Bangkalan', '081234567806'),
			('Gita Permata', 'Sumenep', '081234567807'),
			('Hadi Wijaya', 'Bangkalan', '081234567808');
			INSERT INTO barang (nama_barang, harga) VALUES
			('Buku Tulis A5 38 Lembar', 5000),
			('Pulpen Gel Hitam 0.5mm', 4000),
			('Pensil 2B Kayu', 2500),
			('Penghapus Karet Putih', 1500),
			('Penggaris Plastik 30cm', 3000),
			('Correction Tape (Tipe-X)', 6000),
			('Spidol Whiteboard Hitam', 8500),
			('Map Folio Plastik Biru', 2000),
			('Sticky Notes Pastel 3x3', 7500),
			('Gunting Kertas Sedang', 9000),
			('Buku Tulis A5 58 Lembar', 6500),
			('Buku Tulis B5 Kampus', 8000),
			('Buku Gambar A4', 7000),
			('Buku Gambar A3', 12000),
			('Buku Kwitansi Besar', 5500),
			('Buku Nota Kontan 2 Ply', 4500),
			('Buku Kas Folio', 15000),
			('Buku Agenda Kerja Kulit', 35000),
			('Buku Milimeter Blok A4', 6000),
			('Kalkulator Dagang 12 Digit', 45000);
			INSERT INTO transaksi (tanggal, pelanggan, pelanggan_id, user_id, total) VALUES
			('2026-11-02', 'Fajar Nugraha', 6, 1, 46500),
			('2026-11-02', 'Deni Setiawan', 4, 1, 48000),
			('2026-11-04', 'Budi Santoso', 2, 1, 140000),
			('2026-11-05', 'Andi Pratama', 1, 1, 28000),
			('2026-11-07', 'Deni Setiawan', 4, 1, 30000),
			('2026-11-07', 'Eka Rahmawati', 5, 1, 12000),
			('2026-11-10', 'Fajar Nugraha', 6, 1, 6000),
			('2026-11-10', 'Deni Setiawan', 4, 2, 48000),
			('2026-11-12', 'Citra Dewi', 3, 1, 18000),
			('2026-11-13', 'Hadi Wijaya', 8, 2, 45500),
			('2026-11-15', 'Citra Dewi', 3, 2, 124500),
			('2026-11-15', 'Hadi Wijaya', 8, 2, 7500),
			('2026-11-18', 'Hadi Wijaya', 8, 1, 36000),
			('2026-11-18', 'Eka Rahmawati', 5, 2, 26000),
			('2026-11-20', 'Budi Santoso', 2, 2, 25500);
			INSERT INTO transaksi_detail (transaksi_id, barang_id, qty) VALUES
			(1, 2, 1),
			(1, 3, 3),
			(1, 18, 1),
			(2, 14, 4),
			(3, 18, 4),
			(4, 19, 4),
			(4, 2, 1),
			(5, 10, 2),
			(5, 14, 1),
			(6, 19, 2),
			(7, 19, 1),
			(8, 11, 3),
			(8, 15, 3),
			(8, 19, 2),
			(9, 19, 3),
			(10, 10, 4),
			(10, 3, 2),
			(10, 4, 3),
			(11, 2, 3),
			(11, 3, 3),
			(11, 18, 3),
			(12, 3, 3),
			(13, 10, 4),
			(14, 1, 3),
			(14, 15, 2),
			(15, 7, 3);
			";
	try {
		mysqli_multi_query($conn,$sql);
		while (mysqli_more_results($conn) && mysqli_next_result($conn)) {} // pastikan semua query selesai
		echo "database berhasil dibuat ulang";
	} catch (Exception $e) {
		echo "Error: ". $e->getMessage();
	};
	mysqli_close($conn);
?>