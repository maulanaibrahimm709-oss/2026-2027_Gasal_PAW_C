CREATE DATABASE IF NOT EXISTS penjualan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE penjualan;
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS pembayaran, transaksi_detail, transaksi, barang, pelanggan, supplier, `user`;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE pelanggan(id INT AUTO_INCREMENT PRIMARY KEY,nama VARCHAR(100) NOT NULL,jenis_kelamin ENUM('L','P') NOT NULL,telp VARCHAR(20) NOT NULL,alamat TEXT NOT NULL) ENGINE=InnoDB;
CREATE TABLE supplier(id INT AUTO_INCREMENT PRIMARY KEY,nama VARCHAR(100) NOT NULL,telp VARCHAR(20) NOT NULL,alamat TEXT NOT NULL) ENGINE=InnoDB;
CREATE TABLE barang(id INT AUTO_INCREMENT PRIMARY KEY,kode_barang VARCHAR(30) NOT NULL UNIQUE,nama_barang VARCHAR(100) NOT NULL,harga DECIMAL(12,2) NOT NULL,stok INT NOT NULL,supplier_id INT NOT NULL,FOREIGN KEY(supplier_id) REFERENCES supplier(id)) ENGINE=InnoDB;
CREATE TABLE transaksi(id INT AUTO_INCREMENT PRIMARY KEY,waktu_transaksi DATETIME NOT NULL,keterangan TEXT,total DECIMAL(12,2) NOT NULL,pelanggan_id INT NOT NULL,FOREIGN KEY(pelanggan_id) REFERENCES pelanggan(id)) ENGINE=InnoDB;
CREATE TABLE transaksi_detail(transaksi_id INT NOT NULL,barang_id INT NOT NULL,harga DECIMAL(12,2) NOT NULL,qty INT NOT NULL,PRIMARY KEY(transaksi_id,barang_id),FOREIGN KEY(transaksi_id) REFERENCES transaksi(id),FOREIGN KEY(barang_id) REFERENCES barang(id)) ENGINE=InnoDB;
CREATE TABLE `user`(id_user INT AUTO_INCREMENT PRIMARY KEY,username VARCHAR(50) NOT NULL UNIQUE,password VARCHAR(255) NOT NULL,nama VARCHAR(100) NOT NULL,alamat TEXT NOT NULL,hp VARCHAR(20) NOT NULL,`level` VARCHAR(30) NOT NULL) ENGINE=InnoDB;
CREATE TABLE pembayaran(id INT AUTO_INCREMENT PRIMARY KEY,waktu_bayar DATETIME NOT NULL,total DECIMAL(12,2) NOT NULL,metode ENUM('Tunai','Transfer','EDC') NOT NULL,transaksi_id INT NOT NULL UNIQUE,FOREIGN KEY(transaksi_id) REFERENCES transaksi(id)) ENGINE=InnoDB;

INSERT INTO pelanggan(nama,jenis_kelamin,telp,alamat) VALUES
('Andi Saputra','L','081234560001','Jl. Melati 1'),('Budi Santoso','L','081234560002','Jl. Mawar 2'),('Citra Lestari','P','081234560003','Jl. Kenanga 3'),('Dewi Anggraini','P','081234560004','Jl. Anggrek 4'),('Eko Prasetyo','L','081234560005','Jl. Flamboyan 5'),('Fitri Amelia','P','081234560006','Jl. Dahlia 6'),('Gilang Ramadhan','L','081234560007','Jl. Kamboja 7'),('Hana Putri','P','081234560008','Jl. Teratai 8'),('Irfan Maulana','L','081234560009','Jl. Cempaka 9'),('Jihan Safitri','P','081234560010','Jl. Merpati 10');
INSERT INTO supplier(nama,telp,alamat) VALUES
('CV Sumber Makmur','0317000001','Surabaya'),('PT Berkah Jaya','0317000002','Sidoarjo'),('UD Mitra Niaga','0317000003','Bangkalan'),('CV Karya Abadi','0317000004','Gresik'),('PT Sentosa','0317000005','Lamongan'),('UD Lancar Rezeki','0317000006','Pamekasan'),('CV Maju Bersama','0317000007','Sumenep'),('PT Anugerah','0317000008','Mojokerto'),('UD Sinar Terang','0317000009','Pasuruan'),('CV Bintang Timur','0317000010','Jombang');
INSERT INTO barang(kode_barang,nama_barang,harga,stok,supplier_id) VALUES
('BRG001','Buku Tulis',5000,100,1),('BRG002','Pulpen',3000,150,2),('BRG003','Pensil',2500,120,3),('BRG004','Penghapus',2000,90,4),('BRG005','Penggaris',4000,80,5),('BRG006','Spidol',7000,60,6),('BRG007','Map Plastik',3500,75,7),('BRG008','Kertas A4',55000,40,8),('BRG009','Stapler',18000,25,9),('BRG010','Isi Staples',6000,50,10);
INSERT INTO transaksi(waktu_transaksi,keterangan,total,pelanggan_id) VALUES
('2026-10-01 09:00:00','Pembelian buku tulis',5000,1),('2026-10-01 09:15:00','Pembelian pulpen',6000,2),('2026-10-02 10:00:00','Pembelian pensil',7500,3),('2026-10-02 11:00:00','Pembelian penghapus',8000,4),('2026-10-03 08:30:00','Pembelian penggaris',12000,5),('2026-10-03 13:00:00','Pembelian spidol',14000,6),('2026-10-04 09:45:00','Pembelian map',10500,7),('2026-10-04 14:00:00','Pembelian kertas',55000,8),('2026-10-05 10:30:00','Pembelian stapler',36000,9),('2026-10-05 15:00:00','Pembelian isi staples',12000,10);
INSERT INTO transaksi_detail VALUES(1,1,5000,1),(2,2,3000,2),(3,3,2500,3),(4,4,2000,4),(5,5,4000,3),(6,6,7000,2),(7,7,3500,3),(8,8,55000,1),(9,9,18000,2),(10,10,6000,2);
INSERT INTO `user`(username,password,nama,alamat,hp,`level`) VALUES
('admin','admin123','Administrator','Kantor','081200000001','Admin'),('kasir01','kasir123','Kasir Satu','Bangkalan','081200000002','Kasir'),('kasir02','kasir123','Kasir Dua','Kamal','081200000003','Kasir'),('operator01','op123','Operator Satu','Surabaya','081200000004','Operator'),('operator02','op123','Operator Dua','Sidoarjo','081200000005','Operator'),('staff01','staff123','Staff Satu','Gresik','081200000006','Staff'),('staff02','staff123','Staff Dua','Lamongan','081200000007','Staff'),('staff03','staff123','Staff Tiga','Pamekasan','081200000008','Staff'),('staff04','staff123','Staff Empat','Sumenep','081200000009','Staff'),('staff05','staff123','Staff Lima','Mojokerto','081200000010','Staff');
INSERT INTO pembayaran(waktu_bayar,total,metode,transaksi_id) VALUES
('2026-10-01 09:05:00',5000,'Tunai',1),('2026-10-01 09:20:00',6000,'Transfer',2),('2026-10-02 10:05:00',7500,'EDC',3),('2026-10-02 11:05:00',8000,'Tunai',4),('2026-10-03 08:35:00',12000,'Transfer',5),('2026-10-03 13:05:00',14000,'EDC',6),('2026-10-04 09:50:00',10500,'Tunai',7),('2026-10-04 14:05:00',55000,'Transfer',8),('2026-10-05 10:35:00',36000,'EDC',9),('2026-10-05 15:05:00',12000,'Tunai',10);
