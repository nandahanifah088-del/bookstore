-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2025 at 11:13 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama_admin` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `email`, `password`) VALUES
(1, 'admin nanda', 'adminbooksmart@gmail.com', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `judul` varchar(100) NOT NULL,
  `penulis` varchar(100) DEFAULT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `id_kategori`, `judul`, `penulis`, `penerbit`, `harga`, `stok`, `gambar`, `deskripsi`) VALUES
(1, 1, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 99000, 49, 'laskar_pelangi.jpg', 'Laskar Pelangi adalah novel dan film inspiratif karya Andrea Hirata yang mengisahkan perjuangan sepuluh anak dari keluarga miskin di Belitung yang bersekolah di SD Muhammadiyah Gantong. Meski menghadapi keterbatasan fasilitas dan kemiskinan, mereka memiliki semangat belajar yang tinggi di bawah bimbingan guru mereka, Bu Muslimah dan Pak Harfan. Cerita ini menyoroti semangat persahabatan, mimpi, dan harapan di tengah tantangan kehidupan. '),
(2, 1, 'Cantik Itu Luka', 'Eka kurniawan', 'Gramedia Utama', 100000, 44, 'cantik_itu_luka.jpg', '\"Cantik Itu Luka\" adalah novel karya Eka Kurniawan yang bergenre realisme magis, bercerita tentang Dewi Ayu, seorang perempuan cantik yang menjadi pelacur, dan kutukan kecantikan yang menimpa keluarganya serta memicu rangkaian peristiwa tragis sejak masa kolonial hingga pasca-kemerdekaan Indonesia. Melalui kisah yang kompleks, novel ini mengeksplorasi tema cinta, penderitaan, kekerasan seksual, dan dampak sejarah melalui gaya penceritaan yang sureal dan karakter-karakter yang kuat.'),
(3, 1, 'Bandung After Rain', 'Wulan Nur Amalia', 'Black Swan', 99000, 30, 'bandung_after_rain.jpeg', '\"Bandung After Rain\" adalah novel romansa karya Wulan Nur Amalia tentang Hema dan Rania yang menghadapi masalah cinta dan penyesalan di Kota Bandung yang berlatar belakang suasana setelah hujan. Novel ini menyoroti pentingnya komunikasi, penerimaan diri, dan perjuangan memperbaiki kesalahan dalam sebuah hubungan, menggunakan latar Bandung sebagai simbol kenangan dan perjalanan emosional tokoh.'),
(4, 1, 'Hujan', 'Tere Liye', 'Bentang Pustaka', 99000, 50, 'hujan.jpg', 'Novel \"Hujan\" karya Tere Liye mengisahkan Lail, seorang gadis yang hidup di masa depan tahun 2042 setelah kehilangan orang tuanya akibat bencana alam, dan bertemu Esok yang menjadi kekasihnya. Setelah terpisah oleh kondisi bencana dan kemudian teknologi, Lail dihadapkan pada pilihan untuk menghapus ingatan menyakitkan tentang Esok melalui prosedur medis, namun akhirnya memilih menerima dan menghargai kenangan tersebut.'),
(5, 1, 'Sang Pemimpi', 'Andre Hirata', 'Gramedia Emas', 90000, 50, 'sang_pemimpi.webp', 'Sang Pemimpi adalah novel karya Andrea Hirata yang bercerita tentang perjuangan tiga sahabat, Ikal, Arai, dan Jimbron, di Belitung untuk meraih mimpi mereka bersekolah tinggi hingga ke Eropa, Prancis. Meskipun hidup dalam kesulitan ekonomi, mereka bekerja keras sambil sekolah dan saling menguatkan untuk meraih pendidikan impian mereka, menginspirasi pembaca tentang harapan, kerja keras, dan persahabatan.'),
(6, 1, 'Garis Waktu', 'Fiersa Besari', 'Bintang ', 110000, 40, 'novel_garis_waktu.jpg', 'Garis Waktu adalah novel debut Fiersa Besari (2016) yang berisi kumpulan cerita dan pemikiran pribadi sang penulis tentang cinta, patah hati, dan penyembuhan diri selama lima tahun (2012-2016) yang dikemas dalam bentuk surat-surat seorang pria kepada kekasihnya, kemudian menjadi pelajaran tentang keikhlasan untuk melepaskan dan menerima takdir.'),
(7, 1, 'Diary of Canva', 'ItaKrn', 'Pustaka Pelajar', 99000, 39, 'diary_of_canva.jpg', '\"Diary of Canva\" adalah novel karya ItaKrn (MartabakKolor) tentang Canva Narendra, seorang remaja rapuh yang hidupnya dipenuhi luka batin, namun berusaha membahagiakan orang lain, terutama Aily, dan berjuang untuk sembuh dari penyakit ginjal demi mewujudkan cita-citanya bertemu orang tua. Ia mencurahkan perasaannya dalam diary alih-alih media sosial.'),
(8, 2, 'One Piece', 'Eiichiro Oda', 'Pustakara', 45000, 35, 'one_piece.jpg', 'Komik One Piece adalah seri manga populer tentang petualangan Monkey D. Luffy dan kru bajak lautnya, Bajak Laut Topi Jerami, yang mengarungi lautan luas untuk menemukan harta karun legendaris bernama \"One Piece\" dan mewujudkan impian Luffy menjadi Raja Bajak Laut. Dibuat oleh Eiichiro Oda, serial ini menampilkan tema persahabatan, kebebasan, dan perlawanan terhadap penindasan, menjadikannya salah satu manga paling laris dan berpengaruh di dunia.'),
(9, 2, 'The Villainess Turns the Hourglass', 'Sansobee', 'Ize Press', 380000, 15, 'villaines.jpg', 'The Villainess Turns the Hourglass\" adalah kisah tentang Aria Roscente, seorang wanita bangsawan yang hidupnya penuh dengan penghinaan akibat saudara tirinya, Mielle, hingga akhirnya dieksekusi. Setelah meninggal, ia kembali ke masa lalunya dan menggunakan kesempatan kedua ini untuk membalas dendam pada Mielle dan seluruh keluarganya yang telah memperdayanya.'),
(10, 2, 'Solo Leveling', 'Noboru Kimura', 'Gramedia', 250000, 15, 'solo_leveling.jpg', 'Solo Leveling adalah manhwa (komik Korea) tentang Sung Jin-Woo, seorang hunter terlemah di dunia yang mendapatkan kemampuan luar biasa untuk \"level up\" sendirian melalui sebuah sistem misterius setelah hampir mati dalam sebuah misi. Ia kemudian menggunakan kekuatan barunya untuk menjadi hunter terkuat, mengungkap misteri di balik dunia monster dan gate, serta menghadapi ancaman yang lebih besar.'),
(11, 6, 'Jaringan Komputer', 'Januar Al Imran', 'Gramedia', 100000, 20, 'jaringan komputer.jpg', 'Buku ajar \"Jaringan Komputer dan Internet\" adalah panduan komprehensif yang membahas dasar-dasar jaringan komputer dan internet, mulai dari pengenalan komponen dan protokol, hingga konsep lanjutan seperti virtualisasi dan keamanan. Buku ini disusun secara sistematis, menggunakan bahasa yang jelas, dan ditujukan bagi pembaca untuk memahami cara kerja jaringan, cara mengkonfigurasi, serta topik penting lainnya seperti topologi, model OSI, dan pengamanan jaringan. ');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`jumlah` * `harga`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_buku`, `jumlah`, `harga`) VALUES
(1, 1, 7, 1, '0.00'),
(2, 2, 11, 5, '0.00'),
(3, 3, 2, 1, '0.00'),
(4, 3, 1, 1, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Novel'),
(2, 'Komik'),
(3, 'Biografi'),
(4, 'Pendidikan'),
(5, 'Fiksi'),
(6, 'Komputer');

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `jumlah` int(11) DEFAULT 1,
  `tanggal_ditambahkan` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pesan`
--

CREATE TABLE `pesan` (
  `id_pesan` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama_pengirim` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subjek` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `balasan` text DEFAULT NULL,
  `status` enum('Belum Dibalas','Sudah Dibalas') DEFAULT 'Belum Dibalas',
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `dibaca_user` enum('Sudah','Belum') DEFAULT 'Sudah',
  `dibaca_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pesan`
--

INSERT INTO `pesan` (`id_pesan`, `id_user`, `nama_pengirim`, `email`, `subjek`, `isi`, `balasan`, `status`, `tanggal`, `dibaca_user`, `dibaca_admin`) VALUES
(1, 2, 'Wahidin', 'wahidin@gmail.com', 'tanya buku', 'ka pesanan saya kapan sampai', NULL, 'Belum Dibalas', '2025-10-20 08:59:57', 'Sudah', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jumlah_bayar` decimal(10,2) NOT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `rekening_tujuan` varchar(50) DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT curdate(),
  `status` varchar(50) DEFAULT 'menunggu konfirmasi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_user`, `jumlah_bayar`, `metode_pembayaran`, `alamat`, `no_hp`, `rekening_tujuan`, `bukti_transfer`, `tanggal`, `status`) VALUES
(1, 1, '99000.00', 'Transfer Bank', 'Desa Tonggara RT 05 RW 02', '089756561811', '1234567890 (Bank BRI a.n BookSmart)', '1760939395_bukti_transfer.jpg', '2025-10-20', 'dikirim'),
(2, 2, '500000.00', 'Transfer Bank', 'Desa Tegalandong', '08673562710', '1234567890 (Bank BRI a.n BookSmart)', '1760950732_bukti_transfer.jpg', '2025-10-20', 'diproses'),
(3, 2, '199000.00', 'COD (Bayar di Tempat)', 'Desa Tegalandong', '089756561811', '-', NULL, '2025-10-20', 'diproses');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tanggal_daftar` datetime NOT NULL DEFAULT current_timestamp(),
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `email`, `password`, `tanggal_daftar`, `telepon`, `alamat`, `foto`) VALUES
(1, 'Hanifah', 'hani@gmail.com', '123', '2025-10-20 10:32:58', '089604072498', 'Desa Tonggara RT 05 RW 02 Kec. Kedungbanteng Kab. Tegal', '1760933724_@xinsooo 🧡🍊🧡.jfif'),
(2, 'Wahidin', 'wahidin@gmail.com', '1234', '2025-10-20 15:27:44', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD KEY `fk_buku_kategori` (`id_kategori`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indexes for table `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id_pesan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id_pesan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `fk_buku_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE;

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE;

--
-- Constraints for table `pesan`
--
ALTER TABLE `pesan`
  ADD CONSTRAINT `fk_pesan_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
