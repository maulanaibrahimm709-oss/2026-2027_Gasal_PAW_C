CREATE DATABASE IF NOT EXISTS penjualan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE penjualan;

CREATE TABLE supplier (
 id INT AUTO_INCREMENT PRIMARY KEY,
 nama VARCHAR(100) NOT NULL,
 telp VARCHAR(12),
 alamat TEXT
) ENGINE=InnoDB;

CREATE TABLE pelanggan (
 id VARCHAR(20) PRIMARY KEY,
 nama VARCHAR(100) NOT NULL,
 jenis_kelamin ENUM('L','P') NOT NULL,
 telp VARCHAR(12),
 alamat TEXT
) ENGINE=InnoDB;

CREATE TABLE barang (
 id INT AUTO_INCREMENT PRIMARY KEY,
 kode_barang VARCHAR(10) NOT NULL UNIQUE,
 nama_barang VARCHAR(100) NOT NULL,
 harga INT NOT NULL,
 stok INT NOT NULL DEFAULT 0,
 supplier_id INT,
 CONSTRAINT fk_barang_supplier FOREIGN KEY (supplier_id) REFERENCES supplier(id)
   ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE transaksi (
 id INT AUTO_INCREMENT PRIMARY KEY,
 waktu_transaksi DATE NOT NULL,
 keterangan TEXT,
 total INT NOT NULL DEFAULT 0,
 pelanggan_id VARCHAR(20) NOT NULL,
 CONSTRAINT fk_transaksi_pelanggan FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id)
   ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE transaksi_detail (
 id INT AUTO_INCREMENT PRIMARY KEY,
 transaksi_id INT NOT NULL,
 barang_id INT NOT NULL,
 harga INT NOT NULL,
 qty INT NOT NULL,
 CONSTRAINT uq_transaksi_barang UNIQUE (transaksi_id, barang_id),
 CONSTRAINT fk_detail_transaksi FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
   ON UPDATE CASCADE ON DELETE CASCADE,
 CONSTRAINT fk_detail_barang FOREIGN KEY (barang_id) REFERENCES barang(id)
   ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE pembayaran (
 id INT AUTO_INCREMENT PRIMARY KEY,
 waktu_bayar DATETIME NOT NULL,
 total INT NOT NULL,
 metode ENUM('TUNAI','TRANSFER','EDC') NOT NULL,
 transaksi_id INT NOT NULL,
 CONSTRAINT fk_pembayaran_transaksi FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
   ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE user (
 id_user TINYINT PRIMARY KEY,
 username VARCHAR(30) NOT NULL UNIQUE,
 password VARCHAR(35) NOT NULL,
 nama VARCHAR(50) NOT NULL,
 alamat VARCHAR(150),
 hp VARCHAR(20),
 level TINYINT NOT NULL
) ENGINE=InnoDB;

INSERT INTO supplier (nama,telp,alamat) VALUES
('CV Sumber Makmur','081234567801','Bangkalan'),
('UD Jaya Abadi','081234567802','Pamekasan'),
('PT Madura Niaga','081234567803','Sampang'),
('Toko Sentosa','081234567804','Sumenep'),
('CV Berkah','081234567805','Surabaya');

INSERT INTO pelanggan (id,nama,jenis_kelamin,telp,alamat) VALUES
('PLG001','Andi Pratama','L','081200000001','Bangkalan'),
('PLG002','Siti Aminah','P','081200000002','Pamekasan'),
('PLG003','Budi Santoso','L','081200000003','Sampang'),
('PLG004','Dewi Lestari','P','081200000004','Sumenep'),
('PLG005','Rina Oktavia','P','081200000005','Bangkalan');

INSERT INTO barang (kode_barang,nama_barang,harga,stok,supplier_id) VALUES
('BRG001','Buku Tulis',5000,100,1),
('BRG002','Pulpen',3000,150,2),
('BRG003','Pensil',2500,120,3),
('BRG004','Penghapus',2000,80,4),
('BRG005','Penggaris',4000,70,5),
('BRG006','Spidol',7000,60,1),
('BRG007','Map Plastik',3500,90,2);

INSERT INTO transaksi (waktu_transaksi,keterangan,total,pelanggan_id) VALUES
('2026-10-01','Pembelian alat tulis',0,'PLG001'),
('2026-10-02','Pembelian perlengkapan',0,'PLG002'),
('2026-10-03','Pembelian sekolah',0,'PLG003'),
('2026-10-04','Pembelian kantor',0,'PLG004'),
('2026-10-05','Pembelian alat tulis',0,'PLG005'),
('2026-10-06','Pembelian perlengkapan',0,'PLG001'),
('2026-10-07','Pembelian sekolah',0,'PLG002'),
('2026-10-08','Pembelian kantor',0,'PLG003'),
('2026-10-09','Pembelian alat tulis',0,'PLG004'),
('2026-10-10','Pembelian perlengkapan',0,'PLG005');

INSERT INTO transaksi_detail (transaksi_id,barang_id,harga,qty) VALUES
(1,1,5000,2),(1,2,3000,1),(2,3,2500,4),(2,4,2000,2),
(3,5,4000,3),(3,6,7000,1),(4,7,3500,5),(4,1,5000,1),
(5,2,3000,3),(5,3,2500,2),(6,4,2000,4),(6,5,4000,2),
(7,6,7000,2),(7,7,3500,1),(8,1,5000,3),(8,3,2500,1),
(9,2,3000,5),(9,5,4000,1),(10,4,2000,2),(10,7,3500,3);

UPDATE transaksi t SET total = (
 SELECT COALESCE(SUM(td.harga * td.qty),0) FROM transaksi_detail td WHERE td.transaksi_id=t.id
);

INSERT INTO pembayaran (waktu_bayar,total,metode,transaksi_id) VALUES
('2026-10-01 10:00:00',13000,'TUNAI',1),
('2026-10-02 10:15:00',14000,'TRANSFER',2),
('2026-10-03 11:00:00',19000,'EDC',3),
('2026-10-04 12:00:00',22500,'TUNAI',4),
('2026-10-05 13:00:00',14000,'TRANSFER',5);

INSERT INTO user (id_user,username,password,nama,alamat,hp,level) VALUES
(1,'admin','admin123','Administrator','Bangkalan','081234567890',1),
(2,'operator1','op123','Operator Satu','Pamekasan','081234567891',2),
(3,'operator2','op123','Operator Dua','Sampang','081234567892',2),
(4,'kasir1','kasir123','Kasir Satu','Sumenep','081234567893',3),
(5,'kasir2','kasir123','Kasir Dua','Bangkalan','081234567894',3);
